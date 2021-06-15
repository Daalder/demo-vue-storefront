<template>
    <div id="filter" class="w-full">
        <div>
            <span class="text-xl">Filters</span>
        </div>
        <active-filters></active-filters>

        <ranges v-for="(section, index) in ranges"
                v-bind.sync="section"
                @updated="filterUpdated"
                :key="`range-${index}`"></ranges>

        <filter-section v-bind.sync="section"
                        @updated="filterUpdated"
                        v-for="(section, index) in checkboxes" :key="`filter-${index}`">
            <template slot-scope="{filter}">
                <checkboxes :options="filter.options"
                            :initialy-selected="filter.selected"
                            @update="filter.update"></checkboxes>
            </template>
        </filter-section>

        <filter-section v-bind.sync="section"
                        @updated="filterUpdated"
                        v-for="(section, index) in colorPickers" :key="`color-picker-${index}`">
            <template slot-scope="{filter}">
                <color-picker :options="filter.options"
                              :initialy-selected="filter.selected"
                              @update="filter.update"></color-picker>
            </template>
        </filter-section>

        <filter-section v-bind.sync="section"
                        @updated="filterUpdated"
                        v-for="(section, index) in radioButtons" :key="`sort-${index}`">
            <template slot-scope="{filter}">
                <radio-buttons :options="filter.options"
                               :initialy-selected="filter.selected"
                               @update="filter.update"></radio-buttons>
            </template>
        </filter-section>
        <div class="side-navigation-overlay"></div>
    </div>
</template>
<script>
import Buttons from './Facets/Button';
import FilterSection from './Facets/FilterSection';
import Checkboxes from "./Facets/Checkboxes";
import ColorPicker from "./Facets/ColorPicker";
import RadioButtons from './Facets/RadioButtons';
import ActiveFilters from './ActiveFilters';
import Ranges from './Facets/Range';

var _docHeight = (document.height !== undefined) ? document.height : document.body.offsetHeight;

$('.elementor-widget-product-filters')
    .closest('.elementor-top-column')
    .addClass('side-navigation')
    .css('height', _docHeight + 180); // Add header height

export default {
    name: 'product-filters',
    components: {
        'buttons': Buttons,
        'ranges': Ranges,
        FilterSection,
        RadioButtons,
        Checkboxes,
        ColorPicker,
        ActiveFilters
    },
    data: function () {
        return {
            currentElement: null,
            filterSections: null,
        }
    },
    computed: {
        checkboxes() {
            if (!this.filterSections)
                return [];

            return this.filterSections.filter(section => section.type === 'checkbox');
        },
        radioButtons() {
            if (!this.filterSections)
                return [];

            return this.filterSections.filter(section => section.type === 'sort');
        },
        colorPickers() {
            if (!this.filterSections)
                return [];
            return this.filterSections.filter(section => section.type === 'color-picker');
        },
        ranges() {
            if (!this.filterSections)
                return [];

            return this.filterSections
                .filter(section => section.type === 'range')
                .filter(section => section.values.min < section.values.max);
        }
    },
    mounted: function () {
        if(window.app.aggregations) {

            this.render(window.app.aggregations);
        }
        bus.$on('aggregationsFetched', this.render);
    },
    methods: {
        render(aggregations){
            this.filterSections = aggregations;

            for (let section of this.filterSections) {
                switch (section.type) {
                    case "select":
                    case "multiselect":
                    case "number":
                        section.type = 'checkbox';
                        break;
                }

                if (section.key == 'prices') {
                    section.name = 'Price'
                }
            }
            this.filterUpdated();
        },
        filterUpdated() {
            let filters = this.getActiveFilters();
            bus.$emit('filtersChanged', filters);
        },
        getActiveFilters() {
            let filters = [];

            this.filterSections.forEach(function (section) {
                switch (section.type) {
                    case "checkbox":
                    case "button":
                        if (typeof section.values === "undefined" || section.values.length === 0) {
                            break;
                        }

                        filters.push({
                            id: section.id,
                            key: section.key,
                            name: section.name,
                            values: section.values
                        });
                        break;
                    case "range":
                        if (Math.floor(section.facets.min) !== section.values.min ||
                            Math.ceil(section.facets.max) !== section.values.max) {
                            if (section.values.min < section.values.max) {
                                filters.push({
                                    id: section.id,
                                    key: section.key,
                                    name: section.name,
                                    values: section.values
                                });
                            }
                        }
                        break;
                }
            });

            return filters;
        },
    }
};
</script>
