<template>
    <div>
        <select-field :id="field_prefix + 'country_code'" label="Country" v-model="address.country_code" autocomplete="country" placeholder="Select a country"
                      :options="{'NL': 'Netherlands', 'BE': 'Belgium'}">
        </select-field>

        <text-field :id="field_prefix + 'postalcode'" v-model="address.postalcode"
                    :label="address.country_code === 'BE' ? 'Postnummer' : 'Postcode'"
                    :placeholder="address.country_code === 'BE' ? '1111' : '1111AB'"
                    autocomplete="postal-code" required/>

        <text-field :id="field_prefix + 'housenumber'" v-model="address.housenumber"
                    label="Housenumber" placeholder="12" autocomplete="off" required/>

        <text-field :id="field_prefix + 'addition'" v-model="address.addition"
                    label="Addition" placeholder="A" autocomplete="off"/>

        <text-field :id="field_prefix + 'street'" v-model="address.street"
                    label="Street" placeholder="" autocomplete="street-address" required/>

        <text-field :id="field_prefix + 'city'" v-model="address.city"
                    label="City" placeholder="" autocomplete="locality" required/>

    </div>
</template>
<script>
import {ValidationProvider} from 'vee-validate';
import ValidatedField from '../ValidatedField';
import SelectField from "../Forms/SelectField";

export default {
    components: {
        SelectField,
        ValidatedField, ValidationProvider
    },
    props: {
        address: {
            type: Object,
            default: () => ({
                country_code: '',
                postalcode: '',
                housenumber: '',
                addition: '',
                street: '',
                city: '',
            })
        },
        field_prefix: {
            type: String,
            default: "",
        }
    },
    data() {
        return {}
    },

    computed: {},
}
</script>
