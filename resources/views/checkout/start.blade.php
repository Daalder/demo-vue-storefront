@extends('layout.blank')

@section('content')
    <div class="mt-12">
        <steps></steps>

        <div class="container m-auto mt-12">
            <h1 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">
                Checkout
            </h1>
        </div>
        <div class="container m-auto flex">
            <div class="flex-1 pr-8">
                <p class="text-2xl">Order without creating an account</p>
                <p class="mt-4">An account is not mandatory, but it is that easy of course. Continue ordering immediately or create an account after your order.</p>
                <btn href="/checkout/personal-data" class="mt-8">
                    Proceed to checkout
                </btn>
            </div>
            <div class="flex-1">
                <p class="text-2xl">Do you already have an account?</p>
                <p class="mt-4">Do you already have an account with us? Log in here with your email address and password.</p>

                <login redirect-url="/checkout/shipping"></login>
            </div>
        </div>
    </div>
@endsection
