<template>
    <div>
        <Spinner v-if="loading"></Spinner>
        <div v-else-if="!loading" style="padding-top: 100px;">
            <div class="container-fluid">
                <div class="row" v-if="!this.products">
                    <div class="col-12">
                        <a>У вас нет понравившихся товаров</a>
                    </div>
                </div>
                <div class="row" v-else-if="this.products">
                    <div class="col">
                        <FavouriteList :laodFavourites="this.laodFavourites" :products="this.products"></FavouriteList>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";
import FavouriteList from "../components/FavouriteList";

export default {
    metaInfo: {
        title: 'Понравившиеся товары - Магазин одежды Одевайка',
        meta: [
            { vmid: 'description', property: 'description', content: 'Понравившиеся товары магазина одежды Одевайка' },
            { vmid: 'og:title', property: 'og:title', content: 'Понравившиеся товары - Магазин одежды Одевайка' },
            { vmid: 'og:description', property: 'og:description', content: 'Понравившиеся товары магазина одежды Одевайка' },
        ],
    },
    components: {
        Spinner,
        FavouriteList
    },
    data: () => ({
        loading: true,
        products: [],
    }),
    mounted() {
        this.laodFavourites();
    },
    methods: {
        laodFavourites() {
            let favourites = localStorage.getItem('favourites');
            if (favourites) {
                favourites = JSON.parse(favourites);
                let var_id = [];
                favourites.forEach(element => {
                    var_id.push(element.id);
                });
                let formD = new FormData;
                formD.append('id', var_id);
                axios.post('/api/LoadFavourites', formD)
                    .then(res => {
                        console.log(res.data.data);
                        this.products = res.data.data;
                        setTimeout(() => {
                            this.loading = false;
                        }, 300);
                    })
            }
            else { this.loading = false;this.products = null;}
        },
    }
}
</script>

<style scoped>
</style>