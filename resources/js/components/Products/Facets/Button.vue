<template>
    <div class="collapse filter-section">
        <div class="sidebar-title">{{name}}</div>
        <div class="ck-buttons">
            <label v-for="button in facets" class="checkbox">
                <input type="checkbox"
                       :value="button.name"
                       v-model="localValues"
                       @change="checkboxUpdated" />
                <span>{{ button.name }}</span>
            </label>
        </div>
    </div>
</template>

<script>
    export default {
        name: "buttons",
        props: ['facets','name','values'],
        data() {
            return {
                active: false,
                localValues: []
            }
        },
        mounted(){
            this.localValues = this.values;
        },
        methods: {
            checkboxUpdated(){
                this.$emit('update:values', this.localValues);
                this.$emit('updated');
            }
        }
    }
</script>
