<?php

namespace App\Services\Api;

use App\Services\Auth\CustomerAuthenticator;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApiService
{
    /**
     * @var Client
     */
    public $client;

    /**
     * @var array
     */
    protected $headers = ['Accept' => 'application/json'];

    protected $baseUrl;

    /**
     * @var Response
     */
    protected $errorResponse;

    /**
     * @var RequestInterface
     */
    protected $errorRequest;

    public function __construct()
    {
        $this->baseUrl = config('daalder.api_url');
        $this->baseUrl = rtrim($this->baseUrl, '/').'/';

        $this->client = new Client([
            'headers' => [],
            'verify' => app()->environment() === 'production'
        ]);
    }

    /**
     * @param  string  $url
     * @param  array  $options
     * @return ApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function get($url = null, $options = [])
    {
        return $this->request('GET', $url, $options);
    }

    /**
     * @param  string  $url
     * @param  array  $options
     * @return ApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function post($url = null, $options = [])
    {
        return $this->request('POST', $url, $options);
    }

    /**
     * @param  string  $url
     * @param  array  $options
     * @return ApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function put($url = null, $options = [])
    {
        return $this->request('PUT', $url, $options);
    }

    /**
     * @param  string  $url
     * @param  array  $options
     * @return ApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function delete($url = null, $options = [])
    {
        return $this->request('DELETE', $url, $options);
    }

    /**
     * @param  string  $method
     * @param  string  $url
     * @param  array  $options
     * @param  bool  $isFirstTry
     * @return ApiResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function request($method = 'GET', $url = null, $options = [], $isFirstTry = true)
    {
        try {
            $response = $this->client->request(
                strtoupper($method),
                $this->makeUrl($url),
                $this->addOptions($options)
            );

            return new ApiResponse($response);
        } catch (ClientException $exception) {
            if ($exception->getCode() === 422) {
                $response = json_decode($exception->getResponse()->getBody(), true);
                throw ValidationException::withMessages($response['errors']);
            } elseif ($isFirstTry) {
                if ($exception->getCode() === 403) {
                    app(CustomerAuthenticator::class)->getAnonymousCustomerToken();

                    return $this->request($method, $url, $options, false);
                } elseif ($exception->getCode() === 401 || Str::contains($url, 'customers/tokens')) {
                    $this->getApiAccessToken(true);

                    return $this->request($method, $url, $options, false);
                }
            }

            throw $exception;
        } catch (ServerException $exception) {
            throw $exception;
        }
    }

    /**
     * @param $url
     * @return string
     */
    protected function makeUrl($url)
    {
        $url = str_replace('//', '/', $url);
        $base = $this->baseUrl.'v2/';

        return Str::startsWith($url, $base) ? $url : $base.$url;
    }

    public function getApiAccessToken($refresh = false)
    {
        if (cache()->has('daalder.api_access_token') && !$refresh) {
            return cache()->get('daalder.api_access_token');
        }

        $response = $this->client->request(
            'POST',
            $this->baseUrl.'oauth/token',
            [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('daalder.api_client_id'),
                    'client_secret' => config('daalder.api_client_secret'),
                    'scope' => '*'
                ]
            ]
        );

        $body = json_decode((string) $response->getBody());

        cache()->set('daalder.api_access_token', $body->access_token, now()->addYear());

        return $body->access_token;
    }

    /**
     * @param  array  $credentials
     * @param  bool  $includesCustomerToken
     * @param  array  $headers
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCustomerInfo(array $credentials = [], $includesCustomerToken = true, $headers = [])
    {
        try {
            $this->getHeaders($headers, $includesCustomerToken);
            $url = $this->makeUrl('customers/tokens');
            $response = $this->client->request('POST', $url, [
                'headers' => $this->headers,
                'form_params' => Arr::only($credentials, ['email', 'password'])
            ]);
        } catch (ClientException $exception) {
            return null;
        }

        $body = json_decode((string) $response->getBody());

        return $body;
    }

    protected function addOptions(array $options = [])
    {
        $headers = isset($options['headers']) && is_array($options['headers']) ? $options['headers'] : [];
        $options['headers'] = $this->getHeaders($headers);

        return $options;
    }

    /**
     * @param  array  $headers
     * @param  bool  $includesCustomerToken
     * @return array
     */
    protected function getHeaders(array $headers = [], $includesCustomerToken = true)
    {
        $apiAccessToken = $this->getApiAccessToken();
        $this->headers['Authorization'] = "Bearer $apiAccessToken";

        $storeId = config('daalder.store_id');
        if ($storeId) {
            $this->headers['X-Daalder-Store'] = $storeId;
        }

        if ($includesCustomerToken) {
            $this->headers['X-Daalder-Customer-Token'] = app(CustomerAuthenticator::class)->getCustomerToken();
        }

        return array_merge($this->headers, $headers);
    }
}
