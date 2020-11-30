import ApiService from "../services/ApiService";

class CustomerRepository {
    constructor() {
        this.apiService = new ApiService;
    }

    async fetch() {
        if (cache.daalder) {
            return await this._fetchRequest();
        } else {
            return await this.waitForCustomerInfo();
        }
    }

    async _fetchRequest() {
        if (cache.daalder.is_authenticated) {
            if (typeof cache.customer === "undefined") {
                try {
                    let response = await this.apiService.get('v2/customers/current?includes=company,primaryDeliveryAddress,primaryInvoiceAddress');
                    cache.customer = response.data.data;
                } catch (e) {
                    cache.customer = null;
                }
            }
        } else {
            cache.customer = null;
        }

        return cache.customer;
    }

    waitForCustomerInfo() {
        return new Promise((resolve, reject) => {
            bus.$once('customer-info-updated', (data) => {
                this._fetchRequest()
                    .then((response) => {
                        resolve(response);
                    })
                    .catch((error) => {
                        reject(error);
                    })
            });
        });
    }
}

export default CustomerRepository;
