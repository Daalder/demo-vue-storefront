<template>
    <div class="relative inline-block text-left">
        <div>
            <span class="rounded-md shadow-sm" @focusin="open" @focusout="close">
                <slot name="button"></slot>
            </span>
        </div>

        <transition name="dropdown">
            <div v-if="isOpen" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg z-10">
                <slot></slot>
            </div>
        </transition>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                isOpen: false
            }
        },
        mounted() {
        },
        methods: {
            toggle() {
                if (this.isOpen) {
                    this.close()
                } else {
                    this.open()
                }
            },
            open() {
                this.isOpen = true
            },
            close() {
                this.isOpen = false
            }
        }
    }
</script>
