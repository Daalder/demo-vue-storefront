<template>
    <div class="mb-6" :class="{'loading': loading}">
        <div class="flex justify-between mb-8">
            <div class="">
                <h1 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">Winkelwagen</h1>
            </div>
            <btn @click="proceed" :disabled="loading || !this.cart.entries.length"
                 class="hidden md:inline"
                 href="/checkout">
                Proceed to checkout
            </btn>
        </div>

        <form method="POST" action="/">
            <div v-if="loading || (this.cart && this.cart.entries.length > 0)"
                 class="hidden lg:flex">
                <div class="flex-1">Good choice!</div>
                <div class="w-full md:w-2/3 lg:w-1/2 2xl:w-1/3 flex">
                    <div class="flex-1 hidden w-1/6 md:block text-left px-2">Price p.p.</div>
                    <div class="flex-1 hidden w-1/6 md:block text-left px-2">Amount</div>
                    <div class="flex-1 hidden ml-auto md:block text-right pl-2">Subtotal</div>
                </div>
            </div>
            <div v-if="!loading && !this.cart.entries.length"
                 class="">
                <p>No products in shopping cart.</p>
            </div>
            <template v-if="loading">
                <div v-for="x in [1,2]" class="loading-pulse flex justify-between">
                    <div class="h-20 mt-4 w-24 mr-4"></div>
                    <div class="h-20 mt-4 flex-1"></div>
                </div>
            </template>
            <div v-else-if="cart && cart.entries.length">
                <cart-item v-for="entry in cart.entries"
                           :key="entry.id"
                           :entry="entry"
                           :ref="'basket-item-' + entry.id"
                           class=""
                           @updated="updated"
                           @remove="removeItem"
                           v-cloak
                ></cart-item>
            </div>
            <!--
                    <template v-slot:delivery>
                        <div class="whitespace-no-wrap flex"
                             v-if="entry.product.shipping_time && entry.product.shipping_time.name">
                            <div class="product-icon-wrapper flex-shrink-0">
                                <i class="delivery-icon material-icons">check</i>
                            </div>
                            <div class="inline whitespace-normal">
                                                <span class="margin-left-1">
                                                    Levertijd: {{ entry.product.shipping_time.name }}
                                                </span>
                                <a href="#levertijd" class="link-secondary levertijd-popup margin-left-1">
                                    Meer informatie
                                </a>
                            </div>
                        </div>

                        <div v-if="entry.upsell">
                            <collapse title="Inhoud van combinatie:">
                                <template v-slot:title>
                                    {{
                                        --
                                        @if($entry->upsell->discount_percentage > 0)
                                            met <strong>&nbsp;{{ $entry->upsell->discount_percentage }}% korting</strong>
                                    @endif
                                    --}}
                                </template>
                                <template v-slot:content>
                                    <ul class="mt-1">
                                        <li v-for="product in entry.upsell.products">
                                            <a class="no-underline" :href="'/' + product.url">{{
                                                    product.name
                                                }}</a> &times; {{ product.amount }}
                                        </li>
                                    </ul>
                                </template>
                            </collapse>
                        </div>
                    </template>

                    <template v-slot:options>
                        <div class="options" v-if="this.hasOptions">
                            <ul>
                                <li v-for="variable in entry.variables">
                                    {{ variable.label }}: {{ variable.print_value }}
                                </li>
                            </ul>
                        </div>
                    </template>
-->
        </form>

        <div class="flex mt-8">
            <div class="ml-auto w-full md:w-2/3 lg:w-1/2 2xl:w-1/3 flex" v-cloak>
                <div class="text-right w-full mb-4">
                    <div v-if="!loading && (cart.discount.price > 0 || cart.payment_method_id || cart.pickup_point_id || cart.shipping_method_id)"
                         class="border-0 border-solid border-daalder-dark-blue pb-2 mb-2"
                         :class="{'border-b': !parseInt(this.cart.discount.price)}">
                        <div class="flex justify-between text-md">
                            <span>Subtotal</span>
                            <span>{{ formatCurrency(cart.subtotal.price, cart.subtotal.currency_code) }}</span>
                        </div>
                        <div v-if="cart.shipping_method_id" class="flex justify-between text-md">
                            <span>Shipping</span>
                            <span>{{ formatCurrency(cart.shipping_method.price.price, cart.shipping_method.price.currency_code) }}</span>
                        </div>
                        <div v-if="cart.pickup_point_id" class="flex justify-between text-md">
                            <span>Pickup</span>
                            <span>{{ formatCurrency(cart.pickup_point.price.price, cart.pickup_point.price.currency_code) }}</span>
                        </div>
                        <div v-if="cart.payment_method_id" class="flex justify-between text-md">
                            <span>Transaction cost</span>
                            <span>{{ formatCurrency(cart.payment_method.transaction_fee.price, cart.payment_method.transaction_fee.currency_code) }}</span>
                        </div>
                        <div v-if="cart.discount.price > 0"
                             class="flex text-lg font-bold justify-between mt-2 p-2 bg-blue-100 rounded border-daalder-dark-blue border-solid border">
                            <span>Discount</span>
                            <span>- {{ formatCurrency(cart.discount.price, cart.discount.currency_code) }}</span>
                        </div>
                    </div>

                    <div v-if="loading" class="loading-pulse mt-4">
                        <div class="ml-auto h-20 w-full"></div>
                    </div>

                    <div v-else class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-xl font-extrabold text-gray-900">Total</span>
                            <span class="text-gray-500 text-sm">Incl vat</span>
                        </div>
                        <div class="text-price-lg leading-none text-daalder-dark-blue font-bold">
                            {{ formatCurrency(total, cart.total.currency_code) }}
                        </div>
                    </div>
                    <btn @click="proceed" :disabled="loading || !this.cart.entries.length"
                         class="mt-8"
                         href="/checkout">
                        Proceed to checkout
                    </btn>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import BasketRepository from "../../Repositories/BasketRepository";
import FormatsCurrency from "../../mixins/FormatsCurrency";
import ApiService from "../../services/ApiService";

export default {
    props: {
        minOrderAmount: Number,
    },
    mixins: [FormatsCurrency],
    data: function() {
        return {
            total: 0,
            cart: null,
            loading: true,
            apiService: new ApiService()
        }
    },
    beforeMount() {
        this.loadCart();
    },
    mounted() {

    },
    methods: {
        async loadCart() {
            this.cart = await (new BasketRepository()).fetch();
            this.total = this.cart.total.price;

            if (this.cart) {
                this.loading = false;
            }
        },
        updated(cart) {
            this.cart = cart;
            this.total = this.cart.total.price;
        },
        removeItem(entryId) {
            let item = this.$refs['basket-item-' + entryId][0];

            this.apiService.delete(item.url)
                .then(response => {
                    this.updated(response.data);
                })
                .catch(error => {
                    console.log(error);
                    if (error.response.status === 408) { // session timeout
                        document.location.reload();
                    }
                });
        },
        proceed(event) {
            if (!this.cart || !this.cart.entries.length) {
                event.preventDefault();
            }
        }
    },
}
</script>

<style scoped>

</style>
