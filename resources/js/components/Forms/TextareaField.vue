<template>
    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 py-2 sm:py-4">
        <label :for="id" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
            {{ label || placeholder }}
        </label>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                <validated-field :name="name || id" class="relative" :required="required">
                    <div class="max-w-lg flex rounded-md shadow-sm">
                        <textarea :id="id" :name="name || id" :value="value" ref="input" @input="updated" @blur="blur"
                                  :placeholder="placeholder" :autocomplete="autocomplete" :required="required"
                                  :rows="rows" class="form-textarea block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
                    </div>
                </validated-field>
                <p v-if="description">{{ description }}</p>
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
        rows: {
            type: Number,
            default: 4
        },
        description: String,
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
        },
        blur(e) {
            this.$emit('blur', e);
        }
    }
}
</script>
