<template>
    <div>
        <div class="container-fluid" style="margin: 30px 0;" >
                <FavouriteCard v-for="product in products" :key="product.id" :product="product" 
                :laodFavourites="laodFavourites"></FavouriteCard>
        </div>
    </div>
</template>

<script>
import FavouriteCard from "../components/FavouriteCard";
export default {
    props: {
        products: {
            type: Array,
            default: () => []
        },
        laodFavourites:{},
    },
    components: { FavouriteCard }
}
</script>

<style lang="scss" scoped>
</style>