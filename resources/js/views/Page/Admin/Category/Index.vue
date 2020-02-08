<template>
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">Kategori</h4>
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
                                <h4 class="mt-0 header-title">Semua kategori</h4>
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
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Deskripsi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="category in categories" v-bind:key='category.id'>
                                        <td>{{ category.id }}</td>

                                        <td>
                                            
                                            <img :src="`/images/categories/${category.image_name}`" alt="Gambar" class='image-table'>

                                        </td>
                                        
                                        <td>{{ category.name }}</td>
                                        <td>{{ category.description }}</td>
                                        <td>
                                            <button type="button" class='btn btn-warning' @click="editCategory(category.id)">Edit</button>
                                            <button type="button" @click="deleteCategory(category.id)" class="btn btn-danger" ><span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" ></span>
  Hapus</button>
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
        <!-- Modal -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="addCategory">
                        
                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nama Kategori: </label>
                                    <input type="text" name="name" class="form-control" v-model="name">
                                </div>
                                <div class="form-group">
                                    <label for="">Deskripsi: </label>
                                    <textarea name="description" class="form-control" v-model="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar: </label>
                                    <input type="file" name="file" id="" class='form-control-file' accept='image/*' @change="onChangePhotoUpload">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" :disabled="addLoading == true"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="addLoading == true"></span> Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="updateCategory(edit.id)">
                        
                        <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Nama Kategori: </label>
                                    <input type="text" name="name" class="form-control" v-model="edit.name">
                                </div>
                                <div class="form-group">
                                    <label for="">Deskripsi: </label>
                                    <textarea name="description" class="form-control" v-model="edit.description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Gambar: </label>
                                    <input type="file" name="file" id="" class='form-control-file' accept='image/*' @change="onChangePhotoEdit">
                                    <img :src="`/images/categories/${edit.image_name}`" alt="" class="image-table" id='edit-category-image'>
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
        console.log("category");
        this.displayData(this.page, this.search);
    },

    data() {
        return {
            edit: {
                id: '',
                name: '',
                description: '',
                image_name: '',
                photo: '',
            },
            search: '',
            name: '',
            description: '',
            categories: [],
            photo: '',

            addLoading: false,
            deleteLoading: false, 
            first_page: 1,
            
            page: 1,
            search: '',
            last_page: null,
            current_page: this.$route.query.page || 1,
            next_page_url: '',
            prev_page_url: '',
        }
    },

    methods: {  
        displayData(page = 1, search= '') {
            this.$http({url:'/api/v1/category',method: 'GET',  params: { search: this.search, page: this.page } })
                .then(result => {
                    console.log(result.data);
                    this.categories = result.data.data;
                    this.last_page = result.data.meta.last_page;
                    this.current_page = result.data.meta.current_page;
                    this.next_page_url = result.data.links.next;
                    this.prev_page_url = result.data.links.prev;
                });
        },

        addCategory() {
            this.addLoading = true;
            let name = this.name;
            let description = this.description;
            let photo = this.photo

            let formData = new FormData();
            formData.append('name', name);
            formData.append('description', description);
            formData.append('photo', photo);


            axios.post('/api/v1/category', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(res => {
                    this.addLoading = false;
                    // console.log(res);
                    Swal.fire(
                        'Sukses',
                        'Sukses tambah kategori',
                        'success'
                    );
                    this.displayData();
                });
        },

        editCategory(id) {
            axios.get(`/api/v1/category/${id}/edit`)
                .then(result => {
                    console.log(result.data);
                    this.edit.id = result.data.id; 
                    this.edit.name = result.data.name; 
                    this.edit.description = result.data.description;
                    this.edit.photo = '';
                    this.edit.image_name = result.data.image_name;

                    $('#modalEdit').modal('show');
                });
        },

        updateCategory(id) {
            let name = this.edit.name;
            let description = this.edit.description;
            let photo = this.edit.photo;

            let formData = new FormData();
            formData.append('name',name);
            formData.append('description',description);
            formData.append('photo',photo);

            // console.log(photo);
            console.log(formData.get('name'));

            axios.post(`/api/v1/category/${id}`,formData, { headers: { 'Content-Type': 'multipart/form-data' }})
                .then(res => {
                    console.log(res.data);
                    
                    $('#modalEdit').modal('toggle');
                    this.displayData();
                }).catch(err => console.log(err))
        },

        deleteCategory(id) {
            $(event.target).find('span').removeClass('d-none');
            $(event.target).attr('disabled',true);
            let _this = this;
            axios.delete(`/api/v1/category/${id}`)
                .then(function() {
                    $(event.target).find('span').addClass('d-none');
                    $(event.target).removeAttr('disabled');
                    Swal.fire('Sukses hapus kategori!','Sukses!','success');
                    _this.displayData();
                });
        },

        onChangePhotoUpload() {
            this.photo = event.target.files[0];
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
        }
        
    }
}
</script>
<style>
    .image-table {
        width: 80px;
    }
</style>
