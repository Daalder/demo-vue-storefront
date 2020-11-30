<template>
    <div>
        <textarea-field id="comment" label="Room for remarks" description="For example, are there specific points that the transporter should pay attention to? Let us know!"
                        placeholder="remarks"
                        v-model="comment" @blur="update">

        </textarea-field>
    </div>
</template>

<script>
import _ from 'lodash';
import ApiService from "../../../services/ApiService";

export default {
    name: "comment",
    props: {},
    data() {
        return {
            comment: null,
            apiService: new ApiService
        }
    },

    beforeMount() {
        bus.$on('cart-changed', (cart) => {
            this.comment = cart.comment;
        });
    },

    methods: {
        update: _.debounce(function(e) {
            this.apiService.put('v2/basket?includes=shipping_method,pickup_point,payment_method', {'comment': this.comment})
                .then((response) => {
                    bus.$emit('cart-changed', response.data);
                });
        }, 500),
    }
}
</script>

<style scoped>

</style>
