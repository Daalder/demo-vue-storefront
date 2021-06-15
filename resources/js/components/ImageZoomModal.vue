<template>
    <div v-show="isOpen" class="lg:fixed z-10 inset-0" id="imageviewer-modal">
        <div class="bg-white w-full min-h-screen">
            <div class="w-full flex">
                <div class="hidden lg:flex flex-shrink-0 w-64 lg:w-48 p-4 flex-col">
                    <a
                        v-for="image in images"
                        v-scroll-to="{ el: '#desktopZoomSlide' + image.id, container: '#desktopZoomSwiper', x: false, y: true, easing: 'ease-in-out', duration: 200 }"
                        class="mb-4 bg-contain bg-no-repeat bg-center cursor-pointer"
                    >
                        <img :src="preview(image)" class="w-full h-auto" />
                    </a>
                </div>

                <div
                    @click="desktopToggleZoom()"
                    class="flex-grow min-h-screen h-fit-content lg:h-screen lg:overflow-y-auto cursor-zoom-out"
                    id="desktopZoomSwiper"
                >
                    <img
                        v-for="image in images"
                        class="w-full h-auto"
                        :id="'desktopZoomSlide' + image.id"
                        :src="extraLarge(image)"/>
                </div>
            </div>
        </div>

        <div
            @click="toggleZoom()"
            class="flex fixed right-5 top-3 w-24 h-10 rounded-full items-center justify-center bg-white cursor-pointer shadow hover:shadow-md"
        >
            <span class="leading-snug mr-1">Sluiten</span>
            <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
    </div>
</template>

<script>
import {getImageSrc} from "../services/getImageSrc";

export default {
    data() {
        return {
            images: [],
            isOpen: false,
        }
    },
    mounted() {
        window.bus.$on('open-image-zoom-modal', this.openModal)
    },
    methods: {
        isDesktop() {
            return window.outerWidth >= 1024;
        },
        openModal(media) {
            this.images = media;
            this.toggleZoom();
        },
        toggleZoom() {
            this.isOpen = !this.isOpen;

            if(this.isDesktop()) {
                if(this.isOpen) {
                    document.querySelector('html').style.overflowY = 'hidden';
                } else {
                    document.querySelector('html').style.overflowY = 'auto';
                }
            } else {
                let thisElement = document.getElementById('imageviewer-modal');
                let appChildren = document.getElementById('app').children;

                if(this.isOpen) {
                    for(let i = 0; i < appChildren.length; i++) {
                        if(appChildren[i] !== thisElement) {
                            appChildren[i].style.display = 'none';
                        }
                    }
                } else {
                    for(let i = 0; i < appChildren.length; i++) {
                        if(appChildren[i] !== thisElement) {
                            appChildren[i].style.display = 'unset';
                        }
                    }
                }
            }

            window.bus.$emit('image-zoom-modal-closed');
        },
        desktopToggleZoom() {
            if(this.isDesktop()) {
                this.toggleZoom();
            }
        },
        preview(media) {
            return getImageSrc(media, 'preview');
        },
        extraLarge(media) {
            return getImageSrc(media, 'default_2x');
        },
    }
}
</script>
