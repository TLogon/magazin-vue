<template>
    <div class="d-flex flex-column flex-shrink-0 p-3 text-white" style="width: 280px; background-color: #3b1957;">
        <router-link :to="'/'" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none"
            aria-current="page">
            <img style="width: 90px;" src="/project_files/logotip-magazina.png" alt="Одевайка">
            <span class="fs-4"
                style="text-align: center; text-transform: uppercase;font-family: 'Rubik Bubbles', cursive; color: #d4bce8;">магазин
                одежды</span>
        </router-link>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item" v-for="link in links">
                <router-link :to="link.href" class="nav-link"
                    :class="(link.href == $route.path || link.href + '/' == $route.path) ? 'nav-link active' : 'nav-link'"
                    aria-current="page">
                    {{ link.title }}
                </router-link>
            </li>
        </ul>
        <hr>
        <button type="button" class="btn btn-light" @click.prevent="logout">Выход</button>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            thislink: null,
            links: [
                {
                    title: "Товары",
                    href: "/admin/products"
                },
                {
                    title: "Заказы",
                    href: "/admin/orders"
                },
                {
                    title: "Страны",
                    href: "/admin/countries"
                },
                {
                    title: "Бренды",
                    href: "/admin/brands"
                },
                {
                    title: "Администраторы",
                    href: "/admin/users"
                }
            ]
        }
    },
    mounted() {
        this.activeSlot();
    },
    methods: {
        logout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token');
                    this.$router.push({ name: 'admin.index' });
                })
        },
        activeSlot() {
            this.thislink = this.$route.path;
        }
    }
}
</script>

<style scoped>
.nav-link {
    color: white;
}

.nav-link:hover {
    color: #9cc4ff;
}

.active {
    color: white !important;
}

.active:hover {
    color: white !important;
}
</style>