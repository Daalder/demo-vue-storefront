<template>
    <validation-provider :name="name" :rules="rules ? rules : (required ? 'required' : '')" ref="provider"
                         v-slot="{errors, classes}" class="validated-field" tag="div">
        <div :class="getClasses(classes)" :style="propagateErrors(errors)">
            <slot></slot>
        </div>
        <div class="flex flex-col errors-wrapper" v-if="showErrors">
            <span class="validation-error block" v-for="error of errors">{{ error }}</span>
            <span v-if="name === 'email' && errors.length">
                <a href="/login" class="underline">Go to login!</a>
            </span>
        </div>
    </validation-provider>
</template>
<script>
import {ValidationProvider} from 'vee-validate';

export default {
    components: {
        ValidationProvider
    },
    props: {
        showErrors: {
            type: Boolean,
            default: true
        },
        inputWrapperClasses: {
            type: String,
            default: ""
        },
        required: {
            type: Boolean,
            default: true
        },
        rules: {
            type: Object,
            default: null
        },
        name: '',
    },
    methods: {
        getClasses(stateClasses) {
            let classes = {
                'validation-wrapper': true,
                'valid': stateClasses.valid,
                'invalid': stateClasses.invalid
            };
            this.inputWrapperClasses.split(" ").forEach(cssClass => classes[cssClass] = true);
            return classes;
        },
        propagateErrors(errors) {
            this.$emit('errors-update', errors);
            return "";
        }
    }
}
</script>
