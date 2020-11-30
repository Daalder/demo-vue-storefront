<template>
    <div>
        <div @click="toggle">
            <span class="tw-flex tw-items-center">
                <i class="material-icons tw-text-lg" :class="{'rotate-180': !isVisible, 'success': greenIcon}">keyboard_arrow_up</i>
                <slot name="title">
                    <span>{{ title }}</span>
                </slot>
            </span>
        </div>
        <div v-show="isVisible">
            <slot name="content">
                {{ content }}
            </slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'collapse',
        props: {
            title: {
                default: ''
            },
            content: {
                default: ''
            },
            visible: {
                default: false
            },
            greenIcon: {
                default: false
            },
        },
        data() {
            return {
                isVisible: false
            }
        },
        mounted() {
            this.isVisible = this.visible
        },
        methods: {
            toggle() {
                this.isVisible = !this.isVisible;
            }
        }
    }
</script>
