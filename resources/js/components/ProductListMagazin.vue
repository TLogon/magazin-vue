<template>
    <div>
        <div class="container-fluid">
            <div class="row card-display">
                <ProductCardMagazin v-for="product in products.data" :key="product.id" :product="product">
                </ProductCardMagazin>
            </div>
        </div>
    </div>
</template>

<script>
import ProductCardMagazin from './ProductCardMagazin'

export default {
    props: {
        products: {
            type: Array,
            default: () => []
        },
    },
    components: { ProductCardMagazin }
}
</script>

<style lang="scss" scoped>
</style>