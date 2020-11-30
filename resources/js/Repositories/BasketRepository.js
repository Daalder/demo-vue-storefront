import ApiService from "../services/ApiService";

class BasketRepository {
    constructor() {
        this.apiService = new ApiService;
    }

    async fetch() {
        if (typeof cache.cart === "undefined") {
            try {
                let response = await this.apiService.get('v2/basket?includes=entries.product.media.versions,entries.product.properties,entries.product.productattributeset.producttemplate,entries.product.shipping,customer.primaryInvoiceAddress,customer.primaryDeliveryAddress,shipping_method,pickup_point,payment_method');

                cache.cart = response.data;
            } catch (e) {
                cache.cart = null
            }
        }

        return cache.cart;
    }

    async fetchSmall() {
        if (typeof cache.small_basket === "undefined") {
            try {
                let response = await this.apiService.get('v2/basket');

                cache.small_basket = response.data;
            } catch (e) {
                cache.small_basket = null;
            }
        }

        return cache.small_basket;
    }
}

export default BasketRepository;
