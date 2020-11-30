<?php

namespace App\Services\Auth;

use App\Repositories\CustomerRepository;
use App\Services\Api\ApiService;
use App\User;
use Illuminate\Support\Arr;
use App\Services\Cookie;

class CustomerAuthenticator
{
    const ANONYMOUS = '1';
    const REGISTERED = '2';
    const VERIFIED = '3';

    protected $token = null;
    protected $customerId = null;
    protected $isAnonymous = 1;
    protected $isAuthenticated = 0;
    protected $customer = null;

    /** @var CustomerRepository */
    protected $customerRepository;

    /** @var \App\Services\Cookie */
    protected $cookie;

    public function __construct(CustomerRepository $customerRepository, Cookie $cookie)
    {
        $this->customerRepository = $customerRepository;
        $this->cookie = $cookie;

        $this->read();
    }

    public function isAuthenticated()
    {
        return $this->isAuthenticated;
    }

    public function isGuest()
    {
        return $this->isAnonymous;
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function hasToken()
    {
        return (bool) $this->token;
    }

    /**
     * @return int|null
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return User|null
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param  array  $credentials
     * @param  bool  $refresh
     * @return bool|string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerToken(array $credentials = [], $refresh = false)
    {
        if ($this->token && !$refresh) {
            return $this->token;
        }

        $credentials = Arr::only($credentials, ['email', 'password']);

        $customerInfo = app(ApiService::class)->getCustomerInfo($credentials, !empty($credentials));

        if (!$customerInfo || !$customerInfo->token) {
            return false;
        }

        $this->token = $customerInfo->token;
        $this->customerId = $customerInfo->customer_id;
        $this->customer = null;
        $this->isAnonymous = 1;
        $this->isAuthenticated = (int) ($this->hasToken() && is_numeric($this->customerId));

        if (count($credentials) == 2) {
            $customer = $this->customerRepository->getCurrentUser(true);
            if ($customer) {
                $this->customer = $customer;
                $this->isAnonymous = (int) ($this->customer->state_id == self::ANONYMOUS);
            }
        }

        $this->store();

        return $customerInfo->token;
    }

    public function getAnonymousCustomerToken()
    {
        $this->token = null;
        $this->customerId = null;
        $this->isAnonymous = 1;
        $this->isAuthenticated = 0;
        $this->customer = null;
        $this->store();

        return $this->getCustomerToken([], true);
    }

    protected function read()
    {
        $this->token = $this->cookie->get('customer_token');
        $this->isAnonymous = $this->cookie->get('is_anonymous', 1);
        $this->isAuthenticated = $this->cookie->get('is_authenticated', 0);

        if (request()->hasSession()) {
            $customer = json_decode(request()->session()->get('customer'), true);
            if ($customer) {
                $this->customer = (new User)->forceFill($customer);
            }
        }
    }

    protected function store()
    {
        $this->cookie->put('customer_token', $this->token);
        $this->cookie->put('is_anonymous', (int) $this->isAnonymous);
        $this->cookie->put('is_authenticated', (int) $this->isAuthenticated);

        if (request()->hasSession()) {
            request()->session()->put('customer', json_encode($this->customer));
        }
    }
}
