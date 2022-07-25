<template>
    <div style="padding-top:100px;">
        <Spinner v-if="loading"></Spinner>
        <div v-else-if="!loading && !not_found">
            <section class="single_product_details_area d-flex align-items-center">
                <div class="single_product_thumb clearfix">
                    <CaruselList :photos="photos"></CaruselList>
                </div>
                <div class="single_product_desc clearfix">
                    <span>
                        <router-link :to="'/?buyer=' + product.buyer_id" class="link-primary" aria-current="page">{{
                                product.byer_name
                        }}</router-link> /
                        <router-link :to="'/?buyer=' + product.buyer_id + '&section=' + product.section_id"
                            class="link-primary" aria-current="page">{{ product.section_name }}</router-link> /
                        <router-link
                            :to="'/?buyer=' + product.buyer_id + '&section=' + product.section_id + '&subsection=' + product.subsection_id"
                            class="link-primary" aria-current="page">{{ product.subsection_name }}</router-link>
                    </span>
                    <div style="margin: 20px 0px;">
                        <h2>{{ product.name }}</h2>
                        <p class="product-price">₸ {{ product.price | formatPrice }}</p>
                    </div>
                    <a style="font-size: 1.5rem;">О товаре</a><br />
                    <div v-if="product.country_name != null"><a class="text-muted"
                            style="text-decoration: none;">Страна:</a>
                        <router-link :to="'/?country=' + product.country_id" class="link-primary" aria-current="page">
                            {{ product.country_name }}</router-link>
                        <br />
                    </div>
                    <div v-if="product.brand_name != null"><a class="text-muted"
                            style="text-decoration: none;">Бренд:</a>
                        <router-link :to="'/?country=' + product.brand_id" class="link-primary" aria-current="page">{{
                                product.brand_name
                        }}</router-link>
                        <br />
                    </div>
                    <div v-if="sizes.length > 0">
                        <a class="text-muted" style="text-decoration: none;">Размеры: </a>
                        <a v-for="size in sizes">
                            <router-link
                                :to="'/?buyer=' + product.buyer_id + '&section=' + product.section_id + '&subsection=' + product.subsection_id + '&size=' + size.size_id"
                                class="link-primary" aria-current="page">{{ size.size_name }}</router-link>&nbsp;
                        </a>
                    </div>
                    <div class="cart-form clearfix" style="margin: 20px 0px;">
                        <div class="cart-fav-box d-flex align-items-center">
                            <button name="addtocart" :id="`cart_product`" :ref="`cart_product`"
                                @click.prevent="addCart(product.id)"
                                :class="!loadCart(product.id) ? 'btn essence-btn' : 'btn essence-btn essence-btn-on'">{{
                                        !loadCart(product.id)
                                            ? 'Добавить в корзину' : 'Добавлен в корзину'
                                }}</button>
                            <div class="product-favourite ml-4 div-favourite">
                                <a @click.prevent="addFavourites(product.id)" :ref="`ref_favourite`"
                                    class="favme fa fa-heart a-favourite"
                                    :class="!loadFavourites(product.id) ? 'fa-off-white' : 'fa-on'"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div v-if="not_found">
            <h1>Продукт не найден</h1>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Spinner from "../components/Spinner";
import CaruselList from "../components/CaruselList";

export default {
    metaInfo() {
        return {
            title: this.product.name + ' - Магазин одежды Одевайка',
            meta: [
                { vmid: 'description', property: 'description', content: 'Купить ' + this.product.name + ' в магазине одежды Одевайка' },
                { vmid: 'og:title', property: 'og:title', content: this.product.name + ' - Магазин одежды Одевайка' },
                { vmid: 'og:description', property: 'og:description', content: 'Купить ' + this.product.name + ' в магазине одежды Одевайка' },
            ],
        }
    },
    components: {
        Spinner,
        CaruselList
    },
    data: () => ({
        loading: true,
        product: [],
        sizes: [],
        photos: [],
        not_found: false
    }),

    mounted() {
        this.laodProduct(this.$route.params.id);
    },

    methods: {
        laodProduct(id) {
            axios.get('/api/LoadProduct/' + id)
                .then(res => {
                    console.log(res.data);
                    this.product = res.data.products[0];
                    this.sizes = res.data.sizes;
                    var var_photos = res.data.photos;
                    for (let i = 0; i < var_photos.length; i++) {
                        if (var_photos[i].id == this.product.avatar) {
                            this.photos.push(var_photos[i])
                        }
                    }
                    for (let i = 0; i < var_photos.length; i++) {
                        if (var_photos[i].id != this.product.avatar) {
                            this.photos.push(var_photos[i])
                        }
                    }
                    this.not_found = false;
                    setTimeout(() => {
                        this.loading = false;
                    }, 300);
                })
                .catch(err => {
                    this.loading = false;
                    this.not_found = true
                })
        },
        addCart(id) {
            let cart = localStorage.getItem('cart');
            let newProduct = [
                {
                    'id': id
                }
            ];
            if (!cart) {
                localStorage.setItem('cart', JSON.stringify(newProduct))
            }
            else {
                cart = JSON.parse(cart);
                cart.forEach(element => {
                    if (element.id == id) {
                        cart = cart.filter(element => {
                            return element.id !== id
                        })
                        newProduct = null;
                    }
                });
                Array.prototype.push.apply(cart, newProduct);
                localStorage.setItem('cart', JSON.stringify(cart));
            }
            var number_cart = document.getElementById("cart").innerText;
            if (newProduct) {
                this.$refs["cart_product"].innerText = 'Добавлен в корзину';
                this.$refs["cart_product"].className = 'btn essence-btn essence-btn-on';
                number_cart = Number(number_cart) + 1;
                document.getElementById("cart").innerText = number_cart;
                document.getElementById("cart").style.display = "inline";
            }
            else {
                this.$refs["cart_product"].innerText = 'Добавить в корзину';
                this.$refs["cart_product"].className = 'btn essence-btn essence-btn-off';
                number_cart = Number(number_cart) - 1;
                document.getElementById("cart").innerText = number_cart;
                if (number_cart > 0) {
                    document.getElementById("cart").style.display = "inline";
                }
                else {
                    document.getElementById("cart").style.display = "none";
                }
            }
        },
        addFavourites(id) {
            let favourites = localStorage.getItem('favourites');
            let newProduct = [
                {
                    'id': id
                }
            ];
            if (!favourites) {
                localStorage.setItem('favourites', JSON.stringify(newProduct));
            }
            else {
                favourites = JSON.parse(favourites);
                favourites.forEach(element => {
                    if (element.id == id) {
                        favourites = favourites.filter(element => {
                            return element.id !== id
                        })
                        newProduct = null;
                    }
                });
                Array.prototype.push.apply(favourites, newProduct);
                localStorage.setItem('favourites', JSON.stringify(favourites));
            }
            var number_favourite = document.getElementById("favourite").innerText;
            if (newProduct) {
                this.$refs["ref_favourite"].className = 'favme fa fa-heart a-favourite fa-on';
                number_favourite = Number(number_favourite) + 1;
                document.getElementById("favourite").innerText = number_favourite;
                document.getElementById("favourite").style.display = "inline";
            }
            else {
                this.$refs["ref_favourite"].className = 'favme fa fa-heart a-favourite fa-off-white';
                number_favourite = Number(number_favourite) - 1;
                document.getElementById("favourite").innerText = number_favourite;
                if (number_favourite > 0) {
                    document.getElementById("favourite").style.display = "inline";
                }
                else {
                    document.getElementById("favourite").style.display = "none";
                }
            }
        },
        loadCart(cart_id) {
            let cart = localStorage.getItem('cart');
            if (cart) {
                cart = JSON.parse(cart);
                return cart.filter(n => n.id === cart_id).length === 0 ? false : true; // false
            }
        },
        loadFavourites(favourite_id) {
            let favourites = localStorage.getItem('favourites');
            if (favourites) {
                favourites = JSON.parse(favourites);
                return favourites.filter(n => n.id === favourite_id).length === 0 ? false : true;
            }
        },
    }
}
</script>

<style scoped>
</style>