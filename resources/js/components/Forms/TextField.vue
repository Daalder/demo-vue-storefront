<template>
    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 py-2 sm:py-4">
        <label :for="id" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
            {{ label || placeholder }}
        </label>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                <validated-field :name="name || id" class="relative" :required="required">
                    <input :id="id" :name="name || id" :type="type" :value="value" ref="input" @input="updated"
                           :placeholder="placeholder" :autocomplete="autocomplete" :required="required"
                           class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                </validated-field>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        value: String,
        id: String,
        name: String,
        label: String,
        placeholder: String,
        autocomplete: String,
        required: {
            type: Boolean,
            default: false
        },
        type: {
            type: String,
            default: 'text'
        },
    },
    data() {
        return {}
    },
    mounted() {

    },
    watch: {},
    methods: {
        updated() {
            this.$emit('input', this.$refs.input.value);
        }
    }
}
</script>
