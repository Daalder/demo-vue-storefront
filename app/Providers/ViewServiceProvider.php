<?php

namespace App\Providers;

use App\Services\Api\ApiService;
use App\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(ApiService $apiService)
    {
        View::composer('*', function (\Illuminate\View\View $view) use ($apiService) {
            $response = $apiService->get('stores/'.config('daalder.store_id'));
            $view->with('store', json_decode($response->body()));

            $user = auth()->user();
            if (!$user) {
                $user = (new User())->forceFill([
                    "id" => 14658,
                    "firstname" => "Daniël",
                    "lastname" => "Ducro",
                    "full_name" => "Daniël Ducro",
                    "short_full_name" => "D. Ducro",
                    "contact_type" => "private",
                    "email" => "info@danield.nl",
                    "mobile" => "0639760055",
                    "telephone" => "0103400308",
                    "sys_telephone" => "+31103400308",
                    "lang" => "nl_nl",
                    "sys_mobile" => "+31639760055",
                    "birthday" => null,
                    "state_id" => 2,
                    "updated_at" => "2020-02-24 11:25:30",
                    "created_at" => "2015-04-02 04:46:22",
                    "primaryDeliveryAddress" => (object) [
                        "id" => 46199,
                        "lat" => 0,
                        "lng" => 0,
                        "address_hash" => "8e5a2b92870eed4842f2ee12bf18fd58",
                        "address_line_1" => "Maashaven 2",
                        "address_line_2" => null,
                        "address_line_3" => null,
                        "street" => "Maashaven",
                        "street_number" => "2",
                        "street_number_addition" => "",
                        "postalcode" => "3081AE",
                        "city" => "Rotterdam",
                        "region" => null,
                        "country_code" => "NL",
                        "country_name" => "Netherlands",
                        "created_at" => "2020-02-10 19:00:34",
                        "updated_at" => "2020-02-10 19:00:34",
                        "formatted_address" => "Maashaven 2, 3081AE Rotterdam "
                    ]
                ]);
            }
            $view->with('user', $user);
        });
    }
}
