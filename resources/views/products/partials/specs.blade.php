<div class="mt-8">
    <h2 class="text-4xl">Specifications</h2>
    <div class="grid gap-0 col-gap-20 mx-auto xs:grid-cols-1 lg:grid-cols-2">
        <div class="flex border-b border-gray-500 text-base leading-20">
            <span class="flex-1">
                ID
            </span>
            <span class="flex-1 font-bold">
                {{ $product->id }}
            </span>
        </div>
        @if($product->ean)
            <div class="flex border-b border-gray-500 text-base leading-20">
                <span class="flex-1">
                    EAN
                </span>
                <span class="flex-1 font-bold">
                    {{ $product->ean }}
                </span>
            </div>
        @endif
        @foreach($properties as $property)
            @if(!isset($property->show_in_specifications) || $property->show_in_specifications)
                @if(isset($property->displayValue) && $property->displayValue)
                    <div class="flex border-b border-gray-500 text-base leading-20">
                        <span class="flex-1">
                            {{ $property->attribute->name }}
                        </span>
                        <span class="flex-1 font-bold">
                            {!! $property->displayValue !!}
                        </span>
                    </div>
                @endif
            @endif
        @endforeach
    </div>
</div>
