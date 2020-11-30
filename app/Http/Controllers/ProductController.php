<?php

namespace App\Http\Controllers;

use App\Repositories\ProductRepository;
use App\Services\Product\PropertiesFormatter;
use App\Services\Product\RecentProducts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;

class ProductController extends Controller
{
    /**
     * @param  int  $categoryId
     * @return \Illuminate\View\View
     */
    public function index($categoryId = 4786)
    {
        $data = [
            'api' => "/v2/categories/$categoryId/products"
        ];
        $data['api'] .= '?includes=media.versions,variations.values';

        return view('products.index', $data);
    }

    /**
     * @param $url
     * @param  ProductRepository  $productRepository
     * @param  PropertiesFormatter  $propertiesFormatter
     * @return RedirectResponse|\Illuminate\View\View
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function show(
        $url,
        ProductRepository $productRepository,
        PropertiesFormatter $propertiesFormatter,
        RecentProducts $recentProducts
    ) {
        $product = $productRepository->getProductByUrl($url,
            'variations.values.product.stock,media.versions,brand.media.versions,stock,properties.productattribute,shipping_time,cross_sells.cross_sell_product.media.versions');

        // handle redirects
        if ($product instanceof RedirectResponse) {
            return $product;
        }

        // product not found
        if (!$product) {
            abort(404);
        }

        $product->cross_sells = array_values(array_filter($product->cross_sells, function ($crossSell) {
            return $crossSell->cross_sell_product !== null;
        }));

        return view('products.show', [
            'product' => $product,
            'properties' => $propertiesFormatter->get($product),
            'recentProducts' => $recentProducts->get($product),
        ]);
    }
}
