<template>
    <nav class="container m-auto">
        <ul class="hidden md:flex border border-gray-300 rounded-md divide-y divide-gray-300 md:divide-y-0">
            <li v-for="(text, index) in steps" class="relative md:flex-1 md:flex">
                <!-- Completed Step -->
                <div v-if="index < step" class="group flex items-center w-full">
                    <div class="px-6 py-4 flex-col lg:flex-row flex items-center text-sm leading-5 font-medium lg:space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-indigo-600 rounded-full group-hover:bg-indigo-800 transition ease-in-out duration-150">
                            <!-- Heroicon name: check -->
                            <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <p class="text-sm leading-5 font-medium text-gray-900 whitespace-no-wrap pt-2 lg:pt-0">{{ text }}</p>
                    </div>
                </div>

                <!-- Current Step -->
                <div v-if="index === step" class="px-6 py-4 flex-col lg:flex-row flex items-center text-sm leading-5 font-medium lg:space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border-2 border-indigo-600 rounded-full">
                        <p class="text-indigo-600">{{ index + 1 }}</p>
                    </div>
                    <p class="text-sm leading-5 font-medium text-indigo-600 whitespace-no-wrap pt-2 lg:pt-0">{{ text }}</p>
                </div>

                <!-- Upcoming Step -->
                <div v-if="index > step" class="group flex items-center w-full">
                    <div class="px-6 py-4 flex-col lg:flex-row flex items-center text-sm leading-5 font-medium lg:space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center border-2 border-gray-300 rounded-full group-hover:border-gray-400 transition ease-in-out duration-150">
                            <span class="text-gray-500 group-hover:text-gray-900 transition ease-in-out duration-150">{{ index + 1 }}</span>
                        </div>
                        <p class="text-sm leading-5 font-medium text-gray-500 group-hover:text-gray-900 transition ease-in-out duration-150 whitespace-no-wrap pt-2 lg:pt-0">{{ text }}</p>
                    </div>
                </div>

                <!-- > icon -->
                <div v-if="index < steps.length - 1" class="hidden md:block absolute top-0 right-0 h-full w-5">
                    <svg class="h-full w-full text-gray-300" viewBox="0 0 22 80" fill="none" preserveAspectRatio="none">
                        <path d="M0 -2L20 40L0 82" vector-effect="non-scaling-stroke" stroke="currentcolor" stroke-linejoin="round"/>
                    </svg>
                </div>
            </li>
        </ul>
        <div class="flex justify-center">
            <p v-for="(text, index) in steps" v-if="index === step"
               class="md:hidden text-sm leading-5 font-medium mr-4">
                {{ text }}
            </p>
            <ul class="flex md:hidden items-center space-x-5">
                <template v-for="(text, index) in steps">
                    <!-- Completed Step -->
                    <li v-if="index < step">
                        <a href="#" class="block w-2.5 h-2.5 bg-indigo-600 rounded-full hover:bg-indigo-900 focus:bg-indigo-900"></a>
                    </li>

                    <!-- Current Step -->
                    <li v-if="index === step">
                        <div class="relative flex items-center justify-center">
                        <span class="absolute w-5 h-5 p-px flex">
                            <span class="w-full h-full rounded-full bg-indigo-200"></span>
                        </span>
                            <span class="relative block w-2.5 h-2.5 bg-indigo-600 rounded-full"></span>
                        </div>
                    </li>

                    <!-- Upcoming Step -->
                    <li v-if="index > step">
                        <a href="#" class="block w-2.5 h-2.5 bg-gray-200 rounded-full hover:bg-gray-400 focus:bg-gray-400"></a>
                    </li>
                </template>
            </ul>
        </div>
    </nav>
</template>

<script>

export default {
    props: {
        step: {
            type: Number,
            default: 0
        }
    },
    mixins: [],
    data: function() {
        return {
            steps: [
                'Personal data',
                'Shipping',
                'Payment',
                'Confirmation',
                'Thanks',
            ]
        }
    },
    mounted() {

    },
    methods: {},
}
</script>
