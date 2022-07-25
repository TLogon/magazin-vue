<template>
    <div style="overflow-y: scroll; overflow-x:hidden;">
        <Spinner v-if="loading"></Spinner>
        <div class="container-md" v-else>
            <div class="row">
                <div class="col-md">
                    <button class="btn btn-md btn-success" @click.prevent="newCountry">Добавить новую страну</button>
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col" style="width: 50px;">#</th>
                                <th scope="col">Название</th>
                                <th scope="col" style="width: 110px;">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(сountrie, n) in сountries">
                                <td>{{ n + 1 }}</td>
                                <td>{{ сountrie.name }}</td>
                                <td>
                                    <button @click.prevent="editCountry(сountrie.id, сountrie.name)"
                                        class="btn fa-solid fa-pen"></button>
                                    <button @click.prevent="deleteCountry(сountrie.id, сountrie.name)"
                                        class="btn fa-solid fa-trash"></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Spinner from "../components/Spinner";
import axios from "axios";

export default {
    metaInfo: {
        title: 'Страны',
        meta: [
            { vmid: 'description', property: 'description', content: 'Страны производители товаров' },
            { vmid: 'og:title', property: 'og:title', content: 'Страны' },
            { vmid: 'og:description', property: 'og:description', content: 'Страны производители товаров' },
        ],
    },
    components: {
        Spinner
    },
    data: () => ({
        loading: true,
        сountries: []
    }),
    mounted() {
        this.laodCountries();
    },
    methods: {
        laodCountries() {
            axios.get('/api/countries')
                .then(res => {
                    this.сountries = res.data;
                    setTimeout(() => {
                        this.loading = false;
                    }, 300);
                })
        },
        newCountry() {
            Swal.fire({
                title: 'Введите название страны',
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
                        axios.post('/api/countries', { name }, {
                            headers: {
                                "Content-type": "application/json"
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {
                this.laodCountries();
            })
        },
        deleteCountry(id, name) {
            Swal.fire({
                title: 'Удалить эту страну?',
                text: "Страна " + name + " будет удалена и Вы не сможете её восстановить!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет, я передумал...',
                confirmButtonText: 'Да, удалить это!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.put('/api/countries/delete/' + id).then(response => { this.laodCountries(); })
                }
            })
        },
        editCountry(id, name) {
            Swal.fire({
                title: 'Измените название страны',
                input: 'text',
                inputValue: name,
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Сохранить',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Нет, я передумал...',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                padding: '3em',
                preConfirm: (name) => {
                    if (name != '') {
                        axios.put('/api/countries/' + id, { name }, {
                            headers: {
                                "Content-type": "application/json"
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {
                this.laodCountries();
            })
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
</style>