<template>
    <div class="v-carusel-card">
        <img style="" :src="require('/files/' + photo.photo_link)" />
    </div>
</template>

<script>
export default {
    props: {
        photo: {
            type: Object,
            default: () => { }
        },
    },
    mounted() {

    },
    methods: {

    },
}
</script>

<style lang="scss" scoped>
.v-carusel-card {
    justify-content: center;
    display: flex;
    align-items: center;
}
.v-carusel-card img {
    width: 300px;
    height: inherit;
    max-width: inherit;
}

@media (min-width: 1000px) {
    .v-carusel-card img {
        width: 500px;
    }
}
</style>