<?php

namespace App\Http\Controllers;

use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImgController extends Controller
{
    /**
     * Store a newly created image
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'imageable_type' => 'required|string',
            'imageable_id' => 'required|integer',
            'type' => 'required|in:main,gallery,variant,receipt',
            'alt_text' => 'nullable|string|max:255',
        ]);

        try {
            // If setting as main, demote existing main images
            if ($request->type === 'main') {
                // Use the same imageable_type format for consistency
                Img::where('imageable_type', $this->getFullClassName($request->imageable_type))
                    ->where('imageable_id', $request->imageable_id)
                    ->where('type', 'main')
                    ->update(['type' => 'gallery']);
            }

            $image = $request->file('image');
            $originalName = $image->getClientOriginalName();
            $extension = $image->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;

            // Store in a simpler path structure
            $path = 'images';

            // Store the image - make sure it's using the 'public' disk
            $fullPath = $image->storeAs($path, $filename, 'public');

            // Create image record with full class name
            $img = Img::create([
                'filename' => $filename,
                'original_name' => $originalName,
                'path' => $fullPath,
                'mime_type' => $image->getMimeType(),
                'file_size' => $image->getSize(),
                'imageable_type' => $this->getFullClassName($request->imageable_type), // FIX: Use full class name
                'imageable_id' => $request->imageable_id,
                'type' => $request->type,
                'alt_text' => $request->alt_text,
                'order' => $this->getNextOrder($request->imageable_type, $request->imageable_id, $request->type),
            ]);

            return redirect()->back()->with('success', 'Image uploaded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to upload image: ' . $e->getMessage());
        }
    }

    /**
     * Update image details
     */
    public function update(Request $request, Img $img)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:255',
        ]);

        $img->update($request->only(['alt_text', 'caption']));

        return redirect()->back()->with('success', 'Image updated successfully.');
    }

    /**
     * Remove the specified image
     */
    public function destroy(Img $img)
    {
        try {
            $img->delete();
            return redirect()->back()->with('success', 'Image deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete image: ' . $e->getMessage());
        }
    }

    /**
     * Set image as main (and demote previous main)
     */
    public function setAsMain(Img $img)
    {
        try {
            // Demote previous main image
            Img::where('imageable_type', $img->imageable_type)
                ->where('imageable_id', $img->imageable_id)
                ->where('type', 'main')
                ->update(['type' => 'gallery']);

            // Promote this image to main
            $img->update(['type' => 'main']);

            return redirect()->back()->with('success', 'Image set as main successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to set image as main: ' . $e->getMessage());
        }
    }

    /**
     * Get the next order number for the image
     */
    private function getNextOrder($imageableType, $imageableId, $type)
    {
        $lastOrder = Img::where('imageable_type', $imageableType)
            ->where('imageable_id', $imageableId)
            ->where('type', $type)
            ->max('order');

        return ($lastOrder ?? 0) + 1;
    }
    private function getFullClassName($shortClassName)
    {
        $classMap = [
            'Category' => 'App\Models\Category',
            'Product' => 'App\Models\Product',
            'Sku' => 'App\Models\Sku',
            'Order' => 'App\Models\Order',
            // Add other models as needed
        ];

        return $classMap[$shortClassName] ?? $shortClassName;
    }
}