<?php

namespace App\Repositories;


use App\Services\Auth\CustomerAuthenticator;
use App\User;
use GuzzleHttp\Exception\ClientException;

class CustomerRepository extends Repository
{
    /**
     * @param  bool  $force
     * @return object
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCurrentUser($force = false)
    {
        $customerAuthenticator = app(CustomerAuthenticator::class);

        if (!$customerAuthenticator->isAuthenticated() && !$force) {
            return null;
        }

        try {
            $response = $this->apiService->get('customers/current?includes=company,primaryDeliveryAddress,primaryInvoiceAddress');
            $customer = $response->getOutput()->data;
        } catch (ClientException $exception) {
            $customerAuthenticator->getAnonymousCustomerToken();

            return false;
        }

        $user = app(User::class);

        return $user->forceFill((array) $customer);
    }

    public function register($params)
    {
        $options = [
            'form_params' => $params
        ];

        $response = $this->apiService->post('customers', $options);
        $customer = optional($response->getOutput())->data;

        $user = app(User::class);

        return $user->forceFill((array) $customer);
    }

    public function requestPasswordReset($params)
    {
        $options = [
            'form_params' => $params
        ];

        $response = $this->apiService->post('password-reset-token', $options);

        return $response->getOutput();
    }

    public function getTokenResourceByPasswordResetToken($token)
    {
        $response = $this->apiService->get("password-reset-token/$token");

        return $response->getOutput();
    }

    public function edit($id, $params)
    {
        $options = [
            'form_params' => $params
        ];

        $response = $this->apiService->put("customers/$id", $options);

        return $response->getOutput()->data;
    }
}
