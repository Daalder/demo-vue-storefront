export default async function getCustomerInfo(force) {
    force = typeof force !== 'undefined';
    let token = cookie.get('customer_token');

    if (!force && token) {
        let is_anonymous = cookie.get('is_anonymous');
        let is_authenticated = cookie.get('is_authenticated');
        let daalder = {
            customer_token: token,
            is_guest: is_anonymous,
            is_authenticated: is_authenticated
        };

        axios.defaults.headers.common['X-Daalder-Customer-Token'] = token;
        bus.$emit('customer-info-updated', daalder);
        cache.daalder = daalder;

        return Promise.resolve(daalder);
    } else {
        return axios.get(process.env.MIX_APP_URL + '/customer-token'
        )
            .then(function(response) {
                let daalder = response.data.data;
                axios.defaults.headers.common['X-Daalder-Customer-Token'] = daalder.customer_token;
                bus.$emit('customer-info-updated', daalder);
                cache.daalder = daalder;

                return daalder;
            });
    }
}
