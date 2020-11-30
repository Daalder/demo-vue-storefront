<template>
    <ul class="color-picker">
        <li v-for="option in options">
            <div :class="getOptionClasses(option.name)" @click="toggle(option.name)">
                <div class="color-picker--option--outside">
                    <div class="color-picker--option--inside" :style="{background: option.name }"></div>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "color-picker",
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
            toggle(option) {
                let optionIndex = this.selected.indexOf(option);
                if (optionIndex >= 0) {
                    this.selected.splice(optionIndex, 1);
                } else {
                    this.selected.push(option);
                }
            },
            deselectAll() {
                this.selected = [];
            },
            getOptionClasses(option) {
                return {
                    "color-picker--option": true,
                    "clickable": true,
                    "selected": this.isOptionSelected(option)
                }
            },
            isOptionSelected(option) {
                return this.selected.indexOf(option) >= 0;
            }
        }
    }
</script>

<style lang="scss">
    .color-picker {
        display: flex;

        &--option {
            width: 30px;
            height: 30px;
            margin-left: 10px;

            &--outside {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                border: 2px solid #edf2f0;
            }

            &--inside {
                width: 100%;
                height: 100%;
                border-radius: 100%;
                background: gray;

                border: 1px solid white;
            }

            &.selected, &:hover {
                .color-picker--option--outside {
                    border-color: #a8bdb9;
                }
            }
        }
    }
</style>
