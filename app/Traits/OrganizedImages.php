<?php

namespace App\Traits;


trait OrganizedImages
{
    private function getOrganizedStoragePath($imageableType, $imageableId)
    {
        $basePath = 'images';
        switch ($imageableType) {
            case 'Category':
                $category = \App\Models\Category::find($imageableId);
                if ($category) {
                    return $basePath . '/categories/' . $category->slug;
                }
                break;

            case 'Product':
                $product = \App\Models\Product::with('category')->find($imageableId);

                if ($product && $product->category) {
                    return $basePath . '/categories/' . $product->category->slug . '/products/' . $product->slug;
                }
                break;

            case 'sku':
                $sku = \App\Models\Sku::with(['product.category'])->find($imageableId);
                if ($sku && $sku->product && $sku->product->category) {
                    return $basePath . '/categories/' . $sku->product->category->slug . '/products/' . $sku->product->slug . '/skus/' . $sku->code;
                }
                break;

            case 'Order':
                $order = \App\Models\Order::find($imageableId);
                if ($order) {
                    return $basePath . '/orders/' . $order->order_code;
                }
                break;

            case 'User':
                $user = \App\Models\User::find($imageableId);
                if ($user) {
                    return $basePath . '/users/' . $user->id;
                }
                break;

            default:
                return $basePath . '/misc/' . $imageableType;
        }

        return $basePath . '/uncategorized';
    }
}