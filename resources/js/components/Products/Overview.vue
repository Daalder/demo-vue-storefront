<template>
    <div>
        <div v-if="total_items>0 && !loading" id="overview_settings">

            <button @click="openSideBar" type="button" class="flex sm:hidden inline-flex items-center px-4 mb-5 py-2 border border-transparent text-base leading-6 font-medium rounded-md focus:outline-none transition ease-in-out duration-150 text-white bg-indigo-600 hover:bg-indigo-500 focus:border-indigo-700 focus:shadow-outline-indigo active:bg-indigo-700">
                <span class="material-icons pr-2">filter_alt</span> Filter
            </button>
            <div class="flex justify-between">

                <nav class="border-b border-gray-200 px-4 flex items-center justify-between sm:px-0">
                    <span class="-mt-px pb-3 pr-1 border-b-2 border-transparent inline-flex text-sm leading-5 font-medium text-gray-500">
                        Items per page
                    </span>
                    <div class="flex">
                        <a v-for="itemCount in [20,30,50]" href="#"
                           class="-mt-px border-b-2 pb-3 px-4 inline-flex items-center text-sm leading-5 font-medium hover:text-gray-700 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150"
                           :class="items_per_page === itemCount ? 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700' : 'border-transparent text-gray-500 focus:text-gray-700 focus:border-gray-400'"
                           @click="switchPerPage(itemCount)">
                            {{ itemCount }}
                        </a>
                    </div>
                </nav>

                <dropdown>
                    <template #button>
                        <button type="button"
                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-sm leading-5 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800 transition ease-in-out duration-150">
                            Sort
                            <svg class="-mr-1 ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </template>

                    <div class="rounded-md bg-white shadow-xs">
                        <div class="py-1">
                            <a href="#"
                               class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                Price ascending
                            </a>
                            <a href="#"
                               class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                Price descending
                            </a>
                            <a href="#"
                               class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900">
                                Most populair
                            </a>
                        </div>
                    </div>
                </dropdown>
            </div>
        </div>
        <div>
            <p v-if="!loading && products.length === 0">No products found</p>

            <div class="mt-4 grid gap-5 mx-auto xs:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
                <div v-if="loading === true"
                     v-for="index in (this.products.length > 0 ? this.products.length : 5)"
                     :key="index" class="w-full loading-pulse">
                    <div class="h-48 w-full"></div>
                    <div class="h-6 w-3/4 ml-4 mt-4"></div>
                    <div class="h-6 w-1/2 ml-4 mt-2"></div>
                    <div class="h-8 w-1/3 ml-4 mt-4 mb-4"></div>
                </div>
                <product-item v-if="loading === false"
                              v-for="product in products"
                              :product="product" :key="product.id"></product-item>
            </div>
        </div>

        <div v-if="total_items > items_per_page && !loading">
            <nav class="border-t border-gray-200 px-4 flex items-center justify-between sm:px-0 mt-5">
                <div class="w-0 flex-1 flex">
                    <a href="#" @click="switchPage(page - 1)"
                       class="-mt-px border-t-2 border-transparent pt-4 pr-1 inline-flex items-center text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-400 transition ease-in-out duration-150">
                        <svg class="mr-3 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                        Previous
                    </a>
                </div>

                <div class="hidden md:flex">
                    <template v-for="number in navigation_numbers">
                        <a v-if="number" href="#" @click.prevent="switchPage(number)"
                           class="-mt-px border-t-2 pt-4 px-4 inline-flex items-center text-sm leading-5 font-medium hover:text-gray-700 hover:border-gray-300 focus:outline-none transition ease-in-out duration-150"
                           :class="page === number ? 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700' : 'border-transparent text-gray-500 focus:text-gray-700 focus:border-gray-400'">
                            {{ number }}
                        </a>
                        <span v-else
                              class="-mt-px border-t-2 border-transparent pt-4 px-4 inline-flex items-center text-sm leading-5 font-medium text-gray-500">...</span>
                    </template>
                </div>

                <div class="w-0 flex-1 flex justify-end">
                    <a href="#" @click="switchPage(page + 1)"
                       class="-mt-px border-t-2 border-transparent pt-4 pl-1 inline-flex items-center text-sm leading-5 font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-400 transition ease-in-out duration-150">
                        Next
                        <svg class="ml-3 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                  d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </a>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
