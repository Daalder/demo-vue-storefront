<template>
    <div ref="slider" class="flex flex-col items-center image-viewer">
        <div
            ref="swiper" id="swiper"
            class="w-full flex flex-no-wrap lg:min-h-400"
            :class="{ 'overflow-x-scroll': slides.length > 1 }"
        >
            <template v-for="media in slides">
                <div
                    class="slide flex-shrink-0 flex items-center justify-center"
                    :style="{ width: containerWidth + 'px' }"
                    :id="'slide' + media.id"
                    :ref="'slide' + media.id"
                >
                    <img
                        class="object-cover w-full cursor-zoom-in"
                        @click="toggleZoom()"
                        :ref="'img' + media.id"
                        :src="large(media)"
                        :alt="alt"/>
                </div>
            </template>
        </div>

        <div
            @click="toggleZoom()"
            class="flex lg:hidden w-10 h-10 -mt-14 ml-auto mr-1.5 z-0 rounded-full items-center justify-center bg-white shadow"
        >
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
            </svg>
        </div>

        <div ref="thumbnails" class="mt-4 flex flex-no-wrap" :style="{ width : containerWidth + 'px' }"
             :class="{ 'overflow-x-scroll' : scrollThumbnails }">
            <div v-if="slides.length > 1" class="flex flex-shrink-0 m-auto">
                <a v-for="media in slides"
                   v-scroll-to="{ el: '#slide' + media.id, container: '#swiper', x: true, y: false, easing: 'linear', duration: 1 }"
                   class="w-22 h-22 mx-1 rounded-lg bg-fit bg-no-repeat bg-center cursor-pointer"
                   :style="'background-image:url('+thumbnail(media)+')'">
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import {getImageSrc} from "../services/getImageSrc";

export default {
    props: {
        images: {
            type: Array,
            default: []
        },
        alt: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            containerWidth: undefined,
            scrollThumbnails: false
        }
    },
    mounted() {
        this.updateContainerDimensions();

        window.addEventListener('resize', this.updateContainerDimensions);
        window.bus.$on('image-zoom-modal-closed', () => {

        });
    },
    destroyed() {
        window.removeEventListener('resize', this.updateContainerDimensions);
    },
    computed: {
        slides() {
            return this.images.filter(image => image.type.code === 'image');
        }
    },
    methods: {
        updateContainerDimensions() {
            this.containerWidth = this.$refs['slider'].offsetWidth;
            this.scrollThumbnails = this.$refs['thumbnails'].offsetWidth > this.containerWidth;

            if(this.containerWidth === 0) {
                this.containerWidth = this.$refs['slider'].width;
            }
        },
        toggleZoom() {
            window.bus.$emit('open-image-zoom-modal', this.slides);
        },
        thumbnail(media) {
            return getImageSrc(media, 'thumbnail');
        },
        preview(media) {
            return getImageSrc(media, 'preview');
        },
        large(media) {
            return getImageSrc(media, 'default');
        },
        extraLarge(media) {
            return getImageSrc(media, 'default_2x');
        },
    }
}
</script>
