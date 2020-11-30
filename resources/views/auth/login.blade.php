@extends('layout.default')

@section('content')
    <div class="mt-12">
        <h1 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">
            Login
        </h1>
        <p class="mt-4">Do you already have an account with us? Log in here with your email address and password.</p>

        <login></login>

        <p class="mt-8">
            <a href="/register">Register here</a>
        </p>
    </div>
@endsection


