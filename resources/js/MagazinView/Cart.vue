<template>
    <div>
        <Spinner v-if="loading"></Spinner>
        <div v-else-if="!loading" style="padding-top: 100px;">
            <div class="container-fluid">
                <div class="row" v-if="!this.products">
                    <div class="col-12">
                        <a>У вас нет добавленных товаров</a>
                    </div>
                </div>
                <div class="row" v-else-if="this.products">
                    <div class="col">
                        <CartList :laodCart="this.laodCart" :buyProduct="this.buyProduct" :products="this.products">
                        </CartList>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <BuyCard :laodCart="this.laodCart" :formProduct="this.formProduct"></BuyCard>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";
import CartList from "../components/CartList";
import BuyCard from '../components/BuyCard.vue';

export default {
    metaInfo: {
        title: 'Корзина - Магазин одежды Одевайка',
        meta: [
            { vmid: 'description', property: 'description', content: 'Корзина с товарами магазина одежды Одевайка' },
            { vmid: 'og:title', property: 'og:title', content: 'Корзина - Магазин одежды Одевайка' },
            { vmid: 'og:description', property: 'og:description', content: 'Корзина с товарами магазина одежды Одевайка' },
        ],
    },
    components: {
        Spinner,
        CartList,
        BuyCard
    },
    data: () => ({
        loading: true,
        products: [],
        formProduct: {
            id: 0,
            name: '',
            price: 0,
            section_id: 0,
            photo_link: '',
            size_1: false,
            size_2: false,
            size_3: false,
            size_4: false,
            size_5: false,
            size_6: false,
            size_7: false,
            size_8: false,
            size_9: false,
            size_10: false,
            size_11: false,
            size_12: false,
            size_13: false,
            size_14: false,
            size_15: false,
            size_16: false,
            size_17: false,
        },
    }),
    mounted() {
        this.laodCart();
    },
    methods: {
        buyProduct(id, name, price, section_id, photo_link, size_1, size_2, size_3, size_4, size_5, size_6, size_7, size_8, size_9, size_10, size_11, size_12, size_13, size_14, size_15, size_16, size_17) {
            this.formProduct.id = id;
            this.formProduct.name = name;
            this.formProduct.price = price;
            this.formProduct.section_id = section_id;
            this.formProduct.photo_link = photo_link;
            this.formProduct.size_1 = size_1;
            this.formProduct.size_2 = size_2;
            this.formProduct.size_3 = size_3;
            this.formProduct.size_4 = size_4;
            this.formProduct.size_5 = size_5;
            this.formProduct.size_6 = size_6;
            this.formProduct.size_7 = size_7;
            this.formProduct.size_8 = size_8;
            this.formProduct.size_9 = size_9;
            this.formProduct.size_10 = size_10;
            this.formProduct.size_11 = size_11;
            this.formProduct.size_12 = size_12;
            this.formProduct.size_13 = size_13;
            this.formProduct.size_14 = size_14;
            this.formProduct.size_15 = size_15;
            this.formProduct.size_16 = size_16;
            this.formProduct.size_17 = size_17;
            document.getElementById('openModal').removeAttribute("style");
            document.getElementById('openModal').style.opacity = 1;
            document.getElementById('cart_product_list').style.pointerEvents = 'none';
        },
        laodCart() {
            let favourites = localStorage.getItem('cart');
            if (favourites) {
                favourites = JSON.parse(favourites);
                let var_id = [];
                favourites.forEach(element => {
                    var_id.push(element.id);
                });
                let formD = new FormData;
                formD.append('id', var_id);
                axios.post('/api/LoadCart', formD)
                    .then(res => {

                        console.log(res.data.data);
                        this.products = res.data.data;
                        setTimeout(() => {
                            this.loading = false;
                        }, 300);
                    })
            }
            else { this.loading = false; this.products = null; }
        },
    }
}
</script>

<style scoped>
</style>