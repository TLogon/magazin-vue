<template>
    <div class="row card-favcart">
        <div class="col-auto" style="padding: 0; margin: 0;">
            <img src="/project_files/pixel.png" class="foto-card-favcart"
                :style="{ backgroundImage: product.photo_link == null ? `url(/project_files/nophoto_magazin.png)` : `url(/files/` + product.photo_link + `)` }">
        </div>
        <div class="col" style="padding: 10px;">
            <router-link :to="{ path: '/product/' + product.id }">
                <h6 class="text-card">{{ product.name }}</h6>
            </router-link>
            <p class="product-price">₸ {{ product.price | formatPrice }}</p>
        </div>
        <div class="col-auto" style="padding: 0; margin: 0; display: grid;">
            <button
                @click.prevent="buyProduct(product.id, product.name, product.price, product.section_id, product.photo_link, product.size_1, product.size_2, product.size_3, product.size_4, product.size_5, product.size_6, product.size_7, product.size_8, product.size_9, product.size_10, product.size_11, product.size_12, product.size_13, product.size_14, product.size_15, product.size_16, product.size_17)"
                class="btn"
                style="color: #fff9c4;; display: flex; justify-content: end; align-items: baseline; background-color: #55ae3c; height: min-content;">
                <i class="fa-solid fa-credit-card" style="padding-right: 5px;"></i>Купить</button>
            <button @click.prevent="deleteCart(product.id)" class="btn fa-solid fa-trash"
                style="color: #dc0345; display: flex; justify-content: end; align-items: end;"></button>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        product: {
            type: Object
        },
        buyProduct: {},
        laodCart: {},

    },
    mounted() {

    },
    methods: {
        deleteCart(id) {
            let cart = localStorage.getItem('cart');
            if (cart) {
                cart = JSON.parse(cart);
                cart.forEach(element => {
                    if (element.id == id) {
                        cart = cart.filter(element => {
                            return element.id !== id
                        });
                        var number_cart = document.getElementById("cart").innerText;
                        number_cart = Number(number_cart) - 1;
                        document.getElementById("cart").innerText = number_cart;
                        if (number_cart > 0) {
                            document.getElementById("cart").style.display = "inline";
                        }
                        else {
                            document.getElementById("cart").style.display = "none";
                        }

                    }
                });
                localStorage.setItem('cart', JSON.stringify(cart));
                this.laodCart();
            }
        },
    }
}
</script>

<style lang="scss" scoped>
</style>