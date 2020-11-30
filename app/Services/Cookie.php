<?php

namespace App\Services;

use App\Http\Middleware\EncryptCookies;

class Cookie
{
    /** @var \App\Http\Middleware\EncryptCookies */
    protected $encryptCookies;

    public function __construct(EncryptCookies $encryptCookies)
    {

        $this->encryptCookies = $encryptCookies;
    }

    public function queue($key, $value)
    {
        $httpOnly = $this->encryptCookies->isDisabled($key) === false;

        \Illuminate\Support\Facades\Cookie::queue(
            $key, $value,
            config('session.lifetime'),
            '/', config('app.host'),
            true, $httpOnly);
    }

    public function put($key, $value)
    {
        $this->queue($key, $value);
    }

    public function get($key, $default = null)
    {
        return request()->cookie($key, $default);
    }
}
