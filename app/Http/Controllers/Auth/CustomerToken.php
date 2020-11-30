<?php

namespace App\Http\Controllers\Auth;

use App\Services\Auth\CustomerAuthenticator;
use Illuminate\Routing\Controller as BaseController;

class CustomerToken extends BaseController
{
    public function index()
    {
        $customerAuthenticator = app(CustomerAuthenticator::class);
        $token = $customerAuthenticator->getAnonymousCustomerToken();

        $response = [
            'data' => [
                'customer_token' => $token,
                'is_guest' => (int) $customerAuthenticator->isGuest(),
                'is_authenticated' => (int) $customerAuthenticator->isAuthenticated()
            ]
        ];

        return response()->json($response);
    }
}
