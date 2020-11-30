<template>
    <div v-if="minMaxOrderValueRule()" class="relative border p-4 flex" @click="changed"
         :class="{'bg-indigo-50 border-indigo-200 z-10': selectedId === method.id, 'border-gray-200': selectedId !== method.id}">
        <div class="flex items-center h-5">
            <input :id="'method-'+method.id" name="privacy_setting" type="radio" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 cursor-pointer border-gray-300"
                   :value="method.id" :checked="selectedId === method.id" @change="changed">
        </div>
        <label :for="'method-'+method.id" class="ml-3 w-full flex flex-col cursor-pointer">
            <span class="block text-sm font-bold"
                  :class="{'text-indigo-900': selectedId === method.id, 'text-gray-900': selectedId !== method.id}">
                {{ method.name }}

                <span class="font-medium whitespace-no-wrap ml-4"
                      :class="{'text-indigo-700': selectedId === method.id, 'text-gray-500': selectedId !== method.id}"
                      v-if="method.transaction_label != 0">&plus; <span
                    v-if="!method.is_percentage">&euro;</span> {{ method.transaction_label }} <span
                    v-if="method.is_percentage">&percnt;</span>
                </span>

                <span class="font-medium whitespace-no-wrap ml-4" v-else>
                    <badge>Free</badge>
                </span>
            </span>
            <div class="w-full flex">
                <!-- iDeal -->
                <template
                    v-if="selectedId === method.id && method.issuers && method.issuers.length">
                    <div class="max-w-lg mt-3 rounded-md shadow-sm sm:max-w-xs">
                        <select name="issuer" id="issuer" :value="issuer_code || '-'" ref="select" @input="issuerChanged" @change="issuerChanged"
                                class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                                :class="{'text-placeholder': !issuer_code}">
                            <option disabled value="-">Select your bank</option>
                            <option :value="issuer.id" v-for="issuer in method.issuers">{{ issuer.name }}</option>
                        </select>
                    </div>
                    <div v-if="selectedId === method.id" class="w-auto flex h-12 ml-auto">
                        <btn @click="nextStep">
                            Checkout
                        </btn>
                    </div>
                </template>

                <template v-else>
                    <div v-if="selectedId === method.id" class="flex justify-end w-full">
                        <btn @click="nextStep">
                            Checkout
                        </btn>
                    </div>
                </template>
            </div>
        </label>
    </div>
</template>

<script>

import ApiService from "../../../services/ApiService";
import FormatsCurrency from "../../../mixins/FormatsCurrency";

export default {
    name: "PaymentMethod",
    data() {
        return {
            sending: false,
            apiService: new ApiService,
            issuer_code: null
        }
    },
    props: [
        'cart',
        'method',
        'selectedId',
        'selectedIssuerCode',
        'orderValue',
    ],
    mixins: [
        FormatsCurrency
    ],
    mounted() {
        if (this.selectedIssuerCode && this.method.issuers) {
            this.issuer_code = this.selectedIssuerCode;
        }
    },
    watch: {
        selectedIssuerCode: function(issuerCode, old) {
            if (!this.issuer_code && this.method.issuers) {
                this.issuer_code = issuerCode
                    ? issuerCode
                    : this.method.issuers[0].id;
            }
        }
    },
    methods: {
        changed() {
            this.$emit('update:selectedId', this.method.id);
            this.$emit('update:selectedIssuerCode', this.issuer_code);
            bus.$emit('payment-changed', this.method);
            this.setPaymentMethod();
        },
        issuerChanged() {
            this.issuer_code = this.$refs.select.value;
            this.$emit('update:selectedIssuerCode', this.issuer_code);
            this.setPaymentMethod();
        },
        setPaymentMethod() {
            if (!this.sending) {
                this.sending = true;
                this.apiService.put('v2/basket?includes=shipping_method,pickup_point,payment_method', {
                    'payment_method_id': this.method.id,
                    'issuer_code': this.issuer_code ? String(this.issuer_code) : ''
                })
                    .then((response) => {
                        bus.$emit('cart-changed', response.data);
                    })
                    .finally(() => {
                        this.sending = false;
                    });
            }
        },
        nextStep() {
            if (this.selectedId === this.method.id && (!this.method.issuers || this.issuer_code)) {
                window.location.href = '/checkout/confirm';
            }
        },
        minMaxOrderValueRule() {
            if (this.method.min_order_value || !this.method.max_order_value) {
                return true;
            }
            return (this.method.min_order_value <= this.orderValue) && (this.orderValue <= this.method.max_order_value);
        }
    }
}
</script>
