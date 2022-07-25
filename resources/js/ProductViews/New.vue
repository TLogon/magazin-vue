<template>
    <div class="container-md">
        <div class="row">
            <h3 class="mb-3 text-center">Новый товар</h3>
            <div class="col-12">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-2">
                        <h5 class="my-0 fw-normal">Раздел</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 col-md-4 mb-2">
                                <select id="s_buyer" name="s_buyer" class="form-select" v-model="form.buyer_id"
                                    v-on:change="onChange_buyer(form.buyer_id)">
                                    <option selected disabled value="0">Выбрать...</option>
                                    <option v-for="buyer in buyers" :value="buyer.id">{{ buyer.name }}</option>
                                </select>
                                <div class="invalid-feedback" v-if="error_buyers">
                                    Укажите покупателя</div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6 mb-2">
                                <select id="s_section" name="s_section" class="form-select" v-model="form.section_id"
                                    v-on:change="onChange_section(form.section_id)">
                                    <option selected disabled value="0">Выбрать...</option>
                                    <option v-for="section in sections" :value="section.id">{{ section.name }}
                                    </option>
                                </select>
                                <div class="invalid-feedback" v-if="error_sections">Укажите раздел</div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <select id="s_subsection" name="s_subsection" class="form-select"
                                    v-model="form.subsection_id" v-on:change="onChange_subsection(form.subsection_id)">
                                    <option selected disabled value="0">Выбрать...</option>
                                    <option v-for="subsection in subsections" :value="subsection.id">{{ subsection.name
                                    }}</option>
                                </select>
                                <div class="invalid-feedback" v-if="error_subsections">Укажите
                                    подраздел</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-2" style="margin: 0;">
                <label for="i_name" class="form-label">Наименование товара</label>
                <input type="text" v-model.trim="form.name" v-on:input="onName(form.name)" class="form-control"
                    id="i_name" name="i_name" maxlength="100" autocomplete="off">
                <div class="invalid-feedback" v-if="error_name">{{ error_name_txt }}</div>
            </div>
            <div class="col-md-5 mb-2">
                <label for="s_country" class="form-label">Страна</label>
                <select class="form-select" id="s_country" name="s_country" v-model="form.country_id"
                    v-on:change="onChange_country(form.country_id)">
                    <option value="0">Выбрать...</option>
                    <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label for="s_brend" class="form-label">Бренд</label>
                <select id="s_brend" name="s_brend" class="form-select" v-model="form.brand_id">
                    <option value="0">Выбрать...</option>
                    <option v-for="brend in brands" :value="brend.id">{{ brend.name }}</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label for="i_price" class="form-label">Цена</label>
                <input type="text" v-model.trim="form.price" v-on:input="onPrice(form.price)" class="form-control"
                    id="i_price" name="i_price" maxlength="12" autocomplete="off">
                <div class="invalid-feedback" v-if="error_price">{{ error_price_txt }}</div>
            </div>
            <div v-if="form.section_id >= 1 && form.section_id <= 4">
                <hr class="my-4">
                <h5 class="mb-3">Размеры в наличии</h5>
                <SizesList :classcol="'col-auto'" :nameSizes="this.nameSizes" :form.section_id="0" :loadSection="-1">
                </SizesList>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12 mb-2">
                <div class="card mb-4 rounded-3 shadow-sm">
                    <div class="card-header py-2">
                        <h5 class="my-0 fw-normal">Фотогалерея</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent>
                            <label class="btn btn-primary btn-file" for="newPhoto">
                                Добавить фотографии
                                <input type="file" class="d-none" multiple @change="loadPhoto" id="newPhoto"
                                    name="newPhoto" accept="image/*">
                                <input type="file" class="d-none" @change="editPhoto" ref="refFile" id="editPhoto"
                                    name="editPhoto" accept="image/*">
                            </label>
                        </form>
                        <div id="div_photo" class="row" style="padding-top: 1rem;">
                            <div :id="'col_' + n" class="col-auto" v-for="(img, n) in photoView"
                                style="position: relative;">
                                <img :id="'photo_' + n" class="foto-style" src="/project_files/pixel.png"
                                    :style="{ backgroundImage: `url(${img})` }" />
                                <button :id="'editPhoto_' + n" @click.prevent="editPhotoN(n)"
                                    class="fa-solid fa-pen admin-photo-edit"></button>
                                <button :id="'deletePhoto_' + n" @click.prevent="deletePhoto(n)"
                                    class="fa-solid fa-trash admin-photo-delete"></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-4" />
        <button class="w-100 btn btn-success btn-lg mb-3" @click="addProduct">
            <div class="spinner-border spinner-border-sm" role="status" v-if="loading"></div>
            <span v-else>Добавить</span>
        </button>
    </div>
</template>

<script>
import axios from 'axios';
import SizesList from '../components/SizesList';
export default {
    metaInfo: {
        title: 'Новый товар',
        meta: [
            { vmid: 'description', property: 'description', content: 'Добавить новый товар' },
            { vmid: 'og:title', property: 'og:title', content: 'Новый товар' },
            { vmid: 'og:description', property: 'og:description', content: 'Добавить новый товар' },
        ],
    },
    components: { SizesList },
    data: () => ({
        form: {
            buyer_id: 0,
            section_id: 0,
            subsection_id: 0,
            country_id: 0,
            brand_id: 0,
            name: "",
            price: "",
            size: [],
        },
        nameSizes: [],
        photo: [],
        photoView: [],
        buyers: [],
        sections: [],
        subsections: [],
        countries: [],
        brands: [],
        loading: false,
        error_buyers: false,
        error_sections: false,
        error_subsections: false,
        error_name: false,
        error_price: false,
        error_name_txt: "Введите наименование товара",
        error_price_txt: "Вветите цену товара",
        editN: -1,
    }),
    mounted() {
        this.laodBuyer();
        this.laodCountries();
    },
    methods: {
        onName(e) {
            if (this.form.name.trim() == "") {
                this.error_name_txt = "Введите наименование товара";
                this.error_name = true;
            }
            else if (this.form.name.trim().length < 5) {
                this.error_name_txt = "Наименование товара должно быть не менее 5 символов";
                this.error_name = true;
            }
            else { this.error_name = false; }
        },
        onPrice(e) {
            var e_price = e.trim();
            if (e_price == "") {
                this.error_price_txt = "Вветите цену товара";
                this.error_price = true;
            }
            else if (isNaN(e_price)) {
                this.error_price_txt = "Цена товара должна быть числом";
                this.error_price = true;
            }
            else if (parseFloat(e_price) <= 0) {
                this.error_price_txt = "Цена товара должна быть больше 0";
                this.error_price = true;
            }
            else { this.error_price = false; }
        },
        editPhotoN(e) {
            this.editN = e;
            this.$refs.refFile.click();
        },
        loadPhoto(e) {
            let files = e.target.files;
            if (!files.length) { return false; }
            for (let i = 0; i < files.length; i++) {
                var re = /(\.jpg|\.jpeg|\.bmp|\.png)$/i;
                if (!re.exec(files[i].name)) {
                    alert(files[i].name + err_filetype);
                } else {
                    this.photo.push(files[i]);
                    this.photoView.push(URL.createObjectURL(files[i]));
                }
            }
            console.log(this.photo);
        },
        editPhoto(e) {
            let files = e.target.files[0];
            var validPhoto = /(\.jpg|\.jpeg|\.png|\.bmp)$/i;
            if (validPhoto.exec(files.name)) {
                this.photo.splice(this.editN, 1, files);
                this.photoView.splice(this.editN, 1, URL.createObjectURL(files));
            }
        },
        deletePhoto(e) {
            var idPhoto = e;
            this.photo.splice(idPhoto, 1);
            this.photoView.splice(idPhoto, 1);
        },
        laodBuyer() {
            axios.get('/api/GetBuyers')
                .then(res => {
                    this.buyers = res.data;
                })
        },
        laodCountries() {
            axios.get('/api/countries')
                .then(res => {
                    this.countries = res.data;
                })
        },
        onChange_buyer(buyer_id) {
            if (buyer_id == 0) { this.error_buyers = true; }
            else { this.error_buyers = false; }
            axios.get('/api/GetSections/' + buyer_id).then((response) => {
                this.sections = response.data
            }),
                this.form.section_id = 0;
            this.form.subsection_id = 0;
            this.subsections = '';
        },
        onChange_section(section_id) {
            if (section_id == 0) { this.error_sections = true; }
            else {
                this.error_sections = false;
                let formD = new FormData;
                formD.append('section_id', section_id);
                axios.post('/api/GetSizes/', formD).then((response) => {
                    console.log(response.data.data);
                    this.nameSizes = response.data.data;
                });
                this.form.brand_id = 0;
            }
            axios.get('/api/GetSubSections/' + section_id).then((response) => {
                this.subsections = response.data
            });
            this.form.subsection_id = 0;
        },
        onChange_subsection(subsection_id) {
            if (subsection_id == 0) { this.error_subsections = true; }
            else { this.error_subsections = false; }
        },
        onChange_country(country_id) {
            axios.get('/api/brands/' + country_id).then((response) => {
                this.brands = response.data
            }),
                this.form.brand_id = 0;
        },
        addProduct() {
            this.loading = true;
            var err = false;
            if (this.form.buyer_id == 0) {
                err = true;
                this.error_buyers = true;
            }
            else { this.error_buyers = false; }
            if (this.form.section_id == 0) {
                err = true;
                this.error_sections = true;
            }
            else { this.error_sections = false; }
            if (this.form.subsection_id == 0) {
                err = true;
                this.error_subsections = true;
            }
            else { this.error_subsections = false; }
            if (this.form.name.trim() == "") {
                this.error_name_txt = "Введите наименование товара";
                this.error_name = true;
                err = true;
            }
            else if (this.form.name.trim().length < 5) {
                this.error_name_txt = "Наименование товара должно быть не менее 5 символов";
                this.error_name = true;
                err = true;
            }
            else { this.error_name = false; }
            if (this.form.price.trim() == "") {
                this.error_price_txt = "Вветите цену товара";
                this.error_price = true;
                err = true;
            }
            else if (isNaN(this.form.price.trim())) {
                this.error_price_txt = "Цена товара должна быть числом";
                this.error_price = true;
                err = true;
            }
            else if (parseFloat(this.form.price.trim()) <= 0) {
                this.error_price_txt = "Цена товара должна быть больше 0";
                this.error_price = true;
                err = true;
            }
            else { this.error_price = false; }
            if (err) {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                Toast.fire({
                    icon: 'error',
                    title: 'Заполните данные товара!'
                })
                this.loading = false;
                return;
            }
            var i = 1;
            var size_lengths = 0;
            let formD = new FormData;
            formD.append('buyer_id', this.form.buyer_id);
            formD.append('section_id', this.form.section_id);
            formD.append('subsection_id', this.form.subsection_id);
            formD.append('country_id', this.form.country_id);
            formD.append('brand_id', this.form.brand_id);
            formD.append('name', this.form.name);
            formD.append('price', this.form.price);
            for (let i = 0; i < this.nameSizes.length; i++) {
                if (document.getElementById('ch_size_' + this.nameSizes[i].id).checked == true) {
                    formD.append('sizes[' + i + '][]', this.nameSizes[i].id);
                    formD.append('sizes[' + i + '][]', this.nameSizes[i].name);
                }
            }
            for (let i = 0; i < this.photo.length; i++) {
                formD.append('photo[]', this.photo[i]);
            }
            axios.post('/api/products', formD).then((response) => {
                console.log(response);
                if (response.data == "errorFormat") {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'error',
                        title: 'Проверьте введенные данные!'
                    })
                }
                else {
                    this.form.country_id = 0;
                    this.brands = [];
                    this.form.brand_id = 0;
                    this.form.name = "";
                    this.form.price = "";
                    this.form.size = [];
                    this.photo = [];
                    this.photoView = [];
                    for (let i = 0; i < this.nameSizes.length; i++) {
                        if (document.getElementById('ch_size_' + this.nameSizes[i].id).checked == true) {
                            document.getElementById('ch_size_' + this.nameSizes[i].id).checked = false;
                        }
                    }
                    document.getElementById('div_photo').innerHTML = '';
                    i = 1;
                    while (i <= size_lengths) {
                        document.getElementById('ch_size_' + i).checked = false;
                        this.form.size[i] = false;
                        i++;
                    }
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({
                        icon: 'success',
                        title: 'Товар успешно добавлен!'
                    })
                }
                this.loading = false;
            })
        }
    }
}
</script>

<style scoped>
.container-md {
    padding-top: 1rem;
}
</style>