<template>
    <div>
        <div class="flex justify-between">
            <h2 class="text-xl leading-9 font-medium text-gray-900 sm:text-2xl sm:leading-10 mb-4">
                In your cart
            </h2>
            <btn @click="pay">Place order</btn>
        </div>

        <cart-tabel class="mt-8"></cart-tabel>

        <p>
            <a href="/cart" class="flex items-center text-daalder-dark-blue">
                <span class="material-icons text-sm">arrow_back_ios</span>
                <span class="underline">Change order</span>
            </a>
        </p>

        <div class="border border-gray-200 my-4"></div>

        <h2 class="text-xl leading-9 font-medium text-gray-900 sm:text-2xl sm:leading-10 mb-4">
            Personal data
        </h2>

        <div v-if="customer && cart" class="mb-4">
            <div class="flex flex-col sm:flex-row leading-relaxed">
                <div class="w-1/2 flex flex-col mb-4">
                    <strong>Delivery address</strong>
                    <span>{{ customer.short_full_name }}</span>
                    <span v-if="customer.company">{{ customer.company.contact_name }}</span>
                    <span>{{ customer.primaryDeliveryAddress.address_line_1 }}</span>
                    <span>{{ customer.primaryDeliveryAddress.postalcode }} {{ customer.primaryDeliveryAddress.city }}</span>
                    <span>{{ customer.primaryDeliveryAddress.country_name }}</span>
                </div>
                <div class="w-1/2 flex flex-col mb-4">
                    <strong>Invoice address</strong>
                    <span>{{ customer.short_full_name }}</span>
                    <span v-if="customer.company">{{ customer.company.contact_name }}</span>
                    <span>{{ customer.primaryInvoiceAddress.address_line_1 }}</span>
                    <span>{{ customer.primaryInvoiceAddress.postalcode }} {{ customer.primaryInvoiceAddress.city }}</span>
                    <span>{{ customer.primaryInvoiceAddress.country_name }}</span>
                </div>
            </div>
            <div class="w-1/2 flex flex-col">
                <strong>Contact details</strong>
                <span>{{ customer.email }}</span>
                <span>{{ customer.mobile }}</span>
                <template v-if="cart.comment">
                    <strong>Notes</strong>
                    <span>{{ cart.comment }}</span>
                </template>
            </div>
        </div>

        <p>
            <a href="/checkout/personal-data" class="flex items-center text-daalder-dark-blue">
                <span class="material-icons text-sm">arrow_back_ios</span>
                <span class="underline">Change personal data</span>
            </a>
        </p>

        <div class="border border-gray-200 my-4"></div>

        <h2 class="text-xl leading-9 font-medium text-gray-900 sm:text-2xl sm:leading-10 mb-4">
            Payment method
        </h2>

        <div v-if="cart" class="flex items-center mb-4">
            <img class="w-20" :src="cart.payment_method.media[0].src">
            <strong class="ml-4">{{ cart.payment_method.name }}
                <template v-if="cart.payment_method.issuer">
                    <img v-if="cart.payment_method.issuer.icon" :src="cart.payment_method.issuer.icon" :alt="cart.payment_method.issuer.name"/>
                    <span v-else>{{ cart.payment_method.issuer.name }}</span>
                </template>
            </strong>
        </div>
        <p>
            <a href="/checkout/payment" class="flex items-center text-daalder-dark-blue">
                <span class="material-icons text-sm">arrow_back_ios</span>
                <span class="underline">Change payment method</span>
            </a>
        </p>

        <div class="flex">
            <btn class="ml-auto" @click="pay">Place order</btn>
        </div>

    </div>
</template>
<script>
import ApiService from "../../services/ApiService";
import CustomerRepository from "../../Repositories/CustomerRepository";

export default {
    props: {},
    data: function() {
        return {
            sending: false,
            apiService: new ApiService,
            cart: null,
            customer: null
        }
    },
    mounted() {
        bus.$on('cart-changed', (cart) => {
            this.cart = cart;
        });
    },
    methods: {
        async loadCustomer() {
            this.customer = await (new CustomerRepository()).fetch();
        },
        async pay() {
            if (!this.sending && this.cart) {
                this.sending = true;
                await this.apiService.post('v2/basket/checkout')
                    .then(async (response) => {

                        let data = {
                            'order_id': response.data.data.id,
                            'method_id': this.cart.payment_method_id,
                            'redirect_url': location.origin + '/checkout/success',
                            'cancel_url': location.href
                        };

                        if (this.cart.payment_method.issuer) {
                            data.issuer_id = this.cart.payment_method.issuer.id;
                        }

                        await this.apiService.post('v2/order/pay', data)
                            .then((response) => {
                                location.href = response.data.payment_url || response.data.data.payment_url;
                            }).finally(() => {
                                this.sending = false;
                            });
                    })
                    .catch(() => {
                        this.sending = false;
                    })
            }
        }
    }
}
</script>
