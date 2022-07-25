<template>
    <main class="form-signin text-center">
        <form class="form-input">
            <div class="container-fluid container-input">
                <div class="col-md-4 offset-md-4">
                    <div class="form-container">
                        <div class="form-icon"><i class="fa fa-user"></i></div>
                        <h3 class="title">Авторизация</h3>
                        <div class="alert alert-danger" role="alert" v-if="error">
                            {{ error_text }}
                        </div>
                        <form class="form-horizontal">
                            <div class="form-group">
                                <label>Логин</label>
                                <input v-model="name" class="form-control" type="text" placeholder=""
                                    autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label>Пароль</label>
                                <input v-model="password" class="form-control" type="password" placeholder=""
                                    autocomplete="off">
                            </div>
                            <button type="button" class="btn btn-default" @click.prevent="login">Войти</button>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </main>
</template>
        
<script>
import axios from "axios";

export default {
    metaInfo: {
        title: 'Авторизация',
        meta: [
            { vmid: 'description', property: 'description', content: 'Авторизация администратора' },
            { vmid: 'og:title', property: 'og:title', content: 'Авторизация' },
            { vmid: 'og:description', property: 'og:description', content: 'Авторизация администратора' },
        ],
    },
    data: () => ({
        name: null,
        password: null,
        error: false,
        error_text: ""
    }),
    mounted() {

    },
    methods: {
        login() {
            if (this.name == "" || this.password == "") {
                this.error_text = "Введите логин и пароль";
                this.error = true;
                return;
            }
            else { this.error = false; }
            axios.get('/sanctum/csrf-cookie').then(response => {
                axios.post('/login', { name: this.name, password: this.password })
                    .then(res => {
                        localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN']);
                        this.error = false;
                        this.$router.push({ name: 'products.list' });
                    })
                    .catch(err => {
                        console.log(err.response);
                        this.error_text = "Неверный логин или пароль";
                        this.error = true;
                    })
            });
        }
    }
}
</script>

<style scoped>
html,
form {
    width: 100%;
}

.form-container {
    background: #ecf0f3;
    font-family: 'Nunito', sans-serif;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 14px 14px 20px #cbced1, -14px -14px 20px white;
}

.form-container .form-icon {
    color: #ac40ab;
    font-size: 55px;
    text-align: center;
    line-height: 100px;
    width: 100px;
    height: 100px;
    margin: 0 auto 15px;
    border-radius: 50px;
    box-shadow: 7px 7px 10px #cbced1, -7px -7px 10px #fff;
}

.form-container .title {
    color: #ac40ab;
    font-size: 25px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    text-align: center;
    margin: 0 0 20px;
}

.form-container .form-horizontal .form-group {
    margin: 0 0 25px 0;
}

.form-container .form-horizontal .form-group label {
    font-size: 15px;
    font-weight: 600;
    text-transform: uppercase;
}

.form-container .form-horizontal .form-control {
    color: #333;
    background: #ecf0f3;
    font-size: 15px;
    height: 50px;
    padding: 20px;
    letter-spacing: 1px;
    border: none;
    border-radius: 50px;
    box-shadow: inset 6px 6px 6px #cbced1, inset -6px -6px 6px #fff;
    display: inline-block;
    transition: all 0.3s ease 0s;
}

.form-container .form-horizontal .form-control:focus {
    box-shadow: inset 6px 6px 6px #cbced1, inset -6px -6px 6px #fff;
    outline: none;
}

.form-container .form-horizontal .form-control::placeholder {
    color: #808080;
    font-size: 14px;
}

.form-container .form-horizontal .btn {
    color: #000;
    background-color: #ac40ab;
    font-size: 15px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%;
    padding: 12px 15px 11px;
    border-radius: 20px;
    box-shadow: 6px 6px 6px #cbced1, -6px -6px 6px #fff;
    border: none;
    transition: all 0.5s ease 0s;
}

.form-container .form-horizontal .btn:hover,
.form-container .form-horizontal .btn:focus {
    color: #fff;
    letter-spacing: 3px;
    box-shadow: none;
    outline: none;
}

.form-input {
    width: 100%;
    height: 100%;
    display: table;
    position: absolute;
    top: 0;
    left: 0;
}

.container-input {
    display: table-cell;
    text-align: center;
    vertical-align: middle;
}
</style>