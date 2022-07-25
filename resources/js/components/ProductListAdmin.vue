<template>
    <table class="table table-hover">
        <thead>
            <tr class="table-dark">
                <th class="dt-head-center" style="width: 50px;">#</th>
                <th class="dt-head-center">Товар</th>
                <th class="dt-head-center" style="width: 120px;">Цена</th>
                <th class="dt-head-center" style="width: 160px;">Действие</th>
            </tr>
        </thead>
        <tbody>
            <ProductCardAdmin v-for="product in products.data" :key="product.id" :product="product" :deleteProduct="deleteProduct"></ProductCardAdmin>
        </tbody>
    </table>
</template>

<script>
import ProductCardAdmin from './ProductCardAdmin'

export default {
    props: {
        products: {
            type: Array,
            default: () => []
        },
        deleteProduct: {}
    },
    components: { ProductCardAdmin }
}
</script>

<style lang="scss" scoped>
</style>