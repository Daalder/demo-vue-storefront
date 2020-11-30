<?php

namespace App\Repositories;

use App\Includes\Redirector;
use App\Models\Product\Product;
use App\Services\OptionService;

class ProductRepository extends Repository
{
    /**
     * @param $productId
     * @return \stdClass
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProduct($productId)
    {
        $response = $this->apiService->get('products/'.$productId);

        return $response->getOutput();
    }

    /**
     * @param $url
     * @param $includes
     * @return \Illuminate\Http\RedirectResponse|\stdClass
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getProductByUrl($url, $includes)
    {
        try {
            $store_id = config('daalder.store_id');
            $response = $this->apiService->get('stores/'.$store_id.'/urls/'.$url.'?includes='.$includes);
            $response = $response->getOutput();
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            return null;
        }

        if ($response->data->type == 'redirect' && $response->data->redirect_to) {
            return redirect()->to($response->data->redirect_to);
        }

        return $response->data->product;
    }

    public function getProducts($options)
    {
        $response = $this->apiService->get('products', $options);

        return $response->getOutput()->data->products;
    }
}
