<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Support\Str;

class CartService
{
  public function getCart($user = null, $guestToken = null)
  {
    if ($guestToken) {
      $cart = Cart::with(['items.sku.product.mainImage', 'items.sku.mainImage'])
        ->where('guest_token', $guestToken)
        ->first();

      if ($cart) {
        if ($cart->status === 'converted') {
          return $this->createNewCart($user, $guestToken);
        }

        return $cart;
      }
    }

    if ($user) {
      $cart = Cart::with(['items.sku.product.mainImage', 'items.sku.mainImage'])
        ->active()
        ->where('user_id', $user->id)
        ->first();

      if ($cart) {
        return $cart;
      }
    }

    return $this->createNewCart($user, $guestToken);
  }

  private function createNewCart($user = null, $guestToken = null)
  {
    if (!$guestToken) {
      $guestToken = Str::uuid();
    } else {
      $existingCart = Cart::where('guest_token', $guestToken)->first();
      if ($existingCart) {
        $guestToken = Str::uuid();
      }
    }

    $cart = Cart::create([
      'guest_token' => $guestToken,
      'status' => 'active',
      'user_id' => $user?->id
    ]);

    $cart->load(['items.sku.product.mainImage', 'items.sku.mainImage']);

    return $cart;
  }

  public function getCartTotal($cart)
  {
    if (!$cart) {
      return [
        'items' => [],
        'total' => 0,
        'count' => 0,
        'cart_token' => null
      ];
    }

    $cart->load('items.sku.product.mainImage', 'items.sku.mainImage');
    $total = 0;
    $count = 0;
    $items = [];

    foreach ($cart->items as $item) {
      $subtotal = $item->price * $item->quantity;
      $total += $subtotal;
      $count += $item->quantity;
      $imagePath = null;
      if ($item->sku->mainImage) {
        $imagePath = $item->sku->mainImage->url;
      } elseif ($item->sku->product->mainImage) {
        $imagePath = $item->sku->product->mainImage->url;
      }

      $items[] = [
        'id' => $item->id,
        'sku_id' => $item->sku_id,
        'sku_code' => $item->sku->code,
        'product_name' => $item->sku->product->name,
        'product_slug' => $item->sku->product->slug,
        'price' => $item->price,
        'quantity' => $item->quantity,
        'subtotal' => $subtotal,
        'inventory' => $item->sku->inventory,
        'attributes' => $item->sku->attributes,
        'image' => $imagePath
      ];
    }

    return [
      'items' => $items,
      'total' => $total,
      'count' => $count,
      'cart_token' => $cart->guest_token
    ];
  }

  public function addItem($cart, $skuId, $quantity)
  {
    $sku = Sku::findOrFail($skuId);

    if ($sku->inventory < $quantity) {
      throw new \Exception("Not enough inventory. Only {$sku->inventory} items available.");
    }
    $cart->load('items');
    $cartItem = $cart->items->where('sku_id', $skuId)->first();

    if ($cartItem) {
      $newQuantity = $cartItem->quantity + $quantity;
      if ($sku->inventory < $newQuantity) {
        throw new \Exception("Not enough inventory. Only {$sku->inventory} items available.");
      }
      $cartItem->update(['quantity' => $newQuantity]);
    } else {
      CartItem::create([
        'cart_id' => $cart->id,
        'sku_id' => $skuId,
        'quantity' => $quantity,
        'price' => $sku->price
      ]);

      $cart->load('items');
    }

    return $this->getCartTotal($cart);
  }

  public function updateQuantity($cart, $skuId, $quantity)
  {
    if ($quantity <= 0) {
      return $this->removeItem($cart, $skuId);
    }
    $sku = Sku::findOrFail($skuId);
    if ($sku->inventory < $quantity) {
      throw new \Exception("Not enough inventory. Only {$sku->inventory} items available.");
    }
    $cartItem = $cart->items()->where('sku_id', $skuId)->first();
    if ($cartItem) {
      $cartItem->update(['quantity' => $quantity]);
    }
    return $this->getCartTotal($cart);
  }

  public function removeItem($cart, $skuId)
  {
    $cart->items()->where('sku_id', $skuId)->delete();
    return $this->getCartTotal($cart);
  }

  public function clearCart($cart)
  {
    $cart->items()->delete();
    return $this->getCartTotal($cart);
  }

  public function convertGuestToUserCart($guestToken, $user)
  {
    $guestCart = Cart::active()->forGuest($guestToken)->first();

    if ($guestCart) {
      $userCart = $this->getCart($user);

      foreach ($guestCart->items as $guestItem) {
        $this->addItem($userCart, $guestItem->sku_id, $guestItem->quantity);
      }

      $guestCart->update(['status' => 'converted']);

      return $userCart;
    }

    return $this->getCart($user);
  }
}