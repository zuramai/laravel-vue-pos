<template>
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">Produk</h4>
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div>
                <!-- end page-title-box -->
            </div>
        </div> 
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-8">
                                <h4 class="mt-0 header-title">Daftar Produk</h4>
                            </div>
                            
                            <div class="col-4">

                                <div class="float-right d-none d-md-block">
                                    <div class="dropdown">
                                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalAdd">
                                            <i class="ti-plus mr-1"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="offset-md-8 col-md-4 col-sm-12">
                                <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
                                </div>
                                    <input type="text" class="form-control" placeholder="Cari Nama Kategori" aria-label="Cari Nama Kategori" aria-describedby="basic-addon1" v-model="search" @keyup="searchData">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-lg" id='category-table'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="product in products" v-bind:key='product.id'>
                                        <td>{{ product.id }}</td>
                                        <td><b>{{ product.code }}</b></td>

                                        <td>
                                            <img :src="`/images/products/${product.image_name}`" alt="Gambar" class='image-table'>
                                            <span>{{ product.name }}</span>
                                        </td>
                                        
                                        <td>{{ product.stock }}</td>
                                        <td>Rp {{ formatPrice(product.price) }}</td>
                                        <td>
                                            <button type="button" class='btn btn-warning' @click="editProduct(product.id)">Edit</button>
                                            <button type="button" @click="deleteProduct(product.id)" class="btn btn-danger">Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <nav aria-label="Page navigation example" class="float-right">
                                <ul class="pagination">
                                    <li class="page-item"><button class="page-link" href="#" v-if="this.current_page !== this.first_page" @click="prevPage">Previous</button></li>
                                    <li class="page-item"><button class="page-link" href="#">{{ this.current_page }}</button></li>
                                    <li class="page-item"><button class="page-link" href="#" @click="nextPage" v-if="this.current_page !== this.last_page">Next</button></li>
                                </ul>
                            </nav>
                    </div>
                </div>
            </div>

        </div>
        <!-- Add Modal -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="addProduct">
                        
                        <div class="modal-body">
                                <div class="alert alert-danger" v-if="errors != ''" >
                                    <ul>
                                        <li v-for="error in errors" v-bind:key="error.id">{{ error[0] }}</li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama Produk</label>
                                    <input id="name" type="text"
                                        class="form-control" name="name" autofocus
                                        placeholder="Nama produk" v-model='add.name'>
                                </div>
                                <div class="form-group">
                                    <label for="code">Kode Produk</label>
                                    <input id="code" type="text"
                                        class="form-control" name="code" autofocus
                                        placeholder="Kode produk" v-model='add.code'>
                                
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Kategori</label>
                                    <select name="category" id="" class='form-control' v-model='add.category'>
                                        <option value="">Pilih salah satu..</option>
                                        <option :value="category.id" v-for="category  in this.categories" :key="category.id">{{ category.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Deskripsi</label>
                                    <textarea name="description" id="" cols="30" rows="5" class="form-control" v-model="add.description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="email">Stock</label>
                                    <input id="stock" type="number" class="form-control"
                                        name="stock" placeholder="Stok barang" v-model="add.stock">
                             
                                </div>
                                <div class="form-group">
                                    <label for="email">Harga</label>
                                    <input id="price" type="number" class="form-control"
                                        name="price" placeholder="Harga" v-model="add.price" >
                                </div>
                                <div class="form-group">
                                    <label for="email">PPN</label>
                                    <input id="ppn" type="number" class="form-control"
                                        name="ppn" placeholder="PPN (Dalam %)" v-model="add.ppn" >
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" name='file' id="inputGroupFile01" @change="onChangePhotoUpload" aria-describedby="inputGroupFileAddon01" multiple accept='image/*'>
                                            <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
                                        </div> 
                                    </div>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Edit Modal -->
        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="updateProduct(edit.id)">
                        
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="errors != ''" >
                                <ul>
                                    <li v-for="error in errors" v-bind:key="error.id">{{ error[0] }}</li>
                                </ul>
                            </div>
                            <div class="form-group">
                                <label for="name">Nama Produk</label>
                                <input id="name" type="text"
                                    class="form-control" name="name" autofocus
                                    placeholder="Nama produk" v-model='edit.name'>
                            </div>
                            <div class="form-group">
                                <label for="code">Kode Produk</label>
                                <input id="code" type="text"
                                    class="form-control" name="code" autofocus
                                    placeholder="Kode produk" v-model='edit.code'>
                            
                            </div>
                            <div class="form-group">
                                <label for="last_name">Kategori</label>
                                <select name="category" id="" class='form-control' v-model='edit.category'>
                                    <option value="">Pilih salah satu..</option>
                                    <option :value="category.id" v-for="category  in this.categories" :key="category.id">{{ category.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Deskripsi</label>
                                <textarea name="description" id="" cols="30" rows="5" class="form-control" v-model="edit.description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="email">Stock</label>
                                <input id="stock" type="number" class="form-control"
                                    name="stock" placeholder="Stok barang" v-model="edit.stock">
                            
                            </div>
                            <div class="form-group">
                                <label for="email">Harga</label>
                                <input id="price" type="number" class="form-control"
                                    name="price" placeholder="Harga" v-model="edit.price" >
                            </div>
                            <div class="form-group">
                                <label for="email">PPN</label>
                                <input id="price" type="number" class="form-control"
                                    name="ppn" placeholder="Harga" v-model="edit.ppn" >
                            </div>
                            <div class="form-group">
                                <label for="">Gambar</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name='file' id="inputGroupFile01" @change="onChangePhotoEdit" aria-describedby="inputGroupFileAddon01" multiple accept='image/*'>
                                        <label class="custom-file-label" for="inputGroupFile01">Pilih gambar</label>
                                    </div> 
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- end row -->            

    </div><!-- container fluid -->

</template>
<script>
import Swal from 'sweetalert2'

export default {
    mounted() {
        this.displayData(this.page, this.search);
        this.getCategories();
        console.log(this.categories);
    },

    data() {
        return {
            edit: {
                id: '',
                name: '',
                category: '',
                code: '',
                price: '',
                description: '',
                image_name: '',
                photo: '',
                ppn:'',
            },
            add: {
                name: '',
                description: '',
                price: '',
                stock: '',
                category: '',
                photo: '',
                image_name: '',
                ppn:'',
            },
            search: '',
            description: '',
            search: '',
            products: [],
            categories: [],
            errors: [],
            
            page: 1,
            first_page: 1,
            last_page: null,
            current_page: this.$route.query.page || 1, 
            next_page_url: '',
            prev_page_url: '',
        }
    },

    methods: {  
        displayData(page = 1, search= '') {
            axios.get('/api/v1/product', { params: { search: this.search, page: this.page } })
                .then(result => {
                    console.log(result.data);
                    this.products = result.data.data;
                    this.last_page = result.data.meta.last_page;
                    this.current_page = result.data.meta.current_page;
                    this.next_page_url = result.data.links.next;
                    this.prev_page_url = result.data.links.prev;
                });
        },

        addProduct() {
            let name = this.add.name;
            let description = this.add.description;
            let code = this.add.code;
            let category = this.add.category;
            let price = this.add.price;
            let ppn = this.add.ppn;
            let stock = this.add.stock;
            let photo = this.add.photo

            let formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('code', code);
            formData.append('category', category);
            formData.append('price', price);
            formData.append('ppn', ppn);
            formData.append('stock', stock);
            formData.append('photo', photo);


            axios.post('/api/v1/product', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(res => {
                    console.log(res);
                    this.displayData();
                    $('#modalAdd').modal('toggle');
                    Swal.fire(
                        `Sukses!`,
                        `Sukses tambah produk ${this.add.name}!`,
                        'success'
                    )
                    this.add.name = '';
                    this.add.description = '';
                    this.add.code = '';
                    this.add.category = '';
                    this.add.price = '';
                    this.add.stock = '';
                    this.add.photo = '';
                    this.errors = [];

                }).catch(err => {
                    console.log(err.response.data);
                    if(err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }else if(err.response.status == 500) {
                        this.errors = [['System error']];
                    }
                });
        },

        editProduct(id) {
            let name = this.edit.name;
            let description = this.edit.description;
            let code = this.edit.code;
            let category = this.edit.category;
            let price = this.edit.price;
            let stock = this.edit.stock;
            let photo = this.edit.photo;

            axios.get(`/api/v1/product/${id}/edit`)
                .then(res => {
                    console.log(res.data);
                    
                    this.edit.id = res.data.id;
                    this.edit.name = res.data.name;
                    this.edit.code = res.data.code;
                    this.edit.description = res.data.description;
                    this.edit.category = res.data.category_id;
                    this.edit.price = res.data.price;
                    this.edit.stock = res.data.stock;
                    this.edit.photo = res.data.image_name;

                    $("#modalEdit").modal('toggle');
                }).catch(err => {
                    console.log(err.response);
                })

        },

        updateProduct(id) {
            let name = this.edit.name;
            let description = this.edit.description;
            let code = this.edit.code;
            let category = this.edit.category;
            let price = this.edit.price;
            let stock = this.edit.stock;
            let photo = this.edit.photo

            let formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('code', code);
            formData.append('category', category);
            formData.append('price', price);
            formData.append('stock', stock);
            formData.append('photo', photo);

            console.log(id);
            axios.post(`/api/v1/product/${id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(res => {
                    console.log(res);
                    this.displayData();
                    $('#modalEdit').modal('toggle');
                    Swal.fire(
                        `Sukses!`,
                        `Sukses edit produk ${this.edit.name}!`,
                        'success'
                    )
                    this.edit.name = '';
                    this.edit.description = '';
                    this.edit.code = '';
                    this.edit.category = '';
                    this.edit.price = '';
                    this.edit.stock = '';
                    this.edit.photo = '';
                    this.errors = [];

                }).catch(err => {
                    if(err.response.status == 422) {
                        this.errors = err.response.data.errors;
                    }else if(err.response.status == 500) {
                        this.errors = [['System error']];
                    }
                });
        },

        getCategories() {
            axios.get(`/api/v1/category`)
                .then(res => {
                    this.categories = res.data.data;
                })
                .catch(err => {
                    this.errors = err.response.data;
                })
        },

        deleteProduct(id) {
            alertify.confirm("Anda yakin ingin menghapus?", function (ev) {
                ev.preventDefault();
                let that = this
                axios.delete(`/api/v1/product/${id}`)
                    .then(res => {
                        console.log(res);
                        that.displayData();
                        alertify.success("Berhasil hapus produk!");
                    });
                });
        },

        onChangePhotoUpload() {
            this.add.photo = event.target.files[0];
        },

        onChangePhotoEdit() {
            this.edit.photo = event.target.files[0];
            
            $('#edit-category-image').attr('src', URL.createObjectURL(event.target.files[0]));
        },

        nextPage() {
            let nextPage = this.current_page+1;
            window.history.replaceState(null, null, "?page="+nextPage);
            this.displayData(this.current_page+1, this.search);
        },

        prevPage() {
            let prevPage = this.current_page-1;
            window.history.replaceState(null, null, "?page="+prevPage);
            this.displayData(prevPage, this.search);
        },

        searchData() {
            this.displayData(1, this.search);
            window.history.replaceState(null, null, "?page=1");
        },

        formatPrice(value) {
            let val = (value/1).toFixed(0).replace('.', ',')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }
        
    }
}
</script>
<style>
    .image-table {
        width: 40px;
        height: 40px;
        object-fit: cover;
        margin-right: 10px;;
    }
</style>
