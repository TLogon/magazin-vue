<template>
    <div class="row card-favcart">
        <div class="col-auto" style="padding: 0; margin: 0;">
            <img src="/project_files/pixel.png" class="foto-card-favcart"
                :style="{ backgroundImage: product.photo_link == null ? `url(/project_files/nophoto_magazin.png)` : `url(/files/` + product.photo_link + `)` }">
        </div>
        <div class="col" style="padding: 10px;">
            <router-link :to="{ path: '/product/' + product.id }">
                <h6 class="text-card">{{ product.name }}</h6>
            </router-link>
            <p class="product-price">₸ {{ product.price | formatPrice }}</p>
        </div>
        <div class="col-auto align-self-end" style="padding: 0; margin: 0;">
            <button @click.prevent="deleteFavourites(product.id)" class="btn fa-solid fa-trash"
                style="color: #dc0345"></button>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        product: {
            type: Object
        },
        laodFavourites: {},
    },
    methods: {
        deleteFavourites(id) {
            let favourites = localStorage.getItem('favourites');
            if (favourites) {
                favourites = JSON.parse(favourites);
                favourites.forEach(element => {
                    if (element.id == id) {
                        favourites = favourites.filter(element => {
                            return element.id !== id
                        });
                        var number_favourite = document.getElementById("favourite").innerText;
                        number_favourite = Number(number_favourite) - 1;
                        document.getElementById("favourite").innerText = number_favourite;

                        if (number_favourite > 0) {
                            document.getElementById("favourite").style.display = "inline";
                        }
                        else {
                            document.getElementById("favourite").style.display = "none";
                        }
                    }
                });
                localStorage.setItem('favourites', JSON.stringify(favourites));
                this.laodFavourites();
            }
        },
    }
}
</script>

<style lang="scss" scoped>
</style>