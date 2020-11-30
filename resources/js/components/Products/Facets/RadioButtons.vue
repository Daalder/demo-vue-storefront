<template>
    <ul>
        <li v-for="option in options">
            <div class="checkbox-wrapper" @click="toggle(option.name)">
                <p-radio class="p-default p-round default-input radio-input p-smooth" color="success-o"
                         :value="option.name"
                         v-model="selected">
                    <span class="filter-section--options--value">
                        {{ option.name }}
                    </span>
                </p-radio>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "radio-buttons",
        props: ['options', 'initialySelected'],
        data() {
            return {
                selected: ""
            }
        },
        mounted(){
            if (this.initialySelected && this.initialySelected.length > 0)
                this.selected = this.initialySelected[0];

            this.$parent.$on('deselect-all', this.deselectAll.bind(this));
            this.$parent.$on('deselect', this.deselectAll.bind(this));
        },
        watch: {
            selected() {
                this.$emit('update', {selected: [this.selected], anyOptionChecked: this.anyOptionChecked});
            }
        },
        computed: {
            anyOptionChecked() {
                return !!this.selected;
            }
        },
        methods: {
            toggle(option) {
                if (this.selected === option)
                    this.selected = "";
                else
                    this.selected = option;
            },
            deselectAll() {
                this.selected = "";
            }
        }
    }
</script>
