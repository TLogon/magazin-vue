<template>
    <tr>
        <td align="center" valign="middle">{{ buy.productNumber }}</td>
        <td align="center" valign="middle">{{ buy.created_at | formatDate }}</td>
        <td valign="middle">
            <div style="display: flex;">
                <div valign="middle" style="align-self: center;">
                    <img class="foto-circle-style" src="/project_files/pixel.png"
                        :style="{ backgroundImage: buy.photo_link == null ? `url(/project_files/nophoto.png)` : `url(/files/` + buy.photo_link + `)` }" />
                </div>
                <div valign="middle" style="align-self: center; padding-left: 1rem;">
                    <router-link :to="{ path: '/product/' + buy.product_id }">
                        <a>{{ buy.product_name }}</a>
                    </router-link><br>
                    <a>₸ {{ buy.product_price | formatPrice }}</a><br>
                    <a style="font-size: 0.9rem;">{{ buy.byer_name }} / {{ buy.section_name }} / {{ buy.subsection_name
                    }}</a>
                </div>
            </div>
        </td>
        <td align="center" valign="middle">
            <p class="btn-buy-admin"
                :class="[buy.status_id == 1 ? 'buy-not-paid' : buy.status_id == 2 ? 'buy-paid' : buy.status_id == 3 ? 'buy-processed' : '']">
                {{ buy.status_name }}</p>
        </td>

        <td align="center" valign="middle" v-if="status_id != 3">
            <button v-if="buy.status_id == 1" @click.prevent="deleteBuy()" class="btn fa-solid fa-trash"></button>
            <button v-if="buy.status_id == 2" @click.prevent="processedBuy()" class="btn fa-solid fa-pen"></button>
        </td>
    </tr>
</template>

<script>
import axios from "axios";


import Vue from 'vue';
import VueMask from 'v-mask';
Vue.use(VueMask);

export default {
    props: {
        buy: {
            type: Object
        },
        status_id: {
            type: Object,
            default: () => 0
        },
        laodBuy() { }
    },
    methods: {
        processedBuy() {
            var var_photo = '';
            var var_size_name = '';
            var var_country = '';
            var var_brand = '';
            if (this.buy.photo_link == null) { var_photo = "/project_files/nophoto_magazin.png"; }
            else { var_photo = "/files/" + this.buy.photo_link; }
            if (this.buy.size_name != null) {
                var_size_name = '<div class="row"><div class="col-12"><a>Размер: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.size_name + '</a></div></div>';
            }
            if (this.buy.country_name != null) {
                var_country = '<div class="row"><div class="col-12"><a>Страна: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.country_name + '</a></div></div>';
            }
            if (this.buy.brand_name != null) {
                var_brand = '<div class="row"><div class="col-12"><a>Бренд: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.brand_name + '</a></div></div>';
            }
            var code_html = '';
            code_html = code_html + '<div class="row"><div class="col-auto"><img class="foto-buy-admin-card" src="' + var_photo + '" /></div>' +
                '<div class="col" align="left">' +
                '<div class="row"><div class="col-12"><a style="font-weight: bold;">О товаре</a></div></div>' +
                '<div class="row"><div class="col-12"><a>Цена: </a><a style="font-style: italic;text-decoration: underline;">' + this.$options.filters.formatPrice(this.buy.product_price) + ' ₸</a></div></div>' +
                var_country + var_brand + var_size_name +
                '<br><div class="row"><div class="col-12"><a style="font-weight: bold;">Заказано</a></div></div>' +
                '<div class="row"><div class="col-12"><a>Количество: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.quantity + ' шт.</a></div></div>' +
                '<div class="row"><div class="col-12"><a>На сумму: </a><a style="font-style: italic;text-decoration: underline;">' + this.$options.filters.formatPrice(this.buy.total) + ' ₸</a></div></div></div></div>' +
                '<div class="row" style="padding-top: 1rem;"><div class="col-12" align="left"><a>Заказчик: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.name + '</a></div></div>' +
                '<div class="row"><div class="col-12" align="left"><a>Страна: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.country_code_name + '</a></div></div>';
            if (this.buy.phone != null) {
                code_html = code_html + '<div class="row"><div class="col-12" align="left"><a>Телефон: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.country_code_code + ' (' + this.buy.phone.slice(0, 3) + ') ' + this.buy.phone.slice(3, 6) + ' ' + this.buy.phone.slice(6, 8) + ' ' + this.buy.phone.slice(8, 10) + '</a></a></div></div>';
            }
            if (this.buy.address != null) {
                code_html = code_html + '<div class="row"><div class="col-12" align="left"><a>Адрес: </a><a style="font-style: italic;text-decoration: underline;">' + this.buy.address + '</a></div></div>';
            }
            Swal.fire({
                title: this.buy.product_name,
                html: code_html,
                width: 700,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Обработать заказ',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Нет, я передумал...',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/api/BuyAdmin/Processed/' + this.buy.id).then(response => { this.laodBuy(); })
                }
            });
        },
        deleteBuy() {
            Swal.fire({
                title: 'Заказ не оплачен, удалить этот заказ?',
                text: "Заказ \"" + this.buy.product_name + "\" будет удален и Вы не сможете его восстановить!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет, я передумал...',
                confirmButtonText: 'Да, удалить это!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/api/BuyAdmin/delete/' + this.buy.id).then(response => { this.laodBuy(); })
                }
            });
        },
    }
}
</script>

<style lang="scss" scoped>
</style>