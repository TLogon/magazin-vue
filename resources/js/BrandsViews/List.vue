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
                                    <a style="font-size: 1rem;">Страна</a></span>
                            </div>
                            <select id="s_country" name="s_country" class="form-select" v-model="country_id"
                                v-on:change="onChange_country(country_id)"
                                style="max-width: 250px;border-top-left-radius: 0;border-bottom-left-radius: 0;border-color: #212529; cursor: pointer;">
                                <option selected disabled value="0">Выбрать...</option>
                                <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-auto">
                    <button class="btn btn-md btn-success" @click.prevent="newBrand">Добавить новый бренд</button>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr class="table-dark">
                        <th scope="col" style="width: 50px;">#</th>
                        <th scope="col">Название</th>
                        <th scope="col" style="width: 110px;">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(brand, n) in brands">
                        <td>{{ n + 1 }}</td>
                        <td>{{ brand.name }}</td>
                        <td>
                            <button @click.prevent="editBrand(brand.id, brand.country_id, brand.name)"
                                class="btn fa-solid fa-pen"></button>
                            <button @click.prevent="deleteBrand(brand.id, brand.name)"
                                class="btn fa-solid fa-trash"></button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";

export default {
    metaInfo: {
        title: 'Бренды',
        meta: [
            { vmid: 'description', property: 'description', content: 'Бренды товаров' },
            { vmid: 'og:title', property: 'og:title', content: 'Бренды' },
            { vmid: 'og:description', property: 'og:description', content: 'Бренды товаров' },
        ],
    },
    components: {
        Spinner
    },
    data: () => ({
        loading: true,
        countries: [],
        brands: [],
        country_id: 0
    }),
    mounted() {
        this.laodCountries()
    },
    methods: {
        laodCountries() {
            axios.get('/api/countries')
                .then(res => {
                    this.countries = res.data;
                    setTimeout(() => {
                        this.loading = false;
                    }, 300);
                })
        },
        laodBrands() {
            axios.get('/api/brands/' + this.country_id).then((response) => {
                this.brands = response.data;
            })
        },
        onChange_country(country_id) {
            axios.get('/api/brands/' + country_id).then((response) => {
                this.brands = response.data;
            })
        },
        newBrand() {
            if (this.country_id <= 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Внимание!',
                    text: 'Выберите страну из списка.',
                    confirmButtonText: 'Хорошо',
                    confirmButtonColor: '#3085d6',
                })
                return;
            }
            Swal.fire({
                title: 'Введите название бренда',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Добавить',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Нет, я передумал...',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                padding: '3em',
                preConfirm: (name) => {
                    if (name != '') {
                        axios.post('/api/brands', { country_id: this.country_id, name: name }, {
                            headers: {
                                "Content-type": "application/json"
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {
                this.laodBrands();
            })
        },
        deleteBrand(id, name) {
            Swal.fire({
                title: 'Удалить этот бренд?',
                text: "Бренд " + name + " будет удален и Вы не сможете его восстановить!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет, я передумал...',
                confirmButtonText: 'Да, удалить это!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/api/brands/delete/' + id).then(response => { this.laodBrands(); })
                }
            })
        },
        editBrand(id, country_id, name) {
            var countrySelect = document.getElementById("s_country");
            var countryLenght = countrySelect.options.length;
            var i = 0;
            var select_html = '<select id="Swal_country" name="Swal_country" class="swal2-select" style="width: 80%; width: -webkit-fill-available; width: -moz-available;">';
            while (i < countryLenght - 1) {
                if (country_id == this.countries[i].id) {
                    select_html = select_html + '<option selected value="' + this.countries[i].id + '">' + this.countries[i].name + '</option>';
                }
                else {
                    select_html = select_html + '<option value="' + this.countries[i].id + '">' + this.countries[i].name + '</option>';
                }
                i++;
            }
            select_html = select_html + '</select>';
            Swal.fire({
                title: 'Измените название бренда',
                html:
                    '<input id="Swal_name" value="' + name + '" class="swal2-input" style="width: 80%; width: -webkit-fill-available; width: -moz-available;" />' + select_html,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Сохранить',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Нет, я передумал...',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: (name) => {
                    if (name != '') {
                        axios.put('/api/brands/' + id, { country_id: document.getElementById('Swal_country').value, name: document.getElementById('Swal_name').value })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {this.laodBrands();})
        }
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
    border-color: #3b1957;
    background-color: #3b1957;
    color: white;
}
</style>