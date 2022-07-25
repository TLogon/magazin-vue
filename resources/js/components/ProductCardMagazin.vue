<template>
    <div class="col-auto d-flex justify-content-center">
        <div class="owl-item cloned active" style="width: 190px;">
            <div class="single-product-wrapper">
                <div class="product-img">
                    <img src="/project_files/pixel.png" class="foto-card-style"
                        :style="{ backgroundImage: product.photo_link == null ? `url(/project_files/nophoto_magazin.png)` : `url(/files/` + product.photo_link + `)` }">
                    <div class="product-favourite">
                        <a @click.prevent="addFavourites(product.id)" :ref="`favourite_${product.id}`"
                            :class="!loadFavourites(product.id) ? 'favme fa fa-heart fa-off' : 'favme fa fa-heart fa-on'"></a>
                    </div>
                </div>
                <div class="product-description">
                    <router-link :to="{ path: '/product/' + product.id }">
                        <h6 class="text-card">{{ product.name }}</h6>
                    </router-link>
                    <p class="product-price">₸ {{ product.price | formatPrice }}</p>
                    <div class="hover-content">
                        <div class="add-to-cart-btn">
                            <a @click.prevent="addCart(product.id)" :id="`cart_${product.id}`"
                                :ref="`cart_${product.id}`"
                                :class="!loadCart(product.id) ? 'btn essence-btn' : 'btn essence-btn essence-btn-on'">{{
                                        !loadCart(product.id)
                                            ? 'В корзину' : 'В корзине'
                                }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        product: {
            type: Object
        },
    },
    methods: {
        addCart(id) {
            let cart = localStorage.getItem('cart');
            let newProduct = [
                {
                    'id': id
                }
            ];
            if (!cart) {
                localStorage.setItem('cart', JSON.stringify(newProduct))
            }
            else {
                cart = JSON.parse(cart);
                cart.forEach(element => {
                    if (element.id == id) {
                        cart = cart.filter(element => {
                            return element.id !== id
                        })
                        newProduct = null;
                    }
                });
                Array.prototype.push.apply(cart, newProduct);
                localStorage.setItem('cart', JSON.stringify(cart));
            }
            var number_cart = document.getElementById("cart").innerText;
            if (newProduct) {
                this.$refs["cart_" + id].innerText = 'В корзине';
                this.$refs["cart_" + id].className = 'btn essence-btn essence-btn-on';
                number_cart = Number(number_cart) + 1;
                document.getElementById("cart").innerText = number_cart;
                document.getElementById("cart").style.display = "inline";
            }
            else {
                this.$refs["cart_" + id].innerText = 'В корзину';
                this.$refs["cart_" + id].className = 'btn essence-btn essence-btn-off';
                number_cart = Number(number_cart) - 1;
                document.getElementById("cart").innerText = number_cart;
                if (number_cart > 0) {
                    document.getElementById("cart").style.display = "inline";
                }
                else {
                    document.getElementById("cart").style.display = "none";
                }
            }
        },
        addFavourites(id) {
            let favourites = localStorage.getItem('favourites');
            let newProduct = [
                {
                    'id': id
                }
            ];
            if (!favourites) {
                localStorage.setItem('favourites', JSON.stringify(newProduct));
            }
            else {
                favourites = JSON.parse(favourites);
                favourites.forEach(element => {
                    if (element.id == id) {
                        favourites = favourites.filter(element => {
                            return element.id !== id
                        })
                        newProduct = null;
                    }
                });
                Array.prototype.push.apply(favourites, newProduct);
                localStorage.setItem('favourites', JSON.stringify(favourites));
            }
            var number_favourite = document.getElementById("favourite").innerText;
            if (newProduct) {
                this.$refs["favourite_" + id].className = 'favme fa fa-heart fa-on';
                number_favourite = Number(number_favourite) + 1;
                document.getElementById("favourite").innerText = number_favourite;
                document.getElementById("favourite").style.display = "inline";
            }
            else {
                this.$refs["favourite_" + id].className = 'favme fa fa-heart fa-off';
                number_favourite = Number(number_favourite) - 1;
                document.getElementById("favourite").innerText = number_favourite;
                if (number_favourite > 0) {
                    document.getElementById("favourite").style.display = "inline";
                }
                else {
                    document.getElementById("favourite").style.display = "none";
                }
            }
        },
        loadCart(cart_id) {
            let cart = localStorage.getItem('cart');
            if (cart) {
                cart = JSON.parse(cart);
                return cart.filter(n => n.id === cart_id).length === 0 ? false : true; // false
            }
        },
        loadFavourites(favourite_id) {
            let favourites = localStorage.getItem('favourites');
            if (favourites) {
                favourites = JSON.parse(favourites);
                return favourites.filter(n => n.id === favourite_id).length === 0 ? false : true; // false
            }
        },
    }
}
</script>

<style lang="scss" scoped>
</style>













