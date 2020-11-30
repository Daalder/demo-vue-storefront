<template>
    <transition name="fade">
        <div class="bg-daalder-darker-blue text-white -mx-4 p-4" v-if="hasAny">
            <div class="flex justify-between cursor-pointer mb-4">
                <span class="font-bold">Your filters</span>
                <div class="filter-section--header--controls">
                <span class="underline cursor-pointer" @click.stop="deselectAll">
                    Remove all
                </span>
                </div>
            </div>

            <div class="flex" v-for="filter in activeFilters">
                <div class="pr-2 pl-1 flex items-center cursor-pointer" @click="remove(filter)">
                    <i class="text-sm material-icons">clear</i>
                </div>
                <div class="flex items-center">
                    <div>
                        <span class="font-bold">{{ filter.name }}</span>
                        <span>-</span>
                        <span v-if="isRangeFilter(filter)">{{ filter.values.min }} - {{ filter.values.max }}</span>
                        <span v-else>{{ filter.value }}</span>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: "active-filters",
    props: ['facets', 'name', 'values'],
    data() {
        return {
            activeFilters: [],
        }
    },
    mounted() {
        bus.$on('filtersChanged', (filters) => {
            this.activeFilters = filters.flatMap((filter) => {
                if (this.isRangeFilter(filter))
                    return [filter];

                let filters = [];
                for (let activeOption of filter.values) {
                    filters.push(Object.assign({}, filter, {value: activeOption, values: undefined}))
                }
                return filters;
            });
        });
    },
    computed: {
        hasAny() {
            return this.activeFilters.length > 0;
        },
        anyOptionChecked() {
            return true;
        }
    },
    methods: {
        isRangeFilter(filter) {
            return filter.key === 'prices';
        },
        remove(filter) {
            bus.$emit('clear-filter-' + filter.id, filter.value);
        },
        deselectAll() {
            bus.$emit("clear-all-filters");
        }
    }
}
</script>
