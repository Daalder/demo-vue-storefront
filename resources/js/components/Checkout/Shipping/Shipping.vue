<template>
    <div>
        <div class="content-box" v-for="method in methods"
             :key="method.id" :class="{'shadow-highlight': selected === method.id}">
            <div class="flex flex-row" @click="update(method)">
                <div class="mr-5 w-5">
                    <input type="radio" :checked="selected === method.id" @change="update(method)" style="pointer-events:none;" readonly
                           class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
                </div>
                <div class="leading-normal flex-1">
                    <div class="flex justify-between">
                        <strong v-if="!method.is_express">Have it delivered</strong>
                        <strong v-else>Express delivery<br class="sm:hidden"/><span
                            class="sm:ml-4 text-highlight">Available in your region!</span></strong>
                        <span
                            class="text-quaternary text-xl font-medium whitespace-no-wrap"
                            :class="{ 'green-color' : parseInt(method.price.price) == 0 }">
                            {{ formatCurrency(method.price.price, method.price.currency_code) }}
                        </span>
                    </div>

                    <div v-if="!method.is_express" class="sm:mr-16">
                        <p>The products are delivered to your location. We will contact you for the desired delivery time.</p>
                    </div>
                    <div v-else class="sm:mr-16">
                        <p>Our express delivery service is available in the region where you live. Order today and your order will be delivered within 2 working days.</p>
                    </div>

                    <template v-if="selected === method.id">
                        <p>
                            <span class="font-medium">Address</span><br>
                            {{ address.address_line_1 }}<br>
                            {{ address.postalcode }}, {{ address.city }}<br>
                            {{ address.country_name }}
                        </p>
                        <p>
                            <a href="/checkout/personal-data" class="flex items-center text-daalder-dark-blue">
                                <span class="material-icons text-sm">arrow_back_ios</span>
                                <span class="underline">Choose a different delivery address</span>
                            </a>
                        </p>
                    </template>
                </div>
            </div>


            <div class="rounded-md bg-blue-50 p-4 my-4">
                <div class="flex">
                    <i class="flex-shrink-0 material-icons text-xl text-blue-700 mr-4">local_shipping</i>
                    <div class="flex-1 md:flex md:justify-between">
                        <p class="text-sm leading-5 text-blue-700">
                            <template v-if="!method.is_express">
                                <strong class="mr-4 text-xs">Delivery is provided by <span v-if="store">{{ store.name }}</span> delivery service.</strong>
                                <a href="#levertijd" class="i-leading-inherit levertijd-popup link thin text-xs">More info about shipping</a>
                            </template>
                            <template v-else>
                                <strong class="mr-4 text-xs text-highlight">Ordered before 12:00 tomorrow delivered</strong>
                                <a href="#levertijd" class="i-leading-inherit levertijd-popup link thin text-xs">More info about Express shipping</a>
                            </template>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import ApiService from "../../../services/ApiService";
import FormatsCurrency from "../../../mixins/FormatsCurrency";

export default {
    name: "shipping",
    props: {
        exVat: Boolean,
        storeId: Number,
        selectedId: Number
    },
    mixins: [
        FormatsCurrency
    ],
    data() {
        return {
            customer: null,
            address: null,
            methods: null,
            selected: null,
            store: app.store,
            apiService: new ApiService
        }
    },
    async beforeMount() {
        bus.$on('store', (store) => {
            this.store = store;
        });
        bus.$on('cart-changed', (cart) => {
            if (cart.shipping_method_id) {
                this.selected = cart.shipping_method_id;
                bus.$emit('step-state-update', true);
            }
        });

        bus.$on('user', (customer) => {
            if (customer) {
                this.address = customer.primaryDeliveryAddress;
                this.loadDeliveryMethods();
            }
        });
    },
    created() {
        bus.$on('delivery-changed', (method) => {
            if (method) {
                this.selected = method.id;
            } else {
                this.selected = null
            }
        });

        bus.$on('next-step', () => {
            location.href = '/checkout/payments';
        });
    },
    methods: {
        async loadDeliveryMethods() {
            let countryCode = this.address.country_code || 'NL';
            this.apiService.get('v2/basket/shipping-methods/' + this.storeId + '/' + countryCode)
                .then((response) => {
                    this.methods = response.data.data
                });
        },
        update(method) {
            bus.$emit('delivery-changed', method);
            this.setShippingMethod(method);
            bus.$emit('step-state-update', true);
        },
        setShippingMethod(method) {
            this.apiService.put('v2/basket?includes=shipping_method,pickup_point,payment_method', {'shipping_method_id': method.id})
                .then((response) => {
                    bus.$emit('cart-changed', response.data);
                });
        }
    },
    computed: {
        storeName() {
            return typeof app.store === 'Object' ? app.store.name : '';
        }
    }
}
</script>

<style scoped>

</style>
