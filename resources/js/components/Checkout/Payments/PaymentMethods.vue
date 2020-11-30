<template>
    <div>
        <div v-if="error" class="rounded bg-alert p-5 my-3">
            <div class="flex">
                <i class="material-icons text-2xl mr-3" style="color:#f28b3d">warning</i>
                <span class="font-bold text-xl align-middle">Something went wrong</span>
            </div>
            <p>Unfortunately, the payment has been canceled or aborted. You can pay again below.</p>
        </div>
        <div class="bg-white rounded-md -space-y-px">
            <payment-method :key="method.id"
                            :class="{'rounded-tl-md rounded-tr-md': index === 0, 'rounded-bl-md rounded-br-md': index === methods.length - 1}"
                            :order-value="orderValue"
                            v-for="(method, index) in methods"
                            :method="method"
                            :cart="cart"
                            :selectedId.sync="selectedId"
                            :selectedIssuerCode.sync="selectedIssuerCode"
            />
        </div>
    </div>
</template>

<script>
import ApiService from "../../../services/ApiService";

export default {
    name: "PaymentMethods",
    props: {
        storeId: Number,
    },
    data() {
        return {
            methods: null,
            selectedId: null,
            selectedIssuerCode: null,
            error: false,
            apiService: new ApiService,
            orderValue: null,
            cart: null
        }
    },
    beforeMount() {
        bus.$on('cart-changed', (cart) => {
            this.cart = cart;
            this.orderValue = cart.total.price;

            if (cart.payment_method_id) {
                this.selectedId = cart.payment_method_id;
                if (cart.payment_method.issuer) {
                    this.selectedIssuerCode = cart.payment_method.issuer.id;
                }
            }
        });
    },
    created() {
        this.loadPaymentMethods()
    },
    methods: {
        async loadPaymentMethods() {
            await this.apiService.get('v2/stores/' + this.storeId + '/payment-methods')
                .then((response) => {
                    this.methods = response.data.data;
                });
        }
    }
}
</script>
