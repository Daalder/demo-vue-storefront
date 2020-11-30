<template>
    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:py-5">
        <label :for="id" class="block text-sm font-medium leading-5 text-gray-700 sm:mt-px sm:pt-2">
            {{ label }}
        </label>
        <div class="mt-1 sm:mt-0 sm:col-span-2">
            <div class="max-w-lg rounded-md shadow-sm sm:max-w-xs">
                <select :id="id" :value="value || '-'" ref="select" @input="updated"
                        class="block form-select w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"
                        :class="{'text-placeholder': !value.length}">
                    <option v-if="placeholder" disabled value="-">{{ placeholder }}</option>
                    <option v-for="(text, v) in options"
                            :key="id+v"
                            :value="v">
                        {{ text }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        value: String,
        id: String,
        label: String,
        placeholder: String,
        options: Object
    },
    data() {
        return {}
    },
    mounted() {

    },
    watch: {},
    methods: {
        updated() {
            this.$emit('input', this.$refs.select.value);
        }
    }
}
</script>
