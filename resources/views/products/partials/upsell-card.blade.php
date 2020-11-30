<?php $onSale = false; ?>
<div class="relative flex mb-8 flex-col rounded-lg shadow-lg overflow-hidden">
    <a href="/products/1" class="block absolute top-0 left-0 w-full h-full z-1"></a>
    <div class="flex flex-wrap">
        @foreach(array_filter([1, 1, rand(0, 1)]) as $i)
            <img class="h-12 mb-2"
                 src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80"
                 alt=""/>
            @if (!$loop->last)
                <span class="flex items-center font-bold text-4xl h-12 mx-2 mb-2">+</span>
            @endif
        @endforeach
    </div>
    <div class="flex-1 bg-white p-2 lg:p-4 flex flex-col justify-between">
        <div class="flex-1">
            <h3 class="mt-2 text-xl leading-6 font-semibold text-gray-900">
                {{ implode(' ', app(\Faker\Generator::class)->words(2)) }}
            </h3>
        </div>
        <div class="flex items-center">
            <strong class="text-lg text-daalder-dark-blue">
                €29,90
            </strong>
            @if($onSale)
                <strong class="ml-2 text-sm text-gray-400 line-through">
                    €39,90
                </strong>
            @endif
        </div>
    </div>
</div>
