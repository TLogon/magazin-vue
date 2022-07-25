<template>
    <div style="overflow-y: scroll; overflow-x:hidden;">
        <Spinner v-if="loading"></Spinner>
        <div class="container-md" v-else>
            <div class="row">
                <div class="col-md">
                    <button class="btn btn-md btn-success" @click.prevent="newUser">Добавить нового
                        администратора</button>
                    <table class="table table-hover">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col" style="width: 50px;">#</th>
                                <th scope="col" style="width: 120px;">Дата</th>
                                <th scope="col">Имя администратора</th>
                                <th scope="col" style="width: 110px;">Действие</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, n) in users">
                                <td>{{ n + 1 }}</td>
                                <td>{{ user.created_at | formatDate }}</td>
                                <td>{{ user.name }}</td>
                                <td>
                                    <button @click.prevent="editUser(user.id, user.name)"
                                        class="btn fa-solid fa-pen"></button>
                                    <button @click.prevent="deleteUser(user.id, user.name)"
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
        title: 'Администраторы',
        meta: [
            { vmid: 'description', property: 'description', content: 'Управление администраторами сайта' },
            { vmid: 'og:title', property: 'og:title', content: 'Администраторы' },
            { vmid: 'og:description', property: 'og:description', content: 'Управление администраторами сайта' },
        ],
    },
    components: {
        Spinner
    },
    data: () => ({
        loading: true,
        users: []
    }),
    mounted() {
        this.laodUsers();
    },
    methods: {
        laodUsers() {
            axios.get('/api/users')
                .then(res => {
                    this.users = res.data;
                    setTimeout(() => {
                        this.loading = false;
                    }, 300);
                })
        },
        newUser() {
            Swal.fire({
                title: 'Новый администратор',
                html:
                    '<input type="text" id="user_name" class="swal2-input" autocomplete="off" placeholder="Логин" maxlength="50">' +
                    '<input type="password" id="user_password" class="swal2-input" autocomplete="off" placeholder="Пароль" maxlength="100">',
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Добавить',
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Нет, я передумал...',
                cancelButtonColor: '#d33',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    var user_name = document.getElementById('user_name').value;
                    var user_password = document.getElementById('user_password').value;
                    if (user_name != '' && user_password != '') {
                        axios.post('/api/register', { name: user_name, password: user_password, password_confirmation: user_password }).then(response => { })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {
                this.laodUsers();
            })
        },
        deleteUser(id, name) {
            Swal.fire({
                title: 'Удалить этого администратора?',
                text: "Администратор " + name + " будет удалена и Вы не сможете её восстановить!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Нет, я передумал...',
                confirmButtonText: 'Да, удалить!',
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('/api/users/' + id, {
                        _method: 'DELETE'
                    }).then(response => {
                            console.log(response);
                            this.laodUsers();
                        })
                }
            })
        },
        editUser(id, name) {
            Swal.fire({
                title: 'Введите новый пароль для администратора ' + name,
                input: 'text',
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
                preConfirm: (password) => {
                    if (password != '') {
                        axios.put('/api/users/' + id, { password }, {
                            headers: {
                                "Content-type": "application/json"
                            }
                        })
                    }
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then(response => {
                this.laodUsers();
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