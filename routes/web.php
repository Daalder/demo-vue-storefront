<?php

use App\Http\Controllers\Auth\CustomerToken;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('customer-token', CustomerToken::class.'@index');

Route::get('/', ProductController::class.'@index');
Route::get('products/{id?}', ProductController::class.'@index');

Route::view('cart', 'checkout.cart');

Route::view('account', 'account.account')
    ->middleware('auth');

Route::view('forgot-password', 'account.forgot-password')
    ->middleware('guest');

// todo: validate reset-password token
Route::view('reset-password', 'account.reset-password');

Route::get('login', LoginController::class.'@showLoginForm')->name('login');
Route::post('login', LoginController::class.'@login');

Route::get('logout', LoginController::class.'@logout')->name('logout');

Route::get('register', RegisterController::class.'@showRegistrationForm')->name('register');
Route::post('register', RegisterController::class.'@register');

// todo: validate basket
Route::view('checkout', 'checkout.start')
    ->middleware('guest:web,checkout/shipping');
Route::view('checkout/personal-data', 'checkout.personal-data');
Route::view('checkout/shipping', 'checkout.shipping');
Route::view('checkout/payment', 'checkout.payment');
Route::view('checkout/confirm', 'checkout.confirm');
Route::view('checkout/success', 'checkout.success');

// If no routes match, check if it's a product
Route::get('{url}', ProductController::class.'@show');
