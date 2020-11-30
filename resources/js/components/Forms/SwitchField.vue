<template>
    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-4">
        <label :for="id" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px">
            {{ label }}
        </label>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                <validated-field :name="name || id" class="relative" :required="required">
                    <input :id="id" :name="name || id" type="checkbox" :checked="value"
                           value="1" ref="input" @input="updated" :required="required"
                           class="form-checkbox h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                </validated-field>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        value: { // checked
            type: Boolean,
            default: false
        },
        id: String,
        name: String,
        label: String,
        required: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {}
    },
    mounted() {

    },
    watch: {},
    methods: {
        updated() {
            this.$emit('input', this.$refs.input.checked);
        }
    }
}
</script>