import Loader from '../Loader.js';
import ApiService from "../../services/ApiService";

export default {
    name: 'product-overview',
    props: {
        api_endpoint: {
            type: String
        },
        second_image: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            loader: null,
            products: [],
            total_items: 4,
            page: 1,
            items_per_page: 30,
            filters: [],
            loading: true,
            aggregationsLoaded: false,
            searchQuery: null,
            aggregations: null,
            apiService: new ApiService,
            quickAddProduct: null,
            store_id: process.env.MIX_STORE_ID
        }
    },
    created() {
        this.loader = new Loader($('.product-overview'));
        this.loader.setFixed();

        const queryString = require('query-string');
        this.searchQuery = queryString.parse(location.search).search;

        if (this.searchQuery && this.searchQuery.indexOf(' ') === -1) {
            this.searchQuery = `*${this.searchQuery}*`;
        }

        this.loadProducts();

        bus.$on('filtersChanged', (filters) => {
            let newFiltersDump = JSON.stringify(filters);
            if (JSON.stringify(this.filters) !== newFiltersDump) {
                this.filters = JSON.parse(newFiltersDump);
                this.page = 1;
                this.loadProducts();
                if (this.aggregationsLoaded) {
                    this.buildUrl();
                }
            }
        });

        $(window).bind('hashchange', this.refreshCurrentAggregation.bind(this));
    },
    computed: {
        max_page() {
            return Math.ceil(this.total_items / this.items_per_page);
        },
        navigation_numbers() {
            let firstPage;
            let length;

            if (this.max_page <= 7) {
                firstPage = 1;
                length = this.max_page;
            } else {
                if (this.page - 3 <= 1) {
                    firstPage = 1;
                } else if (this.page + 3 >= this.max_page) {
                    firstPage = this.max_page - 6;
                } else {
                    firstPage = this.page - 3;
                }

                length = 7;
            }

            let navigation = Array(length).fill(0).map((e, i) => i + firstPage);

            //if page 1 isn't in the set.
            if (navigation.indexOf(1) === -1) {
                navigation = [1, false].concat(navigation.slice(2));
            }

            //if last page isn't in the set
            if (navigation.indexOf(this.max_page) === -1) {
                navigation = navigation.slice(0, 5).concat([false, this.max_page]);
            }

            return navigation;
        }
    },
    methods: {
        toggleSideNavigation: function () {
            const el = document.getElementsByClassName("side-navigation")[0];
            el.classList.add('active');
        },
        switchPerPage: function (perPage) {
            this.items_per_page = perPage;
            this.loadProducts();
        },
        switchPage: function (pageNumber) {
            this.page = pageNumber;
            this.loadProducts();
            $("body, html").animate({
                scrollTop: $("#pagecontent").position().top
            });
        },
        async loadProducts() {
            let data = {};

            this.loading = true;

            if (this.searchQuery) {
                data['filter[name,description,sku,search_aliasses]'] = this.searchQuery;
            }

            data['filter[store_ids]'] = this.store_id;
            data.page = this.page;
            data[`filter[is_published]`] = true;

            if (!this.searchQuery) {
                data[`filter[is_visible_in_overview]`] = true;
            }

            if (this.filters.length > 0) {
                for (let section of this.filters) {
                    let key = `filter[properties.${section.key}]`;

                    if (section.key === 'prices') {
                        key = 'filter[price]';
                    }

                    if (section.values.hasOwnProperty('min')) {
                        data[`${key}[min]`] = section.values.min;
                        data[`${key}[max]`] = section.values.max;
                    } else {
                        data[key] = section.values.join('|');
                    }
                }
            }

            data['per_page'] = this.items_per_page;

            //get products with filter
            await this.apiService.get(this.api_endpoint, {params: data})
                .then((response) => {
                    this.products = response.data.data.products;

                    this.total_items = response.data.meta.total;
                    this.items_per_page = response.data.meta.per_page;

                    if (!this.aggregationsLoaded) {
                        this.aggregationsLoaded = true;
                        this.setAggregations(response.data.data.aggregations.data);
                    }
                    this.loading = false;
                });
        },
        setAggregations(aggregations) {
            for (let section of aggregations) {
                // create empty values for every section
                switch (section.type) {
                    case "number":
                    case "multiselect":
                    case "select":
                    case "checkbox":
                    case "button":
                        section.values = [];
                        for (let facet of section.facets) {
                            facet.id = facet.name.replace(/[aeouijy]/g, '');
                        }
                        break;
                    case "range":
                        section.values = {
                            min: Math.floor(section.facets.min),
                            max: Math.ceil(section.facets.max),
                        };
                        break;
                }

                // the keyword key is reserved and cant be passed to the filter component as a prop
                section.id = section.key.replace(/[aeouijy]/g, '');
            }

            this.aggregations = aggregations;
            this.refreshCurrentAggregation();
        },
        refreshCurrentAggregation() {
            let hash = document.location.hash.substring(1);

            if (hash.length === 0) {
                // No hash, so don't rebuild
                this.onAggregationsFeteched();
                return;
            }

            let filter = window.atob(hash);

            let filterFromHash = null;
            try {
                filterFromHash = JSON.parse(filter);
            } catch (e) {
                // Irrelevant
            }

            if (filterFromHash == null) {
                // Stop on JSON error
                this.onAggregationsFeteched();
                return;
            }

            let aggregationsCollection = collect(this.aggregations);
            for (let sectionKey in filterFromHash) {
                if (!filterFromHash.hasOwnProperty(sectionKey)) {
                    continue;
                }

                let section = aggregationsCollection.firstWhere('id', sectionKey);
                if (section === null) {
                    continue;
                }

                //this is only going to work when all urls stop containing ids
                //section.values = filterFromHash[sectionKey];
                if (filterFromHash[sectionKey].hasOwnProperty('min')) {
                    section.values = {
                        min: filterFromHash[sectionKey].min,
                        max: filterFromHash[sectionKey].max
                    };
                    continue;
                }

                section.values.length = 0;
                for (let value of filterFromHash[sectionKey]) {
                    for (let facet of section.facets) {
                        if (this.matchesAggregationGroup(facet, value)) {
                            section.values.push(facet.name);
                        }
                    }
                }
            }
            this.onAggregationsFeteched();
        },

        onAggregationsFeteched() {
            if (!this.aggregations)
                return;

            bus.$emit('aggregationsFetched', this.aggregations);
            window.app.aggregations = this.aggregations;
        },
        matchesAggregationGroup(facet, value) {
            return facet.id === value
                || facet.name === value
                || facet.id.replace(/ /g, '') === value
                || facet.name.replace(/ /g, '') === value;
        },
        buildUrl() {
            let hash = '';

            let urlFilters = {};
            for (var index in this.filters) {
                if (this.filters.hasOwnProperty(index)) {
                    urlFilters[this.filters[index].id] = this.filters[index].values;
                }
            }

            let json = JSON.stringify(urlFilters);
            if (json != '{}') {
                hash = window.btoa(json);
            }

            let location = window.location.pathname + '#' + hash;
            //todo
            window.history.pushState(null, 'TODO', location);
        },
        openSideBar() {
            window.bus.$emit('open-sidebar');
        }
    }
}
</script>

<style scoped>

</style>
