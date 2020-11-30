<?php

namespace App\Repositories;

use App\Services\Api\ApiService;

abstract class Repository
{
    /** @var ApiService */
    protected $apiService;

    /**
     * Repository constructor.
     *
     * @param  ApiService  $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
}
