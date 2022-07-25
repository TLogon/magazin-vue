<template>
    <div>
        <Spinner v-if="loading"></Spinner>
        <div v-else-if="!loading" style="padding-top: 100px;">
            <div class="row row-card">
                <div class="col-auto col-card">
                    <div class="filter-card-style">
                        <div class="row" style="padding: 0 0 10px 0;">
                            <div class="col">
                                <a class="filter-name">Цена от {{ range[0] | formatPrice }} по {{ range[1] | formatPrice
                                }} ₸</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <VueSimpleRangeSlider :min="min" :max="max" :logarithmic="true" v-model="range" />
                            </div>
                        </div>
                        <div class="row" v-if="section_id >= 1 && section_id <= 4">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <a class="filter-name">Размеры</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <SizesList :classcol="'col-3'" :nameSizes="this.nameSizes"
                                            :size="this.checkSizes" :section_id="this.section_id"
                                            :loadSection="this.loadSection"></SizesList>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <a class="filter-name">Страны</a>
                                    </div>
                                </div>
                                <div class="row" style="max-height: 120px; overflow: auto;">
                                    <div class="col-12" v-for="country in countries">
                                        <input type="checkbox" :id="country.name" :value="country.id"
                                            class="form-check-input" v-model="checkCountries">
                                        <label :for="country.name" class="form-check-label">{{
                                                country.name
                                        }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        <a class="filter-name">Бренды</a>
                                    </div>
                                </div>
                                <div class="row" style="max-height: 120px; overflow: auto;">
                                    <div class="col-12" v-for="brand in brands">
                                        <input type="checkbox" :id="brand.name" :value="brand.id"
                                            class="form-check-input" v-model="checkBrands">
                                        <label :for="brand.name" class="form-check-label">{{ brand.name
                                        }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button id="bnt_filter" @click="filterClick()" class="btn-card-ok"
                                    style="float: right;">
                                    <i class="fa fa-circle-check"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <ProductListMagazin :products="this.products"></ProductListMagazin>
                        </div>
                    </div>
                    <div class="row" style="padding: 0 0.75rem;" v-if="products.last_page > 1">
                        <div class="col-md">
                            <nav aria-label="...">
                                <ul class="pagination row">
                                    <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')"
                                        style="padding: 0;">
                                        <button @click="paginationProduct(Number(products.current_page) - 1)"
                                            class="page-link" style="border-radius: 50px;" aria-current="page">
                                            <i class="fa fa-circle-left" style="margin-right: 0.5rem;"></i>Предыдущее
                                        </button>
                                    </li>
                                    <li class="page-item col-auto"
                                        :class="(page == products.last_page ? 'disabled' : '')"
                                        style="padding: 0; margin-left: 0.5rem;">
                                        <button @click="paginationProduct(Number(products.current_page) + 1)"
                                            class="page-link" style="border-radius: 50px;" aria-current="page">
                                            Следующее<i class="fa fa-circle-right" style="margin-left: 0.5rem;"></i>
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="row" style="padding: 0.75rem;" v-if="products.last_page > 1">
                        <div class="col-md">
                            <nav aria-label="...">
                                <ul class="pagination row">

                                    <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')"
                                        style="padding: 0;">
                                        <button @click="paginationProduct(1)" class="page-link" aria-current="page">
                                            Начало
                                        </button>
                                    </li>
                                    <li v-if="page > Number(n - 3) && page < Number(n + 5)" class="page-item col-auto"
                                        :class="(page == Number(n + 1) ? 'active' : '')"
                                        v-for="(link, n) in products.last_page" style="padding: 0;">
                                        <button @click="paginationProduct(Number(n + 1))" class="page-link"
                                            aria-current="page">
                                            {{ n + 1 }}
                                        </button>
                                    </li>
                                    <li class="page-item col-auto"
                                        :class="(page == products.last_page ? 'disabled' : '')" style="padding: 0;">
                                        <button @click="paginationProduct(products.last_page)" class="page-link"
                                            aria-current="page">
                                            Конец
                                        </button>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";
import ProductListMagazin from '../components/ProductListMagazin';
import VueSimpleRangeSlider from 'vue-simple-range-slider';
import 'vue-simple-range-slider/dist/vueSimpleRangeSlider.css'
import SizesList from '../components/SizesList';

export default {
    metaInfo() {
        return {
            title: this.title + ' - Магазин одежды Одевайка',
            meta: [
                { vmid: 'description', property: 'description', content: this.description },
                { vmid: 'og:title', property: 'og:title', content: this.title + ' - Магазин одежды Одевайка' },
                { vmid: 'og:description', property: 'og:description', content: this.description },
            ],
        }
    },
    components: {
        Spinner,
        ProductListMagazin,
        VueSimpleRangeSlider,
        SizesList
    },
    data: () => ({
        loading: true,
        productFilter: "",
        page: 1,
        products: [],
        range: [0, 100000],
        number: 0,
        min: 0,
        max: 100000,
        nameSizes: [],
        section_id: 0,
        checkSizes: [],
        loadSection: -1,
        countries: [],
        checkCountries: [],
        brands: [],
        checkBrands: [],
        title: 'Товары',
        description: 'товары',
    }),
    mounted() {
        this.laodCountries();
        this.laodBrands();
        this.loadPage();
    },
    watch: {
        $route() {
            this.zeroingOut();
            this.loadPage();
        },
    },
    methods: {
        loadPage() {
            this.loadQuery();
            this.productFilterPath();
            this.productFilter_Load();
            this.laodProduct();
        },
        laodCountries() {
            axios.get('/api/SelectCountries')
                .then(res => {
                    this.countries = res.data;
                })
        },
        laodBrands() {
            axios.get('/api/SelectBrands')
                .then(res => {
                    this.brands = res.data;
                })
        },
        filterClick() {
            this.page = 1;
            this.productFilterPath();
            this.productFilter_Click();
            this.$router.push('/' + this.productFilter);
        },
        laodProduct() {
            axios.get('/api/magazin/' + this.productFilter)
                .then(res => {
                    this.products = res.data;
                    if (res.data.meta_buyer != '') {
                        this.title = this.title + res.data.meta_buyer.toLowerCase();
                        this.description = this.description + res.data.meta_buyer.toLowerCase();
                    }
                    if (res.data.meta_section != '') {
                        this.title = res.data.meta_section + ' ' + res.data.meta_buyer;
                        this.description = res.data.meta_section.toLowerCase() + ' ' + res.data.meta_buyer;
                    }
                    if (res.data.meta_subsection != '') {
                        this.title = this.title + ', ' + res.data.meta_subsection;
                        this.description = this.description + ', ' + res.data.meta_subsection.toLowerCase();
                    }
                    this.description = 'Только у нас ' + this.description + ' по доступным ценам. Вы можете оплатить товар удобным для Вас способом и заказать доставку домой. Закупайся в магазине "Одевайка".';
                    console.log(this.products);
                    setTimeout(() => {
                        this.loading = false;
                    }, 300);
                })
                .catch(err => {
                    this.loading = false;
                });
        },
        loadQuery() {
            if (this.$route.query.section) {
                this.section_id = this.$route.query.section;
                this.loadSection = this.$route.query.section;
                let formD = new FormData;
                formD.append('section_id', this.section_id);
                axios.post('/api/GetSizes/', formD).then((response) => {
                    console.log(response.data.data);
                    this.nameSizes = response.data.data;
                });
            }
            else {
                this.section_id = 0;
                this.loadSection = -1;
                this.nameSizes = '';
            }
            if (this.$route.query.page) {
                this.page = this.$route.query.page;
            }
        },
        productFilter_Load() {
            var var_productFilter = "";
            if (this.$route.query.brand) {
                var var_brand = this.$route.query.brand;
                var arr_brand = var_brand.split(",");
                this.checkBrands = arr_brand;
                var_productFilter = var_productFilter + "&brand=" + this.$route.query.brand;
            }
            if (this.$route.query.country) {
                var var_country = this.$route.query.country;
                var arr_country = var_country.split(",");
                this.checkCountries = arr_country;
                var_productFilter = var_productFilter + "&country=" + this.$route.query.country;
            }
            if (this.$route.query.search) {
                document.getElementById("headerSearch").value = this.$route.query.search;
                var_productFilter = var_productFilter + "&search=" + this.$route.query.search;
            }
            if (this.$route.query.priceFrom) {
                this.range[0] = this.$route.query.priceFrom;
                var_productFilter = var_productFilter + "&priceFrom=" + this.$route.query.priceFrom;
            }
            if (this.$route.query.priceTo) {
                this.range[1] = this.$route.query.priceTo;
                var_productFilter = var_productFilter + "&priceTo=" + this.$route.query.priceTo;
            }
            if (this.$route.query.size) {
                var var_size = this.$route.query.size;
                var arr_size = var_size.split(",");
                var data = [];
                for (let i = 0; i < arr_size.length; i++) {
                    var var_json = { size_id: arr_size[i] };
                    data.push(var_json);
                }
                this.checkSizes = data;
                var_productFilter = var_productFilter + "&size=" + this.$route.query.size;
            }
            if (this.productFilter == "") { this.productFilter = "?" + var_productFilter.substring(1); }
            else { this.productFilter = this.productFilter + var_productFilter; }
        },
        productFilter_Click() {
            var var_productFilter = "";
            var arrBrand = "";
            this.checkBrands.forEach(function (elem) {
                if (arrBrand == "") { arrBrand = elem }
                else { arrBrand = arrBrand + "," + elem }
            });
            if (arrBrand != "") {
                var_productFilter = var_productFilter + "&brand=" + arrBrand;
            }
            var arrCountry = "";
            this.checkCountries.forEach(function (elem) {
                if (arrCountry == "") { arrCountry = elem }
                else { arrCountry = arrCountry + "," + elem }
            });
            if (arrCountry != "") {
                var_productFilter = var_productFilter + "&country=" + arrCountry;
            }
            var search = document.getElementById("headerSearch").value;
            if (search != '') {
                var_productFilter = var_productFilter + "&search=" + search;
            }
            if (this.range[0] > this.min) {
                var_productFilter = var_productFilter + "&priceFrom=" + this.range[0];
            }
            if (this.range[1] < this.max) {
                var_productFilter = var_productFilter + "&priceTo=" + this.range[1];
            }
            if (this.$route.query.section && this.nameSizes.length > 0) {
                var size_i = 1;
                var size_string = "";
                for (let i = 0; i < this.nameSizes.length; i++) {
                    if (document.getElementById('ch_size_' + this.nameSizes[i].id).checked == true) {
                        if (size_string == "") {
                            size_string = this.nameSizes[i].id;
                        }
                        else {
                            size_string = size_string + "," + this.nameSizes[i].id;
                        }
                    }
                }
                if (size_string != "") { var_productFilter = var_productFilter + "&size=" + size_string; }
            }
            if (this.productFilter == "") { this.productFilter = "?" + var_productFilter.substring(1); }
            else { this.productFilter = this.productFilter + var_productFilter; }
        },
        productFilterPath() {
            this.productFilter = "";
            if (this.$route.query.buyer) {
                this.productFilter = this.productFilter + "&buyer=" + this.$route.query.buyer;
            }
            if (this.$route.query.section) {
                this.productFilter = this.productFilter + "&section=" + this.section_id;
            }
            if (this.$route.query.subsection) {
                this.productFilter = this.productFilter + "&subsection=" + this.$route.query.subsection;
            }
            if (this.$route.query.name) { this.productFilter = this.productFilter + "&name=" + this.$route.query.name; }
            if (this.page != 1) { this.productFilter = "?page=" + this.page + this.productFilter; }
            else { if (this.productFilter != "") { this.productFilter = "?" + this.productFilter.substring(1); } }
        },
        zeroingOut() {
            this.productFilter = "";
            this.page = 1;
            this.products = [];
            this.range = [0, 100000];
            this.number = 0;
            this.min = 0;
            this.max = 100000;
            this.nameSizes = [];
            this.section_id = 0;
            this.loadSection = -1;
            this.checkSizes = [];
            this.checkCountries = [];
            this.checkBrands = [];
            const menu = document.querySelectorAll('.megamenu');
            menu.forEach(element => {
                element.style.opacity = '0';
                element.style.visibility = 'hidden';
            });
        },
        paginationProduct(n) {
            this.page = n;
            this.productFilterPath();
            this.productFilter_Click();
            this.$router.push('/' + this.productFilter);
        },
    }
}
</script>

<style scoped>
</style>