<template>
    <validation-observer tag="form" v-slot="{dirty, valid}" ref="form" @submit.prevent="submit">
        <span class="hidden" :style="propagateFormState(dirty || valid)"></span>

        <div class="flex flex-col mt-12">
            <text-field id="firstname" v-model="form.firstname" autocomplete="given-name" required placeholder="Firstname"/>
            <text-field id="lastname" v-model="form.lastname" autocomplete="family-name" required placeholder="Lastname"/>
            <text-field id="company_name" v-model="form.company_name" placeholder="Company"/>

            <address-form field_prefix="invoice_" :address="form.invoice"></address-form>

            <switch-field id="different-delivery-address"
                          :label="differentDeliveryAddress ? 'Addresses are different' : 'Addresses are the same'"
                          v-model="differentDeliveryAddress"></switch-field>

            <template v-if="differentDeliveryAddress">
                <address-form field_prefix="delivery_" :address="form.delivery"></address-form>
            </template>

            <text-field id="mobile" v-model="form.mobile" autocomplete="mobile" required placeholder="Phonenumber"/>
            <text-field id="email" v-model="form.email" autocomplete="email" type="email" required placeholder="E-mailadres"/>

            <template v-if="withPassword">
                <text-field id="password" v-model="form.password" type="password" required label="Password"/>
                <text-field id="password_confirmation" v-model="form.password_confirmation" type="password" required label="Password Confirmation"/>
            </template>
        </div>

        <btn type="submit" :disabled="isDisabled" class="mt-6">
            Registreren
        </btn>

    </validation-observer>
</template>
<script>
import {ValidationProvider, ValidationObserver} from 'vee-validate';
import ValidatedField from '../ValidatedField';
import TextField from "../Forms/TextField";
import SwitchField from "../Forms/SwitchField";
import ApiService from "../../services/ApiService";
import BasketRepository from "../../Repositories/BasketRepository";
import CustomerRepository from "../../Repositories/CustomerRepository";

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
        },
        withPassword: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            sending: false,
            isDisabled: false,
            differentDeliveryAddress: false,
            form: {
                firstname: '',
                lastname: '',
                company_name: '',
                email: '',
                mobile: '',
                invoice: {
                    country_code: '',
                    postalcode: '',
                    housenumber: '',
                    addition: '',
                    street: '',
                    city: '',
                },
                delivery: {
                    country_code: '',
                    postalcode: '',
                    housenumber: '',
                    addition: '',
                    street: '',
                    city: '',
                }
            },
            apiService: new ApiService
        }
    },
    beforeMount() {
        this.loadCustomer()
    },
    watch: {},
    methods: {
        async loadCustomer() {
            this.customer = await (new CustomerRepository()).fetch();
            if (this.customer) {
                this.prefillFields();
            }
        },
        async submit() {
            const isValid = await this.validate();

            if (isValid && !this.sending) {
                this.isDisabled = true;
                this.sending = true;

                if (!this.customer) {
                    // Register
                    axios.post('/register', this.postData())
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
                } else {
                    // Edit account
                    await this.apiService.put('v2/customers?includes=company,primaryDeliveryAddress,primaryInvoiceAddress', this.postData())
                        .then((response) => {
                            this.customer = response.data.data;
                            this.sending = false;
                        })
                        .catch((error) => {
                            console.log(error.response);
                            if (error.response.data.errors) {
                                this.setErrors(error.response.data.errors);
                            }
                        })
                        .finally(() => {
                            this.sending = false;
                        });
                }
            }
        },
        async prefillFields() {
            this.form = {
                firstname: this.customer.firstname,
                lastname: this.customer.lastname,
                company_name: this.customer.company ? this.customer.company.contact_name : '',
                email: this.customer.email,
                mobile: this.customer.mobile,
                invoice: {
                    country_code: this.customer.primaryDeliveryAddress.country_code,
                    postalcode: this.customer.primaryDeliveryAddress.postalcode,
                    housenumber: this.customer.primaryDeliveryAddress.street_number,
                    addition: this.customer.primaryDeliveryAddress.street_number_addition,
                    street: this.customer.primaryDeliveryAddress.street,
                    city: this.customer.primaryDeliveryAddress.city,
                },
                delivery: {
                    country_code: this.customer.primaryInvoiceAddress.country_code,
                    postalcode: this.customer.primaryInvoiceAddress.postalcode,
                    housenumber: this.customer.primaryInvoiceAddress.street_number,
                    addition: this.customer.primaryInvoiceAddress.street_number_addition,
                    street: this.customer.primaryInvoiceAddress.street,
                    city: this.customer.primaryInvoiceAddress.city,
                }
            }
            this.differentDeliveryAddress = this.customer.primaryDeliveryAddress.formatted_address != this.customer.primaryInvoiceAddress.formatted_address;

            await this.$nextTick();
            await this.validate();
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
            let data = Object.assign({}, this.form, {
                contact_type: this.form.company_name ? 'company' : 'private'
            });

            if (!this.differentDeliveryAddress) {
                data.delivery = this.form.invoice;
            }

            return data;
        }
    }
}
</script>
