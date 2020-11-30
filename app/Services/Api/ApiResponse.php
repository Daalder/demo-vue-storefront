<?php

namespace App\Services\Api;

use Psr\Http\Message\ResponseInterface;

class ApiResponse
{
    /**
     * @var ResponseInterface
     */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function body()
    {
        return $this->response->getBody();
    }

    public function getOutput()
    {
        $output = json_decode($this->response->getBody());

        return $output;
    }

}
