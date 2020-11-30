<template>
    <div v-if="show" class="md:hidden">
        <div class="fixed inset-0 flex z-40">
            <transition name="fade" @after-leave="show = false">
                <div v-if="isOpen" class="fixed inset-0" @click="close">
                    <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
                </div>
            </transition>
            <transition name="slide-left">
                <div v-if="isOpen"
                     class="relative flex-1 flex flex-col w-full bg-white">
                    <div class="absolute top-0 right-0 -mr-14 p-1">
                        <button
                            class="flex items-center text-gray-500 justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600"
                            aria-label="Close sidebar" @click="close">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <slot></slot>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                show: false,
                isOpen: false
            }
        },
        mounted() {
            window.bus.$on('open-sidebar', this.open)
            window.bus.$on('close-sidebar', this.close)
        },
        methods: {
            async open() {
                this.show = true
                await this.$nextTick()
                this.isOpen = true
            },
            close() {
                this.isOpen = false
            }
        }
    }
</script>
