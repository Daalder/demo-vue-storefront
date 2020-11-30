<template>
    <div class="relative border-b border-gray-500">
        <div class="flex justify-between items-center py-4" @click="collapsed = !collapsed">
            <div class="font-bold text-md">
                <span>{{ name }}</span>
                <span v-if="!collapsed" class="font-thin text-sm ml-2">
                    {{ localValues.min }} - {{ localValues.max }}
                </span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-daalder-dark-blue underline cursor-pointer" @click.stop="reset" v-if="isSet">
                    Reset
                </span>
                <i class="material-icons" v-if="collapsed">add</i>
                <i class="material-icons" v-else>remove</i>
            </div>
        </div>

        <div v-show="!collapsed">
            <div :id="id" class="w-full"></div>
        </div>
    </div>

</template>

<script>
import noUiSlider from 'nouislider';
import 'nouislider/distribute/nouislider.css';

export default {
    name: "range",
    props: ['facets', 'values', 'name', 'id'],
    data() {
        return {
            collapsed: true,
            slider: null,
            localValues: {
                min: 0,
                max: 0,
            }
        }
    },
    computed: {
        isSet() {
            return this.localValues.min > this.min || this.localValues.max < this.max;
        },
        max() {
            return Math.ceil(this.facets.max);
        },
        min() {
            return Math.floor(this.facets.min);
        }
    },
    created() {
        this.localValues.min = this.values.min;
        this.localValues.max = this.values.max;
    },
    mounted() {
        this.slider = document.getElementById(this.id);

        noUiSlider.create(this.slider, {
            start: [this.localValues.min, this.localValues.max],
            connect: true,
            range: {
                'min': this.min,
                'max': this.max
            },
            step: 1,
            format: {
                to: function (value) {
                    return Math.round(value);
                },
                from: function (value) {
                    return value;
                }
            }
        });

        this.slider.noUiSlider.on('update', (values, handle) => {
            if (handle === 0) {
                this.localValues.min = values[0];
            } else {
                this.localValues.max = values[1];
            }
        });
        this.slider.noUiSlider.on('change', (values, handle) => {
            this.emitUpdated(true);
        });

        bus.$on('clear-all-filters', this.reset.bind(this));
        bus.$on('clear-filter-' + this.id, this.reset.bind(this));
    },
    methods: {
        reset() {
            this.localValues.min = this.min;
            this.localValues.max = this.max;
            this.emitUpdated(false);
        },
        emitUpdated(updatedFromSlider) {
            if (!updatedFromSlider) {
                this.slider.noUiSlider.set([this.localValues.min, this.localValues.max]);
            }

            this.$emit('update:values', {min: this.localValues.min, max: this.localValues.max});
            this.$emit('updated');
        }
    }
}
</script>

<style scoped lang="scss">
.range-input-wrapper {
    display: flex;
    background: #edf2f0;

    input {
        height: 40px;
    }
}

.euro-symbol-wrapper {
    display: flex;
    align-items: center;
    margin: 2px;
    padding: 0 0.55em;
    background: white;
    font-size: 14px;
}
</style>
