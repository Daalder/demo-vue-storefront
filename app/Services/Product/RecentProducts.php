<?php

namespace App\Services\Product;

use App\Repositories\ProductRepository;

class RecentProducts
{
    public function get($product)
    {
        $recentProductIds = $this->push($product)
            ->filter(function ($id) use ($product) {
                return $id != $product->id;
            });

        if ($recentProductIds->count()) {
            return app(ProductRepository::class)->getProducts([
                'query' => [
                    'filter[id]' => $recentProductIds->implode('|'),
                    'includes' => 'media.versions,variations.values'
                ]
            ]);
        }

        return collect();
    }

    public function push($product)
    {
        $recentProductIds = collect(request()->session()->get('recent_products', []))
            ->prepend($product->id)
            ->unique();

        if ($recentProductIds->count() > 6) {
            $recentProductIds = $recentProductIds->splice(0, 6);
        }

        request()->session()->put('recent_products', $recentProductIds->values());

        return $recentProductIds;
    }

}
