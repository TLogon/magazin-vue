<template>
    <div class="row">
       <SizeCard v-for="(nameSize, n) in nameSizes" :key="nameSize.id" :nameSize="nameSize" 
       :size="size" :section_id="section_id" :loadSection="loadSection" :classcol="classcol" ></SizeCard>
    </div>
</template>

<script>
import SizeCard from './SizeCard'

export default {
    props: {
        nameSizes: {
            type: Array,
            default: () => []
        },
        size: {
            type: Array,
            default: () => []
        },
        section_id: {
            type: Number,
            default: () => []
        },
        loadSection: {
            type: Number,
            default: () => []
        },
        classcol: {},
    },
    components: { SizeCard }
}
</script>

<style lang="scss" scoped>
</style>