<template>
    <div id="openModal" class="modal-buy" style="left: -9999px; top: -9999px;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ formProduct.name }}</h3>
                    <a v-on:click.prevent="closeModal()" title="Закрыть" class="close">×</a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-auto">
                            <img src="/project_files/pixel.png" class="foto-card-style" :style="{
                                backgroundImage: formProduct.photo_link == null ? `url(/project_files/nophoto_magazin.png)` :
                                    `url(/files/` + formProduct.photo_link + `)`
                            }">
                        </div>
                        <div class="col">

                            <div class="row mb-2">
                                <div class="col-12">
                                    <a>Имя:</a>
                                    <input class="form-control" v-model="FIO" />
                                    <div ref="refFIO" class="d-none">Введите имя</div>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-auto">
                                    <a>Страна:</a>
                                    <select class="form-select" v-model="Country_code_id">
                                        <option selected disabled value="0">Выбрать...</option>
                                        <option v-for="CountryCode in CountryCodes" :value="CountryCode.id">
                                            {{ CountryCode.name }} ({{ CountryCode.code }})</option>
                                    </select>
                                    <div ref="refCountry" class="d-none">Укажите страну</div>
                                </div>
                                <div class="col">
                                    <a>Номер телефона:</a>
                                    <input class="form-control" v-mask="'(###) ### ## ##'" v-model="Phone" />
                                    <div ref="refPhone" class="d-none">Введите номер телефона</div>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-12">
                                    <a>Адрес:</a>
                                    <textarea rows="2" class="form-control" v-model="Address" />
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-2">
                                <div class="col-6" v-if="nameSizes.length">
                                    <a>Размер:</a>
                                    <select class="form-select" v-model="size_id">
                                        <option selected disabled value="0">Выбрать...</option>
                                        <option v-for="nameSize in nameSizes" :value="nameSize.size_id">
                                            {{ nameSize.name }}</option>
                                    </select>
                                    <div ref="refSize" class="d-none">Укажите размер</div>
                                </div>
                                <div class="col-6">
                                    <a>Количество:</a>
                                    <div class="input-group">
                                        <input class="form-control" v-model="value_quantity" value="1"
                                            @keyup="InputKeyUp()" />
                                        <button class="btn btn-secondary" v-on:click="PlusQuantity()"><i
                                                class="fa fa-plus"></i></button>
                                        <button class="btn btn-secondary" v-on:click="MinusQuantity()"><i
                                                class="fa fa-minus"></i></button>
                                    </div>
                                    <div ref="refQuantity" class="d-none">Укажите количество</div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid gap-2 col-6 mx-auto">
                                        <button class="btn btn-success btn-lg" style="margin: 10px;"
                                            v-on:click="Buy_click()">Оплатить: {{ this.total | formatPrice }}
                                            ₸</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import Vue from 'vue';
import VueMask from 'v-mask';
Vue.use(VueMask);
export default {
    props: {
        formProduct: {
            type: Object
        },
        laodCart: {},
    },
    data: () => ({
        total: 0,
        value_quantity: 1,
        nameSizes: '',
        size_id: 0,
        FIO: "",
        Phone: "",
        Country_code_id: 0,
        Address: "",
        CountryCodes: [],
    }),
    watch: {
        'formProduct.id'() {
            this.loadSizes();
            this.CalculateProduct();
        },
        value_quantity() {
            this.CalculateProduct();
        }
    },
    mounted() {
        this.LoadCountryCodes();
    },
    methods: {
        loadSizes() {
            let formD = new FormData;
            formD.append('product_id', this.formProduct.id);
            axios.post('/api/AvailableSizes', formD).then((response) => {
                console.log(response.data.data);
                this.nameSizes = response.data.data;
            });
        },
        LoadCountryCodes() {
            axios.get('/api/CountryCodes').then((response) => {
                console.log(response.data.data);
                this.CountryCodes = response.data.data;
            });
        },
        closeModal() {
            this.size_id = 0;
            this.value_quantity = 1;
            this.total = 0;
            this.FIO = "";
            this.Phone = "";
            this.Country_code_id = 0;
            this.Address = "";
            document.getElementById('openModal').style.opacity = 0;
            document.getElementById('openModal').style.left = '-9999px';
            document.getElementById('openModal').style.top = '-9999px';
            document.getElementById('cart_product_list').style.pointerEvents = 'auto';
        },
        InputKeyUp() {
            if (this.value_quantity != NaN) {
                this.value_quantity = this.value_quantity.replace(/\D/g, '');
            }
        },
        CalculateProduct() {
            if (this.value_quantity < 1) { this.value_quantity = 1; }
            if (this.value_quantity > 999) { this.value_quantity = 999; }
            var var_total = 0;
            var var_price = this.formProduct.price;
            var var_quantity = this.value_quantity;
            var_total = Number(var_price) * Number(var_quantity);
            this.total = var_total;
        },
        PlusQuantity() {
            this.value_quantity++;
        },
        MinusQuantity() {
            this.value_quantity--;
        },
        Buy_click() {
            var var_error = false;
            if (this.FIO == "") { this.$refs.refFIO.className = "invalid-feedback"; var_error = true; }
            else { this.$refs.refFIO.className = "d-none"; }
            if (this.Country_code_id == 0) { this.$refs.refCountry.className = "invalid-feedback"; var_error = true; }
            else { this.$refs.refCountry.className = "d-none"; }
            var var_phone = this.Phone.replace(/\D/g, '');
            if (var_phone.length != 10) { this.$refs.refPhone.className = "invalid-feedback"; var_error = true; }
            else { this.$refs.refPhone.className = "d-none"; }
            if (this.size_id == 0 && this.nameSizes.length > 0) {
                this.$refs.refSize.className = "invalid-feedback"; var_error = true;
            }
            else { this.$refs.refSize.className = "d-none"; }
            if (this.value_quantity < 1 || this.value_quantity > 999) { this.$refs.refQuantity.className = "invalid-feedback"; var_error = true; }
            else { this.$refs.refQuantity.className = "d-none"; }
            if (!var_error) {
                let formD = new FormData;
                formD.append('name', this.FIO);
                formD.append('countrycode_id', this.Country_code_id);
                formD.append('phone', var_phone);
                formD.append('address', this.Address);
                formD.append('product_id', this.formProduct.id);
                formD.append('quantity', this.value_quantity);
                formD.append('size_id', this.size_id);
                formD.append('total', this.total);
                axios.post('/api/BuyProduct', formD).then((response) => {
                    console.log(response);
                });
                let cart = localStorage.getItem('cart');
                if (cart) {
                    cart = JSON.parse(cart);
                    cart.forEach(element => {
                        if (element.id == this.formProduct.id) {
                            cart = cart.filter(element => {
                                return element.id !== this.formProduct.id
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
                }
                this.laodCart();
                this.closeModal();
                alert("Товар оформлен со статусом не оплачен, далее переход на страницу оплаты подключенной платежной системы");
            }
        },
    }
}
</script>

<style lang="scss" scoped>
</style>
