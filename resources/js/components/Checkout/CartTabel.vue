<template>
    <div class="mb-6" :class="{'loading': loading}">
        <template v-if="loading">
            <div class="flex flex-col">
                <div v-for="x in [1,2]" class="loading-pulse flex justify-between">
                    <div v-if="withImages" class="h-8 mt-4 w-16 mr-4"></div>
                    <div class="h-8 mt-4 flex-1"></div>
                </div>
                <div class="border border-gray-200 mt-4"></div>
                <div v-for="x in [1,2,3]" class="loading-pulse flex justify-between">
                    <div class="h-8 mt-4 mr-8 flex-1"></div>
                    <div class="h-8 mt-4 w-16"></div>
                </div>
            </div>
        </template>

        <template v-if="!loading && cart && cart.entries.length" v-cloak>
            <div class="w-full flex flex-col leading-loose">
                <div class="flex mb-1 items-start justify-start"
                     v-for="entry in cart.entries" :key="entry.id">
                    <span v-if="withImages">
                        <img :src="thumbnail(entry.product)" :alt="entry.product.name"/>
                    </span>
                    <span class="flex-1 truncate">{{ entry.product.name }} &times; {{ entry.amount }}
                        <ul v-if="entry.options"
                            class="m-0 p-0 text-gray-500 leading-tight" :class="{'text-xs': isSmall}">
                            <li v-for="option in entry.options" :key="option.id"
                                class="list-none truncate">
                                {{ option.label + ': ' + option.print_value }}
                            </li>
                        </ul>
                        <ul v-if="entry.upsell && entry.upsell.products"
                            class="m-0 p-0 text-gray-500 leading-tight" :class="{'text-xs': isSmall}">
                            <li v-for="product in entry.upsell.products"
                                class="list-none truncate">
                                {{ product.name }} &times; {{ product.amount }}
                            </li>
                        </ul>
                    </span>
                    <span class="text-right whitespace-no-wrap">
                        {{ formatCurrency(entry.price.price, entry.price.currency_code) }}
                    </span>
                </div>
            </div>

            <div class="border border-gray-200 mt-4"></div>

            <div class="flex flex-col w-full leading-loose" v-cloak>
                <div class="flex mb-1 justify-between">
                    <strong>Subtotal</strong>
                    <strong class="whitespace-no-wrap">
                        {{ formatCurrency(cart.subtotal.price, cart.subtotal.currency_code) }}
                    </strong>
                </div>

                <div class="flex mb-1 justify-between" v-if="hasDelivery">
                    <span>{{ cart.shipping_method.name }}</span>
                    <span class="whitespace-no-wrap">
                        {{ formatCurrency(cart.shipping_method.price.price, cart.shipping_method.price.currency_code) }}
                    </span>
                </div>

                <div class="flex mb-1 justify-between" v-if="hasPickup">
                    <span>Pickup {{ cart.pickup_point.address.address_line_1 }}</span>
                    <span class="whitespace-no-wrap">
                        {{ formatCurrency(cart.pickup_point.price.price, cart.pickup_point.currency_code) }}
                    </span>
                </div>

                <div class="flex mb-1 justify-between" v-if="hasPayment">
                    <span>{{ cart.payment_method.name }}
                        <template v-if="cart.payment_method.issuer">
                            <img v-if="cart.payment_method.issuer.icon" :src="cart.payment_method.issuer.icon"
                                 :alt="cart.payment_method.issuer.name"/>
                            <span v-else :text="cart.payment_method.issuer.name"></span>
                        </template>
                    </span>
                    <span class="whitespace-no-wrap">
                        <template v-if="cart.payment_method.transaction_label != 0">
                            {{ formatCurrency(cart.payment_method.transaction_fee.price, cart.payment_method.transaction_fee.currency_code) }}
                        </template>
                        <badge v-else>Free</badge>
                    </span>
                </div>

                <div v-if="cart.discount.price > 0" class="flex mb-1 justify-between">
                    <strong>Discount</strong>
                    <strong class="whitespace-no-wrap">
                        - {{ formatCurrency(cart.discount.price, cart.discount.currency_code) }}
                    </strong>
                </div>

                <div class="flex mb-1 justify-between text-gray-500">
                    <span>Total excl. VAT</span>
                    <span class="whitespace-no-wrap">
                        {{ formatCurrency(cart.total.price_excluding_vat, cart.total.currency_code) }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <strong>Total incl. VAT</strong>
                    <strong class="text-daalder-dark-blue whitespace-no-wrap">
                        {{ formatCurrency(cart.total.price, cart.total.currency_code) }}
                    </strong>
                </div>

            </div>
        </template>
    </div>
</template>

<script>
import BasketRepository from "../../Repositories/BasketRepository";
import FormatsCurrency from "../../mixins/FormatsCurrency";
import ApiService from "../../services/ApiService";
import Badge from "../Badge";
import {getProductImageSrc} from "../../services/getImageSrc";

export default {
    components: {Badge},
    props: {
        withImages: {
            type: Boolean,
            default: true
        },
        isSmall: {
            type: Boolean,
            default: false
        }
    },
    mixins: [FormatsCurrency],
    data: function() {
        return {
            cart: null,
            loading: true,
            apiService: new ApiService()
        }
    },
    beforeMount() {
        this.loadCart();
    },
    mounted() {
        bus.$on('cart-changed', (cart) => {
            this.updated(cart);
        });
    },
    methods: {
        async loadCart() {
            this.cart = await (new BasketRepository()).fetch();

            if (this.cart) {
                bus.$emit('cart-changed', this.cart);
                this.loading = false;
            }
        },
        updated(cart) {
            this.cart = cart;
        },
        thumbnail(product) {
            return getProductImageSrc(product, 0, 'thumbnail');
        },
    },
    computed: {
        hasPayment() {
            return this.cart.payment_method_id != null;
        },
        hasDelivery() {
            return this.cart.shipping_method_id != null;
        },
        hasPickup() {
            return this.cart.pickup_point_id != null;
        }
    }
}
</script>
