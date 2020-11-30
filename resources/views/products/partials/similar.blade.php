@if(count($product->cross_sells))
    <div class="mt-8">
        <h2 class="text-4xl">Similar products</h2>
        <div class="mt-4 grid gap-5 mx-auto xs:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($product->cross_sells as $cross)
                <product-item :product="{{ json_encode($cross->cross_sell_product) }}"></product-item>
            @endforeach
        </div>
    </div>
@endif
