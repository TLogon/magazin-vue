<template>
    <div style="overflow-y: scroll; overflow-x:hidden;">
        <Spinner v-if="loading"></Spinner>
        <div class="container-md" v-else>
            <div class="row">
                <div class="col-md">
                    <router-link :to="{ path: '/admin/products/new' }">
                        <i class="btn btn-md btn-success" style="font-style: normal;"><i class="fa fa-plus"
                                style="margin-right: 0.5rem;"></i>Добавить новый товар</i>
                    </router-link>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4 rounded-3 shadow-sm">
                        <div class="card-header py-2">
                            <h5 class="my-0 fw-normal">Фильтры</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 col-md-4 mb-2">
                                    <select id="s_buyer" name="s_buyer" class="form-select" v-model="buyer_id"
                                        v-on:change="onChange_buyer(buyer_id)">
                                        <option selected value="0">Выбрать...</option>
                                        <option v-for="buyer in buyers" :value="buyer.id">{{ buyer.name }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <select id="s_section" name="s_section" class="form-select" v-model="section_id"
                                        v-on:change="onChange_section(section_id)">
                                        <option selected value="0">Выбрать...</option>
                                        <option v-for="section in sections" :value="section.id">{{ section.name }}
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <select id="s_subsection" name="s_subsection" class="form-select"
                                        v-model="subsection_id" v-on:change="onChange_subsection(subsection_id)">
                                        <option selected value="0">Выбрать...</option>
                                        <option v-for="subsection in subsections" :value="subsection.id">{{
                                                subsection.name
                                        }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md mb-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" v-model="name"
                                            placeholder="Введите название товара для поиска..."
                                            aria-label="Recipient's username" aria-describedby="button-addon2">
                                        <button class="btn btn-outline-success" type="button" @click="Search_click"
                                            id="button-addon2">Найти</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <h5>Товаров всего: {{ products.total }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <ProductListAdmin :deleteProduct="deleteProduct" :products="this.products"></ProductListAdmin>
                </div>
            </div>
            <div class="row" style="padding: 0 0.75rem;" v-if="products.last_page > 1">
                <div class="col-md">
                    <nav aria-label="...">
                        <ul class="pagination row">
                            <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')" style="padding: 0;">
                                <button @click="paginationProduct(Number(products.current_page) - 1)" class="page-link"
                                    style="border-radius: 50px;" aria-current="page">
                                    <i class="fa fa-circle-left" style="margin-right: 0.5rem;"></i>Предыдущее
                                </button>
                            </li>
                            <li class="page-item col-auto" :class="(page == products.last_page ? 'disabled' : '')"
                                style="padding: 0; margin-left: 0.5rem;">
                                <button @click="paginationProduct(Number(products.current_page) + 1)" class="page-link"
                                    style="border-radius: 50px;" aria-current="page">
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
                            <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')" style="padding: 0;">
                                <button @click="paginationProduct(1)" class="page-link" aria-current="page">
                                    Начало
                                </button>
                            </li>
                            <li v-if="page > Number(n - 3) && page < Number(n + 5)" class="page-item col-auto"
                                :class="(page == Number(n + 1) ? 'active' : '')" v-for="(link, n) in products.last_page"
                                style="padding: 0;">
                                <button @click="paginationProduct(Number(n + 1))" class="page-link" aria-current="page">
                                    {{ n + 1 }}
                                </button>
                            </li>
                            <li class="page-item col-auto" :class="(page == products.last_page ? 'disabled' : '')"
                                style="padding: 0;">
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
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";
import ProductListAdmin from '../components/ProductListAdmin';
export default {
    metaInfo: {
        title: 'Товары магазина',
        meta: [
            { vmid: 'description', property: 'description', content: 'Товары магазина' },
            { vmid: 'og:title', property: 'og:title', content: 'Товары магазина' },
            { vmid: 'og:description', property: 'og:description', content: 'Товары магазина' },
        ],
    },
    components: {
        Spinner,
        ProductListAdmin
    },
    data: () => ({
        buyer_id: 0,
        section_id: 0,
        subsection_id: 0,
        name: "",
        buyers: [],
        sections: [],
        subsections: [],
        loading: true,
        products: [],
        page: 1,
        productFilter: "",
        path: "/admin/products"
    }),
    mounted() {
        this.laodBuyer();
        this.laodProduct();
    },
    methods: {
        laodProduct() {
            axios.get('/sanctum/csrf-cookie').then(response => {
                if (this.$route.query.page) {
                    this.page = this.$route.query.page;
                }
                if (this.$route.query.buyer) {
                    this.buyer_id = this.$route.query.buyer;
                    this.getSection(this.$route.query.buyer);
                }
                if (this.$route.query.section) {
                    this.section_id = this.$route.query.section;
                    this.getSubSections(this.$route.query.section);
                }
                if (this.$route.query.subsection) {
                    this.subsection_id = this.$route.query.subsection;
                }
                if (this.$route.query.name) {
                    this.name = this.$route.query.name;
                }
                this.productFil();
                axios.get('/api/products' + this.productFilter)
                    .then(res => {
                        this.products = res.data;
                        console.log(res.data);
                        if (this.$route.query.page < 1) {
                            this.page = 1;
                        }
                        if (this.$route.query.page > res.data.last_page) {
                            this.page = this.products.last_page;
                        }
                        setTimeout(() => {
                            this.loading = false;
                        }, 300);
                    })
            });
        },
        deleteProduct(id, name) {
            Swal.fire({
                title: 'Удалить этот продукт?',
                text: "Продукт " + name + " будет удален и Вы не сможете его восстановить!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет, я передумал...',
                confirmButtonText: 'Да, удалить это!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post('/api/products/' + id, {
                        _method: 'DELETE'
                    })
                        .then(response => {
                            this.laodProduct();
                        })
                }
            })
        },
        laodBuyer() {
            axios.get('/api/GetBuyers')
                .then(res => {
                    this.buyers = res.data;
                })
        },
        paginationProduct(n) {
            this.page = n;
            this.productFil();
            this.laodProduct();
        },
        productFil() {
            this.productFilter = "";
            if (this.buyer_id != 0) { this.productFilter = this.productFilter + "&buyer=" + this.buyer_id; }
            if (this.section_id != 0) { this.productFilter = this.productFilter + "&section=" + this.section_id; }
            if (this.subsection_id != 0) { this.productFilter = this.productFilter + "&subsection=" + this.subsection_id; }
            if (this.name != "") { this.productFilter = this.productFilter + "&name=" + this.name; }

            if (this.page != 1) { this.productFilter = "?page=" + this.page + this.productFilter; }
            else { if (this.productFilter != "") { this.productFilter = "?" + this.productFilter.substring(1); } }

            this.$router.push(this.path + '/' + this.productFilter);
        },
        getSection() {
            axios.get('/api/GetSections/' + this.buyer_id).then((response) => {
                this.sections = response.data
            }),
                this.section_id = 0;
            this.subsection_id = 0;
            this.subsections = '';
        },
        getSubSections() {
            axios.get('/api/GetSubSections/' + this.section_id).then((response) => {
                this.subsections = response.data
            }),
                this.subsection_id = 0;
        },
        onChange_buyer(buyer_id) {
            this.page = 1;
            this.buyer_id = buyer_id;
            this.section_id = 0;
            this.subsection_id = 0;
            this.productFil();
            this.laodProduct();
        },
        onChange_section(section_id) {
            this.page = 1;
            this.section_id = section_id;
            this.subsection_id = 0;
            this.productFil();
            this.laodProduct();
        },
        onChange_subsection(subsection_id) {
            this.page = 1;
            this.subsection_id = subsection_id;
            this.productFil();
            this.laodProduct();
        },
        Search_click() {
            this.page = 1;
            this.productFil();
            this.laodProduct();
        },
    }
}
</script>

<style scoped>
.btn-success {
    float: right;
    margin-bottom: 0.5rem;
}

.container-md {
    padding-top: 1rem;
}

.router-link-exact-active {
    color: white;
    background-color: #0d6efd;
}
</style>