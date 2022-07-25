<template>
    <tr>
        <td align="center" valign="middle">{{ product.productNumber }}</td>
        <td valign="middle">
            <div style="display: flex;">
                <div valign="middle" style="align-self: center;">
                    <img class="foto-circle-style" src="/project_files/pixel.png"
                        :style="{ backgroundImage: product.photo_link == null ? `url(/project_files/nophoto.png)` : `url(/files/` + product.photo_link + `)` }" />
                </div>
                <div valign="middle" style="align-self: center; padding-left: 1rem;">
                    {{ product.name }} <br> <a style="font-size: 0.9rem;">
                        {{ product.byer_name }} / {{ product.section_name }} / {{ product.subsection_name }}</a>
                </div>
            </div>
        </td>
        <td align="center" valign="middle">{{ product.price | formatPrice }}</td>
        <td align="center" valign="middle">
            <router-link :to="{ path: '/admin/products/edit/' + product.id }">
                <i class="btn fa-solid fa-pen"></i>
            </router-link>
            <router-link :to="{ path: '/product/' + product.id }">
                <i class="btn fa-solid fa-eye"></i>
            </router-link>
            <button @click.prevent="deleteProduct(product.id, product.name)" class="btn fa-solid fa-trash"></button>
        </td>
    </tr>
</template>

<script>
export default {
    props: {
        product: {
            type: Object
        },
        deleteProduct:{}
    },
    methods: {}
}
</script>

<style lang="scss" scoped>
</style>