<template>
    <div v-if="pickupPoints.length > 0" class="sm:border-t sm:border-gray-200">
        <div class="mt-4" :class="{'shadow-highlight': isPickup}">
            <div class="flex flex-row" @click="selectFirstPickup()">
                <div class="mr-5 w-5">
                    <input type="radio" :checked="isPickup" style="pointer-events:none;" readonly
                           class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out"/>
                </div>
                <div class="leading-normal flex-1">
                    <div class="flex justify-between">
                        <strong>Pickup at <span v-if="store">{{ store.name }}</span></strong>
                        <span class="text-quaternary text-xl font-medium whitespace-no-wrap">
                            <template v-if="selected">{{ formattedPickupPrice(selected) }}</template>
                            <template v-else-if="pickupPoints.length">
                                {{ formattedPickupPrice(pickupPoints[0]) }}
                            </template>
                        </span>
                    </div>

                    <div class="sm:mr-16">
                        <p>We will inform you as soon as your order is ready for collection at <span v-if="store">{{ store.name }}</span>.</p>
                        <p><strong>Choose your pick-up location:</strong></p>
                    </div>
                </div>
            </div>

            <ul class="relative bg-white rounded-md -space-y-px mt-4 ml-9">
                <li v-for="pickup in pickupPoints" :key="'pickup_' + pickup.id"
                    @click="update(pickup)">
                    <div class="relative cursor-pointer border rounded-tl-md rounded-tr-md p-4 flex flex-col md:pl-4 md:pr-6 md:grid md:grid-cols-2"
                         :class="{'bg-indigo-50 border-indigo-200 z-10': selected && selected.id === pickup.id, 'border-gray-200': !selected || selected.id !== pickup.id}">
                        <label class="flex items-center text-sm leading-5 space-x-3">
                            <input name="pricing_plan" type="radio" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out cursor-pointer" aria-describedby="plan-option-pricing-0 plan-option-limit-0"
                                   :checked="selected && selected.id === pickup.id">
                            <span class="font-bold text-gray-900">{{ pickup.address.city }}</span>
                            <span class="font-medium text-gray-900">{{ pickup.address.address_line_1 }}</span>
                        </label>
                        <p v-if="parseInt(pickup.price.price) !== 0" class="ml-6 pl-1 text-sm leading-5 md:ml-0 md:pl-0 md:text-right"
                           :class="{'text-indigo-700': selected && selected.id === pickup.id, 'text-gray-500': !selected || selected.id !== pickup.id}">
                            + {{ formattedPickupPrice(pickup) }}
                        </p>
                    </div>
                </li>
            </ul>

            <div class="rounded-md bg-blue-50 p-4 my-4">
                <div class="flex">
                    <i class="flex-shrink-0 material-icons text-xl text-blue-700 mr-4">local_shipping</i>
                    <div class="flex-1 md:flex md:justify-between">
                        <p class="text-sm leading-5 text-blue-700">
                            You will receive an email from us when your order is ready for collection.
                        </p>
                    </div>
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
    name: "pickup",
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
            address: null,
            pickupPoints: [],
            selected: null,
            isPickup: false,
            cart: null,
            store: app.store,
            apiService: new ApiService
        }
    },
    async beforeMount() {
        bus.$on('store', (store) => {
            this.store = store;
        });
        bus.$on('customer-changed', (customer) => {
            if (customer) {
                this.address = customer.primaryDeliveryAddress;
            }
        });

        this.loadDeliveryMethods();
    },
    mounted() {
        bus.$on('cart-changed', (cart) => {
            this.cart = cart;

            if (cart.pickup_point) {
                this.selected = cart.pickup_point;
                this.isPickup = true;
                bus.$emit('step-state-update', true);
            }
        });
    },
    created() {
        bus.$on('delivery-changed', (method) => {
            this.isPickup = method === null;
            if (!this.isPickup) {
                this.selected = null
            }
        });
        bus.$on('pickup-changed', (pickup) => {
            this.selected = pickup;
            this.isPickup = true
        });
    },
    methods: {
        async loadDeliveryMethods() {
            await this.apiService.get('v2/stores/' + this.storeId + '/pickup-points?includes=address')
                .then((response) => {
                    this.pickupPoints = response.data.data
                });
        },
        update(pickup) {
            bus.$emit('delivery-changed', null);
            bus.$emit('pickup-changed', pickup);
            bus.$emit('step-state-update', true);
            this.setPickupPoint(pickup)
        },
        selectFirstPickup() {
            this.update(this.pickupPoints[0])
        },
        setPickupPoint(pickup) {
            this.apiService.put('v2/basket?includes=shipping_method,pickup_point,payment_method', {'pickup_point_id': pickup.id})
                .then((response) => {
                    bus.$emit('cart-changed', response.data);
                });
        },
        formattedPickupPrice(pickup, doFormat = true) {
            let price = this.exVat ? pickup.price.price_excluding_vat : pickup.price.price;

            if (this.cart && pickup.free_from_price && parseFloat(this.cart.subtotal.price) > parseFloat(pickup.free_from_price.price)) {
                price = '0.00';
            }

            if (doFormat) {
                return this.formatCurrency(price, pickup.price.currency_code);
            } else {
                return price;
            }
        }
    }
}
</script>

<style scoped>

</style>
