<template>
    <div class="w-full flex-wrap lg:flex-no-wrap flex flex-row mt-4 pb-4 border-b border-solid border-gray-300" v-cloak>
        <div class="flex-1 flex flex-row">
            <div class="w-24 mr-4">
                <img :src="thumbnail" class="mr-4"/>
            </div>
            <div class="">
                <div class="font-lg font-bold mb-2">
                    <a :href="'/'+entry.product.url">{{ entry.product.name }}</a>
                </div>
                <div class="font-md">
                    <div v-if="product.shipping_time && product.shipping_time.name"
                         class="whitespace-no-wrap flex items-center">
                        <i class="material-icons text-sm">check</i>
                        <div class="whitespace-normal">
                                    <span class="ml-2">
                                        Delivery: {{ product.shipping_time.name }}
                                    </span>
                            <a href="#delivery" class="link ml-2">
                                More info
                            </a>
                        </div>
                    </div>

                    <!--
                    <div v-if="entry.upsell">
                        <collapse title="Inhoud van combinatie:">
                            <template v-slot:title>
                                {{--
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
                    -->
                </div>
                <div class="">
                    <div class="product-options" v-if="hasOptions">
                        <div v-for="option in options" class="product-options--col">
                            <div @click="toggleOption(option)">
                                <div class="product-icon-wrapper clickable">
                                    <i class="options-icon material-icons">
                                        {{ option.collapsed ? "keyboard_arrow_down" : "keyboard_arrow_up" }}
                                    </i>
                                </div>
                            </div>
                            <div @click="toggleOption(option)">
                                <div class="product-options--title">
                                    {{ option.label }}
                                </div>
                                <div class="product-options--list" v-show="!option.collapsed">
                                    <slot name="options">
                                        <ul>
                                            <li>{{ option.print_value }}: {{ formatCurrency(option.price) }}</li>
                                        </ul>
                                    </slot>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex-break lg:hidden"></div>
        <div class="w-full md:w-2/3 lg:w-1/2 2xl:w-1/3 flex items-between">
            <div class="flex-1 px-2 flex flex-col">
                <div class="text-md text-gray-400 line-through" v-if="entry.unit_list_price.price > 0">
                    {{ formatCurrency(entry.unit_list_price.price, entry.unit_list_price.currency_code) }}
                </div>
                <div class="text-price-md text-daalder-dark-blue font-bold">
                    {{ formatCurrency(entry.unit_price.price, entry.unit_price.currency_code) }}
                </div>
            </div>
            <div class="flex-1 px-2 flex items-center flex-row">
                <span v-if="entry.fixedAmount"
                      class="text-lg font-bold">{{ amount }}</span>

                <amount-counter v-else
                                :amount.sync="amount"
                                :with-label="false"
                                @change="updateCost"
                ></amount-counter>

                <button type="button" class="ml-4 mt-1" @click="requestRemove">
                    <i class="material-icons text-2xl">remove_circle_outline</i>
                </button>
            </div>
            <div class="flex-1 pl-2 flex flex-col items-end">
                <div class="text-md text-gray-400 line-through" v-if="entry.list_price.price > 0">
                    {{ formatCurrency(entry.list_price.price) }}
                </div>
                <div class="text-price-md text-daalder-dark-blue font-bold">
                    {{ formatCurrency(entry.price.price) }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {getProductImageSrc} from "../../services/getImageSrc";
import FormatsCurrency from "../../mixins/FormatsCurrency";
import ApiService from "../../services/ApiService";

export default {
    name: "cart-item",
    props: {
        entry: Object,
        required: true
    },
    mixins: [FormatsCurrency],
    data: function() {
        return {
            product: null,
            amount: null,
            updateCounter: 0,
            thumbnail: null,
            apiService: new ApiService()
        };
    },
    beforeMount() {
        this.amount = this.entry.amount;
    },
    mounted() {
        this.product = this.entry.product;
        this.thumbnail = this.productThumbnail(this.entry.product);

        _.forEach(this.entry.options, (option) => {
            option.collapsed = true
        });
    },
    computed: {
        hasOptions() {
            if (this.options) {
                return !!this.options.length;
            }

            return false;
        },
        url() {
            return 'v2/basket/entries/' + this.entry.id;
        }
    },
    methods: {
        requestRemove() {
            this.$emit('remove', this.entry.id);
        },
        async updateCost() {
            this.updateCounter += 1;
            const updateAt = this.updateCounter;

            this.apiService.put(this.url, {amount: this.amount})
                .then(response => {
                    if (this.updateCounter > updateAt) {
                        return;
                    }

                    this.$emit('updated', response.data);
                })
                .catch(error => {
                    console.log(error);
                    if (error.status === 408) {  //session timeout
                        document.location.reload();
                        //todo give popup to notify of session timeout.
                    }
                });
        },
        toggleOption(option) {
            option.collapsed = !option.collapsed;
            this.options = _.clone(this.options);
        },
        productThumbnail(product) {
            return getProductImageSrc(product, 0, 'thumbnail');
        }
    }
}
</script>

<style scoped lang="scss">
.product-options {
    display: flex;
    font-size: 12px;
    flex-direction: column;

    &--col {
        display: flex;
        flex-direction: row;
    }

    &--title {
        cursor: pointer;
    }

    &--list {
        ul {
            padding: 0;
            margin: 0.5em 0 0;

            li {
                display: flex;
                line-height: 20px;

                i {
                    align-self: center;
                    font-size: 3px;
                    margin-right: 5px;
                }
            }
        }
    }
}
</style>
