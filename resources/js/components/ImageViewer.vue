<template>
    <div ref="slider" class="flex flex-col items-center image-viewer">
        <div id="swiper" class="w-full flex flex-no-wrap min-h-400"
             :class="{'overflow-x-scroll': slides.length > 1}">
            <div v-for="media in slides" :id="'slide'+media.id"
                 class="slide flex-shrink-0 flex items-center justify-center"
                 :style="{width: containerWidth+'px'}">
                <img class="m-auto object-cover" :src="large(media)" :alt="alt"/>
            </div>
        </div>
        <div ref="thumbnails" class="mt-4 flex flex-no-wrap" :style="{width: containerWidth+'px'}"
             :class="{'overflow-x-scroll': scrollThumbnails}">
            <div v-if="slides.length > 1" class="flex flex-shrink-0 m-auto">
                <a v-for="media in slides"
                   v-scroll-to="{el: '#slide'+media.id, container: '#swiper', x: true, y: false}"
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
        },
        thumbnail(media) {
            return getImageSrc(media, 'thumbnail');
        },
        large(media) {
            return getImageSrc(media, 'default');
        }
    }
}
</script>
