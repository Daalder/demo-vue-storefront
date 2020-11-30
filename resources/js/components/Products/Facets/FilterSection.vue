<template>
    <div class="relative border-b border-gray-500">
        <div class="flex justify-between items-center cursor-pointer pt-4 pb-2" @click="collapsed = !collapsed">
            <div class="flex font-bold text-md">
                <span>{{ name }}</span>
            </div>
            <div class="flex items-center">
                <span class="mr-2 text-daalder-dark-blue underline cursor-pointer" @click.stop="deselectAll"
                      v-if="anyOptionChecked">
                    Remove all
                </span>
                <i class="material-icons" v-if="collapsed">add</i>
                <i class="material-icons" v-else>remove</i>
            </div>
        </div>

        <transition name="dropdown">
            <div v-show="!collapsed" class="mb-2">
                <slot :filter="{
                        options: visibleOptions,
                        selected: values,
                        update: updateFilter.bind(this)
                    }">
                </slot>

                <template v-if="hasExtraOptions">
                    <div class="flex items-center text-daalder-dark-blue underline-hover cursor-pointer mb-2 -ml-1"
                         @click="toggleOptionsVisibility">
                        <i slot="extra" class="material-icons">
                            {{ showAllOptions ? 'keyboard_arrow_up' : 'keyboard_arrow_down' }}
                        </i>
                        <span>{{ showAllOptions ? 'Show less options' : 'Show more options' }}</span>
                    </div>
                </template>
            </div>
        </transition>
    </div>
</template>

<script>
import noUiSlider from 'nouislider';
import 'nouislider/distribute/nouislider.css';


export default {
    name: "filter-section",
    props: ['facets', 'name', 'values', 'id'],
    data() {
        return {
            minVisibleOptions: 4,
            showAllOptions: false,
            collapsed: true,
            anyOptionChecked: false
        }
    },
    mounted() {
        bus.$on('clear-all-filters', this.deselectAll.bind(this));
        bus.$on('clear-filter-' + this.id, this.deselect.bind(this));
    },
    computed: {
        orderedFacets() {
            return collect(this.facets)
                .sortBy('name')
                .filter(option => option.name !== '')
                .all()
        },
        visibleOptions() {
            if (this.showAllOptions)
                return this.orderedFacets;

            return this.orderedFacets.slice(0, this.minVisibleOptions);
        },
        hasExtraOptions() {
            return this.orderedFacets.length > this.minVisibleOptions;
        },
    },
    methods: {
        updateFilter({selected, anyOptionChecked}) {
            this.anyOptionChecked = anyOptionChecked;
            this.$emit('update:values', selected);
            this.$emit('updated');
        },

        toggleOptionsVisibility() {
            this.showAllOptions = !this.showAllOptions;
        },
        deselectAll() {
            this.$emit('deselect-all');
        },
        deselect(option) {
            this.$emit('deselect', option);
        }
    }
}
</script>
