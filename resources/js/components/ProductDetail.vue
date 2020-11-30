<template>
    <div class="w-full md:w-1/2 p-8">
        <h2 class="text-3xl leading-9 font-extrabold text-gray-900 sm:text-4xl sm:leading-10 mb-4">
            {{ product.name }}
        </h2>

        <checklist :list="checklist()"></checklist>

        <div class="mt-4">
            <div>
                <span class="text-price-lg leading-none text-daalder-dark-blue font-bold">
                    {{ formatCurrency(product.prices[0].price) }}
                </span>
                <strong v-if="product.list_price" class="ml-2 text-price-md text-gray-400 line-through">
                    {{ formatCurrency(product.list_price.price) }}
                </strong>
            </div>
            <span class="text-gray-500 text-sm">incl. VAT</span>
        </div>

        <div v-if="product.variations.length" v-for="variation in product.variations"
             class="mt-4">
            <label :for="'variation-'+variation.id"
                   class="block text-sm leading-5 font-bold text-gray-700">{{ variation.name }}</label>
            <select :id="'variation-'+variation.id"
                    class="mt-1 form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-daalder-darker-blue focus:border-daalder-dark-blue sm:text-sm sm:leading-5"
                    @change="variationSelected" v-model="selectedVariation">
                <option v-for="value in variation.values"
                        :value="value.product.url">
                    {{ value.value }}
                </option>
            </select>
        </div>

        <amount-counter class="mt-4" :amount.sync="amount"></amount-counter>

        <div class="mt-8">
            <btn v-if="product.is_visible" @click="addToCart">
                Order
            </btn>
            <btn v-else :disabled="true">
                Not available
            </btn>
        </div>
    </div>
</template>
<script>
import FormatsCurrency from "../mixins/FormatsCurrency";
import ApiService from "../services/ApiService";

export default {
    props: {
        product: {
            type: Object
        }
    },
    mixins: [FormatsCurrency],
    data() {
        return {
            amount: 1,
            selectedVariation: this.product.url,
            apiService: new ApiService,
        }
    },
    mounted() {
    },
    methods: {
        variationSelected() {
            location.href = this.selectedVariation;
        },
        checklist() {
            let checklist = {};

            if (this.product.is_visible) {
                checklist['In Stock'] = true;
            } else {
                checklist['Out of Stock'] = false;
            }

            checklist[this.product.shipping_time.name] = false;

            return checklist;
        },
        async addToCart() {
            let postData = {
                entries: [
                    {
                        product_id: this.product.id,
                        amount: this.amount
                    }
                ]
            };

            let response = await this.apiService.post('v2/basket/entries', postData);
            let entries = collect(response.data.entries);
            let entry = entries.firstWhere('product_id', this.product.id);
            entry.product = this.product;

            bus.$emit('open-added-to-cart', entry);
        }
    }
}
</script>
