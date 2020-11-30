@extends('layout.default')

@section('content')
    <div class="mt-12">
        <div class="flex flex-col md:flex-row min-h-500">
            <div class="w-full md:w-1/2">
                <image-viewer
                    :images="{{ json_encode($product->media) }}"
                    :alt="'{{ $product->name }}'"></image-viewer>
            </div>

            <product-detail :product="{{ json_encode($product) }}"></product-detail>
        </div>

        <div class="lg:flex">
            <div class="flex-1">
                @include('products.partials.description')
                @include('products.partials.specs')
            </div>

            @include('products.partials.upsells')
        </div>

        @include('products.partials.similar')
        @include('products.partials.recent')

        @include('partials.footer')
    </div>
@endsection

@push('modals')
    <product-added-to-cart></product-added-to-cart>
@endpush

