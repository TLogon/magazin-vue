<template>
    <div style="overflow-y: scroll; overflow-x:hidden;">
        <Spinner v-if="loading"></Spinner>
        <div class="container-md" v-else>
            <div class="row">
                <div class="col-md">
                    <div class="form-group has-success has-feedback mb-2">
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">
                                    <a style="font-size: 1rem;">Статус заказа</a></span>
                            </div>
                            <select class="form-select" v-model="status_id" v-on:change="onChange_status()"
                                style="max-width: 250px;border-top-left-radius: 0;border-bottom-left-radius: 0;border-color: #212529; cursor: pointer;">
                                <option selected value="0">Все</option>
                                <option value="1">Не оплаченные</option>
                                <option value="2">Оплаченные</option>
                                <option value="3">Обработанные</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md">
                    <BuyListAdmin :laodBuy="laodBuy" :buys="this.buys" :status_id="this.status_id"></BuyListAdmin>
                </div>
            </div>
            <div class="row" style="padding: 0 0.75rem;" v-if="buys.last_page > 1">
                <div class="col-md">
                    <nav aria-label="...">
                        <ul class="pagination row">
                            <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')" style="padding: 0;">
                                <button @click="paginationBuy(Number(buys.current_page) - 1)" class="page-link"
                                    style="border-radius: 50px;" aria-current="page">
                                    <i class="fa fa-circle-left" style="margin-right: 0.5rem;"></i>Предыдущее
                                </button>
                            </li>
                            <li class="page-item col-auto" :class="(page == buys.last_page ? 'disabled' : '')"
                                style="padding: 0; margin-left: 0.5rem;">
                                <button @click="paginationBuy(Number(buys.current_page) + 1)" class="page-link"
                                    style="border-radius: 50px;" aria-current="page">
                                    Следующее<i class="fa fa-circle-right" style="margin-left: 0.5rem;"></i>
                                </button>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="row" style="padding: 0.75rem;" v-if="buys.last_page > 1">
                <div class="col-md">
                    <nav aria-label="...">
                        <ul class="pagination row">
                            <li class="page-item col-auto" :class="(page == 1 ? 'disabled' : '')" style="padding: 0;">
                                <button @click="paginationBuy(1)" class="page-link" aria-current="page">
                                    Начало
                                </button>
                            </li>
                            <li v-if="page > Number(n - 3) && page < Number(n + 5)" class="page-item col-auto"
                                :class="(page == Number(n + 1) ? 'active' : '')" v-for="(link, n) in buys.last_page"
                                style="padding: 0;">
                                <button @click="paginationBuy(Number(n + 1))" class="page-link" aria-current="page">
                                    {{ n + 1 }}
                                </button>
                            </li>
                            <li class="page-item col-auto" :class="(page == buys.last_page ? 'disabled' : '')"
                                style="padding: 0;">
                                <button @click="paginationBuy(buys.last_page)" class="page-link" aria-current="page">
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
import BuyListAdmin from '../components/BuyListAdmin';

export default {
    metaInfo: {
        title: 'Заказы',
        meta: [
            { vmid: 'description', property: 'description', content: 'История и управление заказами' },
            { vmid: 'og:title', property: 'og:title', content: 'Заказы' },
            { vmid: 'og:description', property: 'og:description', content: 'История и управление заказами' },
        ],
    },
    components: {
        Spinner,
        BuyListAdmin
    },
    data: () => ({
        loading: true,
        countries: [],
        buys: [],
        status_id: 0,
        page: 1,
        buyFilter: "",
        path: "/admin/orders"
    }),
    mounted() {
        this.buyQuery();
        this.buyFil();
        this.laodBuy();
    },
    methods: {
        laodBuy() {
            axios.get('/api/BuyAdmin' + this.buyFilter).then((response) => {
                console.log(response.data);
                this.buys = response.data;
            })
            this.loading = false;
        },
        paginationBuy(n) {
            this.page = n;
            this.buyFil();
            this.laodBuy();
        },
        buyQuery() {
            if (this.$route.query.page) {
                this.page = this.$route.query.page;
            }
            if (this.$route.query.status) {
                this.status_id = this.$route.query.status;
            }
        },
        buyFil() {
            this.buyFilter = "";
            if (this.page > 1) {
                this.buyFilter = "&page=" + this.page + this.buyFilter;
            }
            if (this.status_id > 0) {
                this.buyFilter = this.buyFilter + "&status=" + this.status_id;
            }
            if (this.buyFilter != "") {
                this.buyFilter = "?" + this.buyFilter.substring(1);
            }
            this.$router.push(this.path + '/' + this.buyFilter);
        },
        onChange_status() {
            this.page = 1;
            this.buyFil();
            this.laodBuy();
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

.input-group-text {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-color: #212529;
    background-color: #212529;
    color: white;
}
</style>