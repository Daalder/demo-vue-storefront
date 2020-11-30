<template>
    <ul>
        <li v-for="(selected, text) in list"
            class="flex items-center h-6 font-bold"
            :class="{'text-green-700': selected}">
            <span class="material-icons text-xl mr-2">
                check_circle_outline
            </span>
            <span class="">{{ text }}</span>
        </li>
    </ul>
</template>
<script>
export default {
    props: {
        list: {
            type: Object,
            default: {}
        }
    },
    data() {
        return {}
    },
    mounted() {

    },
    watch: {},
    methods: {}
}
</script>
