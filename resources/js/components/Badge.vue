<template>
    <span class="inline-flex items-center px-2 py-.5 rounded-full text-sm font-medium leading-5"
          :class="classes">
        <slot></slot>
    </span>
</template>

<script>
export default {
    props: {
        color: {
            type: String,
            default: 'success'
        }
    },
    computed: {
        classes() {
            return {
                'bg-green-100 text-green-800': this.color === 'success',
                'bg-red-100 text-red-800': this.color === 'error',
                'bg-yellow-100 text-yellow-800': this.color === 'warning',
                'bg-blue-100 text-blue-800': this.color === 'info',
                'bg-gray-100 text-gray-800': this.color === 'gray'

            }
        }
    }
}
</script>
