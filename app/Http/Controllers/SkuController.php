<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sku;
use App\Traits\OrganizedImages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SkuController extends Controller
{
  use OrganizedImages;
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(Product $product)
  {
    return view('skus.create', compact('product'));
  }


  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Product $product)
  {
    $validated = $request->validate([
      'price' => 'required|numeric|min:0',
      'inventory' => 'required|integer|min:0',
      'color' => 'required|string|max:255',
      'size' => 'required|string|max:255',
      'material' => 'required|string|max:255',
      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120'
    ]);

    $categoryCode = substr(strtoupper($product->category->name ?? 'GEN'), 0, 3);
    $productCode = substr(strtoupper($product->name ?? 'GEN'), 0, 3);
    $colorCode = substr(strtoupper($validated['color']), 0, 3);
    $materialCode = substr(strtoupper($validated['material']), 0, 3);
    $sizeCode = strtoupper($validated['size']);
    $code = $categoryCode . $productCode . $colorCode  . $sizeCode . $materialCode;

    if (SKU::where('code', $code)->exists()) {
      $newcode = $code . '-' . rand(10, 999);
      $code =  $newcode;
    };

    $sku = Sku::create([
      'product_id' => $product->id,
      'code' => $code,
      'price' => $validated['price'],
      'inventory' => $validated['inventory'],
      'attributes' => [
        'color' => $validated['color'],
        'size' => $validated['size'],
        'material' => $validated['material'],
      ]
    ]);

    if ($request->hasFile('images')) {
      foreach ($request->file('images') as $index => $image) {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // Get organized storage path
        $basePath = $this->getOrganizedStoragePath('sku', $sku->id);
       
        // Store the image using organized path
        $path = $image->storeAs($basePath, $filename, 'public');

        // Create image record matching your imgs table structure
        $sku->images()->create([
          'filename' => $filename,
          'original_name' => $image->getClientOriginalName(),
          'path' => $path,
          'mime_type' => $image->getMimeType(),
          'file_size' => $image->getSize(),
          'disk' => 'public',
          'type' => $index === 0 ? 'main' : 'variant',
          'order' => $index,
          'alt_text' => "{$product->name} - {$validated['color']} {$validated['size']}",
          'caption' => "Variant: {$validated['color']} | Size: {$validated['size']} | Material: {$validated['material']}",
        ]);
      }
    }

    return redirect()->route('products.show', $product->slug)->with('success', 'Variant created successfully!');
  }

  /**
   * Display the specified resource.
   */
  public function show(Sku $sku)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Sku $sku)
  {
    return view('skus.edit', compact('sku'));
  }

  /**
   * Update the specified resource in storage.
   */

  public function update(Request $request, Sku $sku)
  {
    $sku->load('product');
    $validated = $request->validate([
      'price' => 'required|numeric|min:0',
      'inventory' => 'required|numeric|min:0',
      'color' => 'required|string',
      'size' => 'required|string',
      'material' => 'required|string',
      'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
      'replace_all_images' => 'sometimes|boolean'
    ]);

    $sku->update([
      'price' => $validated['price'],
      'inventory' => $validated['inventory'],
      'attributes' => [
        'color' => $validated['color'],
        'size' => $validated['size'],
        'material' => $validated['material'],
      ]
    ]);

    // Handle image uploads
    if ($request->hasFile('images')) {
      // Get organized storage path
      $basePath = $this->getOrganizedStoragePath('sku', $sku->id);

      // Delete existing images if replacing all
      if ($request->has('replace_all_images') && $request->boolean('replace_all_images')) {
        $this->deleteSkuImages($sku);
      }

      foreach ($request->file('images') as $index => $image) {
        // Generate unique filename
        $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();

        // Store using organized path
        $path = $image->storeAs($basePath, $filename, 'public');

        // Determine image type
        $imageType = 'variant';
        if ($index === 0 && (!$sku->images()->where('type', 'main')->exists() || $request->has('replace_all_images'))) {
          $imageType = 'main';
        }

        // Get current max order
        $currentMaxOrder = $sku->images()->max('order') ?? 0;

        // Create image record
        $sku->images()->create([
          'filename' => $filename,
          'original_name' => $image->getClientOriginalName(),
          'path' => $path,
          'mime_type' => $image->getMimeType(),
          'file_size' => $image->getSize(),
          'disk' => 'public',
          'type' => $imageType,
          'order' => $currentMaxOrder + 1 + $index,
          'alt_text' => "{$sku->product->name} - {$validated['color']} {$validated['size']}",
          'caption' => "Variant: {$validated['color']} | Size: {$validated['size']} | Material: {$validated['material']}",
        ]);
      }
    }

    return redirect()->route('products.show', $sku->product->slug)->with('success', 'Variant updated successfully!');
  }
  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Sku $sku)
  {
    // variant in order
    if ($sku->orderItems()->exists()) {
      $orderCount = $sku->orderItems()->count();
      return redirect()->back()
        ->with('error', "Cannot delete this variant because it is used in $orderCount order(s).");
    }

    // Delete all associated images
    $this->deleteSkuImages($sku);

    $productSlug = $sku->product->slug;
    $sku->delete();

    return redirect()->route('products.show', $productSlug)
      ->with('success', 'Variant deleted successfully!');
  }


  private function deleteSkuImages(Sku $sku)
  {
    $images = $sku->images;

    foreach ($images as $image) {
      // Delete file from storage
      if (Storage::disk($image->disk)->exists($image->path)) {
        Storage::disk($image->disk)->delete($image->path);
      }

      // Delete image record from database
      $image->delete();
    }

    // Delete the empty folder using organized path
    $folderPath = $this->getOrganizedStoragePath('sku', $sku->id);

    if (Storage::disk('public')->exists($folderPath)) {
      // Check if folder is empty before deleting
      $files = Storage::disk('public')->files($folderPath);
      if (empty($files)) {
        Storage::disk('public')->deleteDirectory($folderPath);

        // Optional: Also try to delete parent directories if they're empty
        $this->cleanupParentDirectories($folderPath);
      }
    }
  }
  private function cleanupParentDirectories($path)
  {
    $parentDir = dirname($path);

    // Stop if we've reached the base images directory
    if ($parentDir === 'images' || $parentDir === '.') {
      return;
    }

    if (Storage::disk('public')->exists($parentDir)) {
      $files = Storage::disk('public')->files($parentDir);
      $subdirs = Storage::disk('public')->directories($parentDir);

      // Delete directory if empty
      if (empty($files) && empty($subdirs)) {
        Storage::disk('public')->deleteDirectory($parentDir);
        // Recursively check the next parent
        $this->cleanupParentDirectories($parentDir);
      }
    }
  }
}