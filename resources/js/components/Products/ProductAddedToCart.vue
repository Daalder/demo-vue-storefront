<template>
    <Modal ref="modal" :name="name" @closed="unsetEntry">
        <template v-slot:content v-if="entry">
            <h1 class="text-2xl leading-9 font-extrabold text-gray-900 sm:text-3xl sm:leading-10 mb-4">
                Added to shopping cart
            </h1>
            <div class="float-left max-w-md mr-4 mb-4 flex">
                <img :src="thumbnail()" class="mr-4 rounded-lg"/>
                <div>
                    <a class="link" :href="entry.product.url">
                        {{ entry.product.name }}
                    </a>
                    <div>
                            <span class="text-price-lg leading-none text-daalder-dark-blue font-bold">
                                {{ formatCurrency(entry.price.price) }}
                            </span>
                        <span v-if="entry.amount > 1" class="ml-4 text-lg font-bold">
                                {{ formatCurrency(entry.unit_price.price) }} × {{ entry.amount }}
                            </span>
                    </div>
                </div>
            </div>
            <div class="float-right flex">
                <btn @click="close" color="light" class="mr-4">
                    Continue shopping
                </btn>
                <btn href="/cart">
                    Go to cart
                </btn>
            </div>
            <div class="clear-both"></div>
            <div class="mt-8">
                <h2 class="text-xl">Other people also bought</h2>
                <div class="mt-4 grid gap-3 mx-auto grid-cols-2 sm:grid-cols-3">
                    <product-item v-for="(cross, index) in entry.product.cross_sells.slice(0, 3)"
                                  :key="cross.cross_sell_product.id"
                                  :product="cross.cross_sell_product"
                                  :class="{'sm_max:hidden': index === 2}"
                    ></product-item>
                </div>
            </div>
        </template>
    </Modal>
</template>
<script>
import Modal from "../Modal";
import {getProductImageSrc} from "../../services/getImageSrc";
import FormatsCurrency from "../../mixins/FormatsCurrency";

export default {
    components: {Modal},
    props: {},
    data() {
        return {
            name: 'added-to-cart',
            entry: null
        }
    },
    mixins: [
        FormatsCurrency
    ],
    mounted() {
        bus.$on('open-' + this.name, this.setEntry);
    },
    watch: {},
    methods: {
        setEntry(entry) {
            this.entry = entry;
        },
        unsetEntry() {
            this.entry = null;
        },
        thumbnail() {
            return getProductImageSrc(this.entry.product, 0, 'thumbnail');
        },
        close() {
            this.$refs['modal'].close();
        }
    }
}
</script>
