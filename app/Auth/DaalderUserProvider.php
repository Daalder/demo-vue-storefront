<?php

namespace App\Auth;

use App\Services\Api\ApiService;
use App\Services\Auth\CustomerAuthenticator;
use Illuminate\Contracts\Auth\Authenticatable;

class DaalderUserProvider implements \Illuminate\Contracts\Auth\UserProvider
{
    /**
     * @var \App\Services\Api\ApiService
     */
    protected $apiService;
    /**
     * @var \App\Services\Auth\CustomerAuthenticator
     */
    protected $customerAuthenticator;

    public function __construct(CustomerAuthenticator $customerAuthenticator, ApiService $apiService)
    {
        $this->apiService = $apiService;
        $this->customerAuthenticator = $customerAuthenticator;
    }

    public function retrieveById($id)
    {
        $user = $this->customerAuthenticator->getCustomer();
        if ($user && $user->id == $id) {
            return $user;
        }
    }

    public function retrieveByToken($id, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function retrieveByCredentials(array $credentials)
    {
        $this->customerAuthenticator->getCustomerToken($credentials, true);

        return $this->customerAuthenticator->getCustomer();
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $user->email === $credentials['email'];
    }
}
