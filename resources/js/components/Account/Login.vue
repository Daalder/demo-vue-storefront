<template>
    <validation-observer tag="form" v-slot="{dirty, valid}" ref="form" @submit.prevent="submit">
        <span class="hidden" :style="propagateFormState(dirty)"></span>

        <div class="flex flex-col mt-8">
            <text-field id="email" v-model="form.email" autocomplete="email" type="email" required placeholder="E-mailadres"/>
            <text-field id="password" v-model="form.password" type="password" required label="Password"/>
        </div>

        <btn type="submit" :disabled="isDisabled" class="mt-6">
            Login
        </btn>

    </validation-observer>
</template>
<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate';
import ValidatedField from '../ValidatedField';
import TextField from "../Forms/TextField";
import SwitchField from "../Forms/SwitchField";
import ApiService from "../../services/ApiService";

export default {
    components: {
        SwitchField,
        TextField,
        ValidatedField, ValidationProvider, ValidationObserver
    },
    props: {
        redirectUrl: {
            type: String,
            default: '/account'
        }
    },
    data() {
        return {
            sending: false,
            isDisabled: false,
            differentDeliveryAddress: false,
            form: {
                email: '',
                password: ''
            },
            apiService: new ApiService
        }
    },
    mounted() {

    },
    watch: {},
    methods: {

        async submit() {
            const isValid = await this.validate();

            if (isValid && !this.sending) {
                this.isDisabled = true;
                this.sending = true;

                axios.post('/login', this.form)
                    .then((response) => {
                        location.href = this.redirectUrl;
                    })
                    .catch((error) => {
                        if (error.response.data.errors) {
                            this.setErrors(error.response.data.errors);
                        }
                    })
                    .finally(() => {
                        this.sending = false;
                    });
            }
        },
        propagateFormState(dirty) {
            if (!this.sending) {
                this.isDisabled = !dirty;
            }
        },
        async validate() {
            return await this.$refs.form.validate();
        },
        setErrors(errors) {
            this.$refs.form.setErrors(errors);
        },
        reset() {
            return this.$refs.form.reset();
        },
        postData() {
            return this.form;
        }
    }
}
</script>
