<template>
    <div>
        <div id="cart_product_list" class="container-fluid" style="margin: 30px 0;">
            <CartCard v-for="product in products" :key="product.id" :product="product" :buyProduct="buyProduct" :laodCart="laodCart"></CartCard>
        </div>
    </div>
</template>

<script>
import CartCard from "../components/CartCard";
export default {
    props: {
        products: {
            type: Array,
            default: () => []
        },
        laodCart: {},
        buyProduct: {},
    },
    components: { CartCard }
}
</script>

<style lang="scss" scoped>
</style>