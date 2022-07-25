<template>
    <div :class="classcol">
        <div class="form-check" v-if="section_id == loadSection">
            <input type="checkbox" class="form-check-input" :id="'ch_size_' + nameSize.id"
                :name="'ch_size_' + nameSize.id" v-if="size_checked == true" checked />
            <input type="checkbox" class="form-check-input" :id="'ch_size_' + nameSize.id"
                :name="'ch_size_' + nameSize.id" v-else-if="size_checked == false" />
            <label class="form-check-label" :for="'ch_size_' + nameSize.id">{{ nameSize.name }}</label>
        </div>
        <div class="form-check" v-else-if="section_id != loadSection">
            <input type="checkbox" class="form-check-input" :id="'ch_size_' + nameSize.id"
                :name="'ch_size_' + nameSize.id">
            <label class="form-check-label" :for="'ch_size_' + nameSize.id">{{ nameSize.name }}</label>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        nameSize: {
            type: Object
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
    data: () => ({
        size_checked: false,
    }),
    mounted() {
        this.checkSize();
    },
    methods: {
        checkSize() {
            for (let i = 0; i < this.size.length; ++i) {
                if (this.size[i].size_id == this.nameSize.id) {
                    this.size_checked = true;
                }
            }
        }
    },
}
</script>

<style lang="scss" scoped>
</style>