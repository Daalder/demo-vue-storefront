@extends('layout.blank')

@section('content')
    <div class="mt-12">
        <steps :step="2"></steps>

        <div class="container m-auto mt-12">
            <h1 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">
                Payment
            </h1>
        </div>

        <div class="container m-auto flex flex-col sm:flex-row">
            <div class="flex-1 pr-8">
                <payment-methods :store-id="{{ $store->id }}"></payment-methods>
            </div>
            <div class="w-64">
                <p class="text-2xl">Cart</p>
                <cart-tabel :is-small="true" :with-images="false"></cart-tabel>
                @include('checkout/selling-points')
            </div>
        </div>

    </div>
@endsection
