<div class="mt-8 lg:w-64 lg:ml-8">
    <h2 class="text-4xl pb-4">Combo deals</h2>
    <div class="lg:flex lg:flex-col">
        @for($i=0;$i<3;$i++)
            @include('products.partials.upsell-card')
        @endfor
    </div>
</div>
