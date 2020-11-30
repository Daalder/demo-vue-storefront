<template>
    <span class="inline-flex rounded-md shadow-sm">
        <a v-if="href" :href="href" @click="click"
           class="inline-flex items-center px-6 py-3 border border-transparent text-base leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150"
           :class="styles()">
            <slot/>
        </a>
        <button v-else :type="type" @click="click" :disabled="disabled"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150"
                :class="styles()">
            <slot/>
        </button>
    </span>
</template>
<script>
export default {
    props: {
        href: {
            type: String,
            default: ''
        },
        disabled: {
            type: Boolean,
            default: false
        },
        type: {
            type: String,
            default: 'button'
        },
        color: {
            type: String,
            default: 'primary' // primary | secondary | light
        }
    },
    data() {
        return {}
    },
    mounted() {

    },
    watch: {},
    methods: {
        styles() {
            if (this.disabled) {
                return 'text-gray-300 bg-gray-700 hover:bg-gray-600';
            }

            if (this.color === 'secondary') {
                return 'text-indigo-700 bg-indigo-100 hover:bg-indigo-50 focus:border-indigo-300 focus:shadow-outline-indigo active:bg-indigo-200';
            } else if (this.color === 'light') {
                return 'text-gray-700 bg-white hover:text-gray-500 focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50';
            }

            return 'text-white bg-indigo-600 hover:bg-indigo-500 focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700';
        },
        click(e) {
            this.$emit('click', e)
        }
    }
}
</script>
