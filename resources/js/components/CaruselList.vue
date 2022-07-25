<template>
    <div class="carusel-body">
        <div class="v-carusel-list" :style="{ 'margin-left': '-' + (100 * indexPhoto) + '%' }">
            <CaruselCard v-for="photo in photos" :key="photo.id" :photo="photo" />
            <i v-if="this.photos.length > 1" @click="prevPhoto" class="fa fa-arrow-left btn-prev btn-carusel" style="z-index:10000"></i>
            <i v-if="this.photos.length > 1" @click="nextPhoto" class="fa fa-arrow-right btn-next btn-carusel" style="z-index:10000"></i>
            <div class="setka" :style="{ 'width': + (100 * (indexPhoto + 1)) + '%' }" style="z-index:9999"
                @mouseup="mouseupPhoto" @mousedown="mousedownPhoto" @mousemove="mousemovePhoto"></div>
        </div>
    </div>
</template>

<script>
import CaruselCard from './CaruselCard.vue'
export default {
    props: {
        photos: {
            type: Array,
            default: () => []
        },
    },
    data: () => ({
        indexPhoto: 0,
        mousePhoto: false,
    }),
    methods: {
        prevPhoto() {
            if (this.indexPhoto > 0) {
                this.indexPhoto--;
            }
        },
        nextPhoto() {

            if (this.indexPhoto < this.photos.length - 1) {
                this.indexPhoto++;
            }
        },
        mouseupPhoto() {
            this.mousePhoto = false;
        },
        mousedownPhoto() {
            this.mousePhoto = true;
        },
        mousemovePhoto(e) {
            if (this.mousePhoto == true) {
                this.mousePhoto = false;
                if (e.movementX > 0) {
                    this.prevPhoto();
                }
                else if (e.movementX < 0) {
                    this.nextPhoto();
                }
            }
        },
    },
    components: { CaruselCard }
}
</script>

<style lang="scss" scoped>
.carusel-body {
    width: 300px;
    overflow: hidden;
    margin: 0 auto;
    position: relative;
}

@media (min-width: 1000px) {
    .carusel-body {
        width: 500px;
    }
}




.v-carusel-list {
    display: flex;
    transition: all ease .5s;
}

.btn-prev {
    position: absolute;
    left: 0;
    margin-left: 3px !important;
    top: 50%;
}

.btn-next {
    position: absolute;
    right: 0;
    margin-right: 3px !important;
    top: 50%;
}

.btn-carusel {
    font-size: 30px;
    background: none;
    border: none;
    padding: 0;
    margin: 0;
    cursor: pointer;
    color: white;
    background-color: #6c757de8;
    padding: 5px 5px;
    border-radius: 7px;
    transition: background-color ease .5s;
}

.btn-carusel:hover {
    background-color: #595f64;
    transition: background-color ease .5s;
}

.setka {
    width: 100%;
    height: 100%;
    background-color: #0ff0;
    position: absolute;
}
</style>