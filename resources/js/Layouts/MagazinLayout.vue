<template>
    <div>
        <header class="header_area">
            <div class="classy-nav-container breakpoint-off d-flex align-items-center justify-content-between">
                <nav class="classy-navbar" id="essenceNav">
                    <router-link :to="'/'" class="nav-brand" aria-current="page">
                        <img @mouseenter="closeMenuAll()" style="height: 55px;"
                            src="/project_files/logotip-magazina.png" alt="Одевайка">
                    </router-link>
                    <div class="classy-navbar-toggler">
                        <span class="navbarToggler"><span></span><span></span><span></span></span>
                    </div>
                    <div class="classy-menu">
                        <div class="classycloseIcon">
                            <div class="cross-wrap"><span class="top"></span><span class="bottom"></span></div>
                        </div>
                        <div class="classynav">
                            <ul>
                                <li v-for="(menuMagazin, n) in menuMagazins">
                                    <router-link @mouseover.native="openMenu(n)" :to="'/?buyer=' + menuMagazin.id"
                                        class="btn-menu-buyer-link" aria-current="page">{{ menuMagazin.name }}
                                    </router-link>
                                    <div :id="'menu' + n" class="megamenu" @mouseleave="closeMenu(n)">
                                        <ul class="single-mega cn-col-4" v-for="section in menuMagazin.sections">
                                            <li class="title">
                                                <router-link
                                                    :to="'/?buyer=' + menuMagazin.id + '&section=' + section.id"
                                                    class="btn-menu-link" aria-current="page">{{ section.name }}
                                                </router-link>
                                            </li>
                                            <li v-for="subsection in section.subsections">
                                                <router-link
                                                    :to="'/?buyer=' + menuMagazin.id + '&section=' + section.id + '&subsection=' + subsection.id"
                                                    class="btn-menu-link" aria-current="page">{{ subsection.name }}
                                                </router-link>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="header-meta d-flex clearfix justify-content-end">
                    <div class="search-area">
                        <div class="search-input">
                            <input type="search" ref="headerSearch" id="headerSearch" name="headerSearch"
                                v-on:keyup.enter="onEnterClick" @blur="filterClick()" placeholder="Поиск...">
                            <button @click="filterClick()"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="favourite-area">
                        <router-link :to="'/favourites'" class="btn-menu-link" aria-current="page">
                            <img src="/img/core-img/heart.svg" alt=""><span id="favourite" :ref="'favourite'">0</span>
                        </router-link>
                    </div>
                    <div class="cart-area">
                        <router-link :to="'/cart'" class="btn-menu-link" aria-current="page">
                            <img src="/img/core-img/bag.svg" alt=""><span id="cart" :ref="'cart'">0</span>
                        </router-link>
                    </div>
                </div>
            </div>
        </header>
        <main class="main-magazin">
            <router-view></router-view>
        </main>
        <footer class="footer_area clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="single_widget_area d-flex mb-30">
                            <div class="footer_menu">
                                <ul>
                                    <li>
                                        <router-link :to="'/'">Магазин</router-link>
                                    </li>
                                    <li>
                                        <router-link :to="'/help'">Помощь</router-link>
                                    </li>
                                    <li>
                                        <router-link :to="'/about'">О нас</router-link>
                                    </li>
                                    <li>
                                        <router-link :to="'/contact'">Контакты</router-link>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="single_widget_area mb-30">
                            <ul class="footer_widget_menu">
                                <li>
                                    <router-link :to="'/payment'">Варианты оплаты</router-link>
                                </li>
                                <li>
                                    <router-link :to="'/shipping'">Доставка</router-link>
                                </li>
                                <li>
                                    <router-link :to="'/privacy'">Политика конфиденциальности</router-link>
                                </li>
                                <li>
                                    <router-link :to="'/terms'">Условия использования</router-link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row align-items-end">
                    <div class="col-12 col-md-6">
                        <div class="single_widget_area">
                            <div class="footer_social_area">
                                <a href="#" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                <a href="#" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Instagram"><i class="fa fa-instagram"
                                        aria-hidden="true"></i></a>
                                <a href="#" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                <a href="#" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Pinterest"><i class="fa fa-pinterest"
                                        aria-hidden="true"></i></a>
                                <a href="#" data-toggle="tooltip" data-placement="top" title=""
                                    data-original-title="Youtube"><i class="fa fa-youtube-play"
                                        aria-hidden="true"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-12 text-center">
                        <p style="color: white;">Магазин одежды "ОДЕВАЙКА" © 2022 Все права защищены</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>



<script>
import axios from "axios";

export default {
    components: {},
    data: () => ({
        menuMagazins: [],
    }),
    mounted() {
        this.laodmenuMagazin();
        this.loadFavourites();
        this.loadCart();
    },
    watch: {
        $route() {
            this.headerSearchNULL();
        },
    },
    methods: {
        headerSearchNULL() {
            if (this.$router.currentRoute.path != "/") {
                this.$refs.headerSearch.value = '';
            }
        },
        loadFavourites() {
            let favourites = localStorage.getItem('favourites');
            if (!favourites) {
                this.$refs.favourite.style.display = "none";
            }
            else {
                favourites = JSON.parse(favourites);
                if (favourites.length <= 0) {
                    this.$refs.favourite.style.display = "none";
                }
                else {
                    this.$refs.favourite.style.display = "inline";
                    this.$refs.favourite.innerText = favourites.length;
                }
            }
        },
        loadCart() {
            let cart = localStorage.getItem('cart');
            if (!cart) {
                this.$refs.cart.style.display = "none";
            }
            else {
                cart = JSON.parse(cart);
                if (cart.length <= 0) {
                    this.$refs.cart.style.display = "none";
                }
                else {
                    this.$refs.cart.style.display = "inline";
                    this.$refs.cart.innerText = cart.length;
                }
            }
        },
        laodmenuMagazin() {
            axios.get('/api/MagazinMenu')
                .then(res => {
                    this.menuMagazins = res.data.menu;
                })
        },
        openMenu(n) {
            var i = 0;
            var all = this.menuMagazins.length
            while (i < all) {
                document.getElementById("menu" + i).style.opacity = '0';
                document.getElementById("menu" + i).style.visibility = 'hidden';
                i++;
            }
            document.getElementById("menu" + n).style.opacity = '1';
            document.getElementById("menu" + n).style.visibility = 'visible';
        },
        closeMenu(n) {
            document.getElementById("menu" + n).style.opacity = '0';
            document.getElementById("menu" + n).style.visibility = 'hidden';
        },
        closeMenuAll() {
            const menu = document.querySelectorAll('.megamenu');
            menu.forEach(element => {
                element.style.opacity = '0';
                element.style.visibility = 'hidden';
            });
        },
        filterClick() {
            if (document.getElementById("bnt_filter") != null) {
                document.getElementById("bnt_filter").click();
            }
            else {
                this.$router.push('/?search=' + this.$refs.headerSearch.value);
            }
        },
        onEnterClick: function () {
            this.$refs.headerSearch.blur();
        },
    }
}
</script>

<style scoped>
</style>
<style>
body {
    background-color: #f3f3fd !important;
}
</style>