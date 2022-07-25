<template>
    <table class="table table-hover">
        <thead>
            <tr class="table-dark">
                <th scope="col" style="width: 50px;">#</th>
                <th scope="col" style="width: 110px;">Дата</th>
                <th scope="col">Товар</th>
                <th scope="col" style="width: 110px;">Статус</th>
                <th scope="col" style="width: 110px;" v-if="status_id != 3">Действие</th>
            </tr>
        </thead>
        <tbody>
            <BuyCardAdmin v-for="buy in buys.data" :key="buy.id" :buy="buy" :status_id="status_id" :laodBuy="laodBuy"></BuyCardAdmin>
        </tbody>
    </table>
</template>

<script>
import BuyCardAdmin from './BuyCardAdmin.vue';
export default {
    props: {
        buys: {
            type: Array,
            default: () => []
        },
        status_id: {
            type: Object,
            default: () => 0
        },
        laodBuy() {}
    },
    components: { BuyCardAdmin }
}
</script>

<style lang="scss" scoped>
</style>