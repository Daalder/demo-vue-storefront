@extends('layout.blank')

@section('content')
    <div class="mt-12">
        <steps :step="3"></steps>

        <div class="container m-auto mt-12">
            <h1 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">
                Confirm
            </h1>
        </div>

        <div class="container m-auto flex flex-col sm:flex-row">
            <div class="flex-1 pr-8">
                <confirm></confirm>
            </div>
            <div class="w-64">
                @include('checkout/selling-points')
            </div>
        </div>

    </div>
@endsection


<script>
    import Confirm from "../../js/components/Checkout/Confirm";

    export default {
        components: {Confirm}
    }
</script>
