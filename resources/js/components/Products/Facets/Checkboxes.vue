<template>
    <ul>
        <li v-for="checkbox in options">
            <div class="">
                <p-check class="p-icon default-input p-smooth" color="success-o"
                         :value="checkbox.name"
                         v-model="selected">
                    <i slot="extra" class="icon material-icons">done</i>
                    <span class="font-light">
                        {{ checkbox.name }}
                    </span>
                </p-check>
            </div>
        </li>
    </ul>
</template>

<script>
export default {
    name: "checkboxes",
    props: ['options', 'initialySelected'],
    data() {
        return {
            selected: []
        }
    },
    mounted() {
        if (this.initialySelected && this.initialySelected.length > 0)
            this.selected = this.initialySelected;

        this.$parent.$on('deselect-all', this.deselectAll.bind(this));
        this.$parent.$on('deselect', this.deselect.bind(this));
    },
    watch: {
        selected() {
            this.$emit('update', {selected: this.selected, anyOptionChecked: this.anyOptionChecked});
        }
    },
    computed: {
        anyOptionChecked() {
            return this.selected.length > 0;
        }
    },
    methods: {
        deselectAll() {
            this.selected = [];
        },
        deselect(option) {
            let optionIndex = this.selected.indexOf(option);
            if (optionIndex < 0)
                return;

            this.selected.splice(optionIndex, 1);
        },
    }
}
</script>
