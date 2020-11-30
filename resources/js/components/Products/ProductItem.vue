<template>
    <div
        class="product-item relative flex flex-col rounded-lg shadow-md hover:shadow-lg overflow-hidden transition-shadow transition duration-200">
        <a :href="product.url" class="block absolute top-0 left-0 w-full h-full z-1"></a>
        <div class="relative flex-shrink-0">
            <span v-if="listPrice" class="bedge sale">Sale</span>
            <span v-else-if="isNew" class="bedge new">New</span>
            <img class="h-48 w-full object-contain"
                 :class="{'normal-image': hasSecondImage()}" :alt="product.name"
                 :src="productThumbnail()"/>
            <img class="h-48 w-full object-contain hover-image"
                 v-if="hasSecondImage()" :alt="product.name" :src="productSecondThumbnail()"/>
        </div>
        <div class="flex-1 bg-white p-2 lg:p-4 flex flex-col justify-between">
            <div class="flex-1">
                <h3 class="mt-2 text-xl leading-6 font-semibold text-gray-900">
                    {{ product.name }}
                </h3>
                <p v-if="product.variations && product.variations.length > 0"
                   class="mt-3 text-base leading-3 text-gray-500 flex items-center"
                   v-html="getVariationsLabel(product)">
                </p>
            </div>
            <div class="flex items-center mt-1">
                <strong class="text-lg text-daalder-dark-blue">
                    {{ price }}
                </strong>
                <strong v-if="listPrice" class="ml-2 text-sm text-gray-400 line-through">
                    {{ listPrice }}
                </strong>
            </div>
        </div>
    </div>
</template>

<script>
import FormatsCurrency from "../../mixins/FormatsCurrency";
import {getProductImageSrc} from "../../services/getImageSrc";

export default {
    name: "product-item",
    props: {
        product: {
            type: Object,
            default: function() {
                return {
                    url: null
                };
            }
        }
    },
    mixins: [FormatsCurrency],
    components: {},
    data() {
        return {
            isNew: Math.floor(Math.random() * 3) === 0,
            promotionLabel: false,
            promotionLabelClass: false,
            properties: collect(),
            basket: null,
            _hasSecondImage: null
        }
    },
    mounted() {
        this.properties = collect(this.product.properties);
    },
    computed: {
        url: function() {
            return this.product.url ? '/' + this.product.url : '#';
        },
        listPrice: function() {
            if (!this.product.list_price)
                return '';
            let listPrice = this.normalizePrice(this.product.list_price.price || this.product.list_price);
            return this.formatCurrency(listPrice);
        },
        price: function() {
            let price = '0';
            if (this.product.prices && this.product.prices.length > 0)
                price = this.product.prices[0].price ? this.product.prices[0].price : this.product.prices[0];

            price = this.normalizePrice(price);

            return this.formatCurrency(price);
        },
        priceDescription: function() {
            if (this.template === 'stone')
                return 'm<sup>2</sup>';
            else if (this.isWooden)

                if (this.oldPrice) {
                    return '<span class="small quaternary-color">p.m.</span>';
                } else {
                    return '<span class="small quaternary-color">p.m.</span><span class="default">per meter</span>';
                }

            return false
        },
        hasVariations: function() {
            let variations = collect(this.product.variations);

            if (variations.count() > 0) {
                return true;
            }

            return false;
        },
        hasVariables: function() {
            let variables = collect(this.product.variables);

            if (variables.count() > 0) {
                return true;
            }

            return false;
        },
        isStone: function() {
            return this.template === 'stone';
        },
        isWooden: function() {
            return this.template === 'wood';
        },
        template: function() {
            if (this.product.productattributeset && this.product.productattributeset.product_template) {
                return this.product.productattributeset.product_template.name;
            }

            return null;
        },

    },
    methods: {
        normalizePrice(price) {
            if (this.isWooden && this.getProperty('lengte')) {
                let length = this.getProperty('lengte').value / 1000; // meters
                price = price / length;
            }

            return price;
        },
        getProperty(code) {
            if (!this.properties.count())
                return null;

            return this.properties.filter((value) => value.attribute.code === code).first();
        },
        hasSecondImage() {
            if (typeof this._hasSecondImage === 'undefined') {
                this._hasSecondImage = !!this.productSecondThumbnail();
            }
            return this._hasSecondImage;
        },
        productThumbnail() {
            return getProductImageSrc(this.product);
        },
        productSecondThumbnail() {
            return getProductImageSrc(this.product, 1);
        },
        variationCount(variation) {
            return window.collect(variation.values).pluck('value').unique().count();
        },
        getVariationsLabel(product) {
            let variations = collect(product.variations);

            if (variations.count() > 1) {
                let variationsLabels = variations.map((variation) => {
                    return this.variationCount(variation) + ' ' + this.variationName(variation);
                });

                return '<i class="-ml-1 material-icons">fullscreen</i>' + variationsLabels.join(' + ');

            } else {
                return '<i class="-ml-1 material-icons">fullscreen</i>' + this.variationCount(variations.first()) + ' ' + this.variationName(variations.first());
            }
        },
        variationName(variation) {
            return variation.type + 's';
        },
        quickAddToBasket() {
            bus.$emit('quickAddToBasket', this);
        }
    }
}
</script>

<style scoped>
.product-item .hover-image {
    opacity: 0;
    position: absolute;
    left: 0;
    top: 0;
    transition: opacity 0.35s ease-in-out;
}

.product-item .normal-image {
    transition: opacity 0.35s ease-in-out;
    opacity: 1;
}

.product-item:hover .hover-image {
    opacity: 1;
}

.product-item:hover .normal-image {
    opacity: 0;
}
</style>
