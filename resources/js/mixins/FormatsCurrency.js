export default {
    methods: {
        formatCurrency(amount, currency = 'EUR') {
            let formatter = new Intl.NumberFormat(undefined, {
                style: 'currency',
                currency: currency,
            });

            return formatter.format(amount);
        }
    }
}
