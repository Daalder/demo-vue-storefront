<template>
    <div v-if="show" class="fixed z-10 inset-0 overflow-y-auto">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:pt-0 lg:p-0">
            <transition name="fade" @after-leave="closed" @after-enter="opened">
                <div v-if="isOpen" @click="close" class="fixed inset-0 transition-opacity">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>
            </transition>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

            <transition name="modal">
                <div v-if="isOpen"
                     class="inline-block align-middle bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all w-full sm:my-8 sm:max-w-4xl sm:p-6"
                     role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                    <a v-if="closeButton" @click="close"
                       class="absolute right-0 top-0 w-10 h-10 text-center cursor-pointer">
                        <span class="material-icons leading-10">close</span>
                    </a>

                    <slot name="content"></slot>
                    <div v-if="$slots.buttons" class="mt-5 sm:mt-6">
                        <slot name="buttons"></slot>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        name: {
            type: String
        },
        closeButton: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            show: false,
            isOpen: false
        }
    },
    mounted() {
        bus.$on('open-' + this.name, this.open);
        bus.$on('close-' + this.name, this.close);
    },
    watch: {},
    methods: {
        async open() {
            this.$emit('opening');
            this.show = true;
            await this.$nextTick();
            this.isOpen = true;
        },
        opened() {
            this.$emit('opened');
        },
        close() {
            this.isOpen = false;
            this.$emit('closing');
        },
        closed() {
            this.show = false;
            this.$emit('closed');
        }
    }
}
</script>
