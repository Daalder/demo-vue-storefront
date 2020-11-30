<?php

namespace App\Repositories;

use App\Models\Basket;
use App\Models\Order\Order;

class BasketRepository extends Repository
{
    /**
     * @return Basket
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBasket($addedIncludes = '')
    {
        $response = $this->apiService->get('basket?includes=entries.product.media.versions,entries.product.properties,entries.product.productattributeset.producttemplate,customer.primaryInvoiceAddress,customer.primaryDeliveryAddress,shipping_method,pickup_point,payment_method'.$addedIncludes);

        return $this->fillBasket($response);
    }

    /**
     * @param $input
     * @return $this|\App\Models\Basket
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function addProductInBasket($input)
    {
        $productArray = $input['product'];

        if (!$productArray || !is_array($productArray)) {
            return null;
        }

        $fixed_amount = isset($input['fixed_amount']) ? true : false;

        $variables = [];
        if (isset($input['options'])) {
            foreach ($input['options'] as $variable_id => $option_id) {

                if (is_array($option_id)) {
                    // array of checkboxes e.g: [variable_id => "1"]
                    foreach ($option_id as $optionId => $value) {
                        array_push($variables, [
                            'option_id' => $variable_id,
                            'option_value' => $optionId
                        ]);
                    }
                } else {
                    array_push($variables, [
                        'option_id' => $variable_id,
                        'option_value' => (int) $option_id
                    ]);
                }
            }
        } else {
            // Quicksell from product overview
            // If there's no options passed and there is one entry in $productArray
            if (count($productArray) === 1) {
                // Get the product from API
                $product = $this->apiService->get('products/'.key($productArray).'?includes=variables.options')->getOutput();

                // Filter out the required variables
                $requiredVariables = collect($product->variables)->filter(function ($variable) {
                    return $variable->required;
                });

                // Set the default options for the required variables
                foreach ($requiredVariables as $requiredVariable) {
                    array_push($variables, [
                        'option_id' => $requiredVariable->id,
                        'option_value' => $requiredVariable->options[0]->id
                    ]);
                }
            }
        }

        $entries = [];
        foreach ($productArray as $productId => $amount) {
            $entries[] = [
                'product_id' => $productId,
                'amount' => $amount,
                'options' => $variables
            ];
        }

        $body = [
            'json' => [
                'entries' => $entries
            ]
        ];

//        @TODO /basket/entries don't accept $fixed_amount?
        $response = $this->apiService->post('basket/entries', $body);

        return $this->fillBasket($response);
    }

    public function updateProduct($entryId, $amount = 1)
    {
        $data = [
            'form_params' => [
                'amount' => $amount
            ]
        ];

        $response = $this->apiService->put('basket/entries/'.$entryId, $data);

        return $this->fillBasket($response);
    }


    public function removeProduct($entryId)
    {
        $response = $this->apiService->delete('basket/entries/'.$entryId);

        return $this->fillBasket($response);
    }

    public function checkout($params)
    {
        $data = [
            'form_params' => $params
        ];

        $response = $this->apiService->post('basket/checkout', $data);

        $instance = new Order();

        return $instance->forceFill(get_object_vars($response->getOutput()->data));
    }

    public function convertToOffer($params)
    {
        $params['store_id'] = config('daalder.store_id');
        $params['referer'] = config('app.url');

        $data = [
            'form_params' => $params
        ];

        $response = $this->apiService->post('basket/request-offer', $data);

        return $response->getOutput();
    }

    public function clear()
    {
        $response = $this->apiService->delete('basket/entries');

        return $this->fillBasket($response);
    }

    public function addDiscount($input)
    {
        $params['coupon_code'] = $input['coupon_code'];

        $data = [
            'form_params' => $params
        ];

        $response = $this->apiService->post('basket/coupon', $data);

        return $this->fillBasket($response);
    }

    private function fillBasket($response)
    {
        return (new Basket)->forceFill(get_object_vars($response->getOutput()));
    }

}
