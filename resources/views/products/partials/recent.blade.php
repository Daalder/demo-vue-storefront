@if(count($recentProducts))
    <div class="mt-8">
        <h2 class="text-4xl">Recent products</h2>
        <div class="mt-4 grid gap-5 mx-auto xs:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            @foreach($recentProducts as $recentProduct)
                <product-item :product="{{ json_encode($recentProduct) }}"></product-item>
            @endforeach
        </div>
    </div>
@endif
