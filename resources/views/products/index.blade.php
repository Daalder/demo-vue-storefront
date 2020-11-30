@extends('layout.default')

@section('content')
    <div class="mt-5">
        <overview api_endpoint="{{ $api }}" :second_image="true"></overview>
    </div>
    {{--
<div class="flex justify-between">
    <nav class="border-b border-gray-200 px-4 flex items-center justify-between sm:px-0">
        <span
            class="-mt-px pb-3 pr-1 border-b-2 border-transparent inline-flex text-sm leading-5 font-medium text-gray-500">
            Items per page
        </span>
        <div class="flex">
            @foreach([20,30,50] as $items)
                <a href="#"
                   class="-mt-px border-b-2 pb-3 px-4 inline-flex items-center text-sm leading-5 font-medium hover:text-gray-700 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150
            @if($items == 30) border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700 @else border-transparent text-gray-500 focus:text-gray-700 focus:border-gray-400 @endif">
                    {{ $items }}
                </a>
            @endforeach
        </div>
    </nav>

    <dropdown>
        <template #button>
            <button type="button"
                    class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                Sort
                <svg class="-mr-1 ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </button>
        </template>

        <div class="rounded-md bg-white shadow-xs">
            <div class="py-1">
                <a href="#"
                   class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                    Price ascending
                </a>
                <a href="#"
                   class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                    Price descending
                </a>
                <a href="#"
                   class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                    Most populair
                </a>
            </div>
        </div>
    </dropdown>
</div>
</div>
<div class="mt-4 grid gap-5 mx-auto xs:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
@for($i=0;$i<20;$i++)
    @include('products.partials.product-card')
@endfor
</div>

<nav class="border-t border-gray-200 px-4 flex items-center justify-between sm:px-0 mt-5">
<div class="w-0 flex-1 flex">
    <a href="#"
       class="-mt-px border-t-2 border-transparent pt-4 pr-1 inline-flex items-center text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-400 transition ease-in-out duration-150">
        <svg class="mr-3 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                  clip-rule="evenodd"/>
        </svg>
        Previous
    </a>
</div>
<div class="hidden md:flex">
    @foreach([1,2,3] as $page)
        <a href="#"
           class="-mt-px border-t-2 pt-4 px-4 inline-flex items-center text-sm leading-5 font-medium hover:text-gray-700 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150
            @if($page == 2) border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700 @else border-transparent text-gray-500 focus:text-gray-700 focus:border-gray-400 @endif">
            {{ $page }}
        </a>
    @endforeach
    <span
        class="-mt-px border-t-2 border-transparent pt-4 px-4 inline-flex items-center text-sm leading-5 font-medium text-gray-500">
...
</span>
    @foreach([8,9,10] as $page)
        <a href="#"
           class="-mt-px border-t-2 border-transparent pt-4 px-4 inline-flex items-center text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-400 transition ease-in-out duration-150">
            {{ $page }}
        </a>
    @endforeach
</div>
<div class="w-0 flex-1 flex justify-end">
    <a href="#"
       class="-mt-px border-t-2 border-transparent pt-4 pl-1 inline-flex items-center text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-400 transition ease-in-out duration-150">
        Next
        <svg class="ml-3 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                  d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                  clip-rule="evenodd"/>
        </svg>
    </a>
</div>
</nav>
--}}
    @include('partials.footer')
@endsection

@section('sidebar')

    <div class="flex flex-col">
        <div class="flex-1 h-0 pb-4 overflow-y-auto">
            {{--        @include('partials.menu')--}}
            @include('products.partials.filter')
            {{--        @include('partials.side-content')--}}
        </div>
    </div>

@endsection
