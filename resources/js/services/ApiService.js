import * as md5 from "md5";

let collect = require('collect.js');

if (typeof window.pendingRequests === 'undefined') {
    window.pendingRequests = collect();
}

export default class ApiService {
    async request(method, url, options) {
        if (axios.defaults.headers.common['X-Daalder-Customer-Token']) {
            return this._axiosRequest(method, url, options);
        } else {
            return this.waitForCustomerToken(method, url, options);
        }
    }

    async _axiosRequest(method, url, options) {
        let baseUrl = process.env.MIX_DAALDER_API_URL;

        return axios[method](baseUrl + url, options);
    }

    _pendingRequestsKey(url, options = {}) {
        return url + md5(JSON.stringify(options));
    }

    async get(url, options) {
        let key = this._pendingRequestsKey(url, options);

        if (pendingRequests.has(key)) {
            return pendingRequests.get(key);
        }

        let request = this.request('get', url, options);
        pendingRequests.put(key, request);

        request.then(() => {
            pendingRequests.forget(key);
        });

        return request;
    }

    async post(url, options) {
        return this.request('post', url, options);
    }

    async put(url, options) {
        return this.request('put', url, options);
    }

    async delete(url) {
        return this.request('delete', url);
    }

    waitForCustomerToken(method, url, options) {
        return new Promise((resolve, reject) => {
            bus.$once('customer-info-updated', (data) => {
                this._axiosRequest(method, url, options)
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
