<?php $onSale = false; ?>
<div class="relative flex flex-col rounded-lg shadow-lg overflow-hidden">
    <a href="/products/1" class="block absolute top-0 left-0 w-full h-full z-1"></a>
    <div class="relative flex-shrink-0">
        @if(rand(0, 2) == 0)
            <span class="bedge new">New</span>
        @elseif($onSale = rand(0, 2) == 0)
            <span class="bedge sale">Sale</span>
        @endif
        <img class="h-48 w-full object-cover"
             src="https://images.unsplash.com/photo-1496128858413-b36217c2ce36?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1679&q=80"
             alt=""/>
    </div>
    <div class="flex-1 bg-white p-2 lg:p-4 flex flex-col justify-between">
        <div class="flex-1">
            <h3 class="mt-2 text-xl leading-6 font-semibold text-gray-900">
                {{ implode(' ', app(\Faker\Generator::class)->words(2)) }}
            </h3>
            @if($sizes = rand(0, 6))
                <p class="mt-3 text-base leading-3 text-gray-500">
                    {{ $sizes }} sizes
                </p>
            @endif
        </div>
        <div class="flex items-center mt-1">
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
