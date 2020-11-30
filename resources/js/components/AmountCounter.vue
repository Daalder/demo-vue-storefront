<template>
    <div>
        <span v-if="withLabel" class="font-bold">Amount</span>
        <div class="flex flex-row items-center">
            <a href="#" class="rounded-l border border-gray-500 bg-white h-10 w-10 flex items-center justify-center"
               @click="decrement">
                <i class="material-icons text-lg">remove</i>
            </a>
            <div class="border-t border-b border-gray-500 bg-gray-300 flex items-center justify-center h-10">
                <input id="amount"
                       v-model="count"
                       @change.prevent="changed"
                       type="text"
                       min="1" step="1"
                       class="w-10 text-center bg-transparent h-full outline-none px-2">
            </div>
            <a href="#" class="rounded-r border border-gray-500 bg-white h-10 w-10 flex items-center justify-center"
               @click="increment">
                <i class="material-icons text-lg">add</i>
            </a>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        amount: {
            type: Number,
            default: 1
        },
        withLabel: {
            type: Boolean,
            default: true
        }
    },
    data() {
        return {
            count: null
        }
    },
    mounted() {
        this.count = this.amount
    },
    watch: {
        count: function(count, prev) {
            // component just loaded
            if (prev === null) {
                return;
            }

            // still typing
            if (!Number.isInteger(count)) {
                return;
            }

            // value hasn't changed
            if (prev === count) {
                return;
            }

            // emit the changed count
            this.$emit('update:amount', count);
            this.$emit('change', count);
        }
    },
    methods: {
        increment() {
            this.count++
        },
        decrement() {
            this.count = this.count > 1 ? this.count - 1 : 1
        },
        changed() {
            this.count = parseInt(this.count);
            if (isNaN(this.count)) {
                this.count = 1
            }
        }
    }
}
</script>
