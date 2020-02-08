<template>
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">Customer</h4>
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
                                <h4 class="mt-0 header-title">Semua Customer</h4>
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
                                    <input type="text" class="form-control" placeholder="Cari Nama"  aria-describedby="basic-addon1" v-model="search" @keyup="searchData">
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-lg" id='category-table'>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No telepon</th>
                                        <th>Level</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="data in customers" v-bind:key='data.id'>
                                        <td>{{ data.id }}</td>
                                        <td><img :src="`/images/customers/${data.image_name}`" class='image-table-customers' v-if="data.image_name !== ''"> {{ data.name }}</td>
                                        <td>{{ data.email }}</td>
                                        <td>{{ data.phone }}</td>
                                        <td>
                                            <div class="badge badge-primary">{{ data.role }}</div>
                                        </td>
                                        <td>
                                            <button type="button" class='btn btn-primary' @click="userDetail(data.id)">Detail</button>
                                            <button type="button" class='btn btn-warning' @click="userEdit(data.id)">Edit</button>
                                            <button type="button" @click="userDelete(data.id)" class="btn btn-danger">Hapus</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="customerAdd()">
                        <div class="modal-body">
                            <div class="alert alert-danger" v-if="errors != ''" >
                                <ul>
                                    <li v-for="error in errors" v-bind:key="error.id">{{ error[0] }}</li>
                                </ul>
                            </div>
                                <div class="form-group">
                                    <label for="">Nama Pengguna: </label>
                                    <input type="text" name="name" class="form-control" v-model="add.name">
                                </div>
                                <div class="form-group">
                                    <label for="">No telp: </label>
                                    <input type="text" name="name" class="form-control" v-model="add.phone">
                                </div>
                                <div class="form-group">
                                    <label for="">Email: </label>
                                    <input type="text" name="name" class="form-control" v-model="add.email">
                                </div>
                                <div class="form-group">
                                    <label for="">Role: </label>
                                    <select name="" id="" v-model="add.role" class="form-control">
                                        <option value="">Pilih salah satu</option>
                                        <option value="Retail">Retail</option>
                                        <option value="Grosir">Grosir</option>
                                        <option value="Dropshipper">Dropshipper</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Provinsi: </label>
                                    <select name="" class="form-control" v-model="add.provinsi" id="">
                                        <option value="">Pilih salah satu</option>
                                        <option value="aceh">Aceh</option>
                                        <option value="Jakarta">DKI Jakarta</option>
                                        <option value="Sumut">Sumatera Utara</option>
                                        <option value="sumbar">Sumatera Barat</option>
                                        <option value="Riau">Riau</option>
                                        <option value="Jambi">Jambi</option>
                                        <option value="Sumsel">Sumatera Selatan</option>
                                        <option value="Bengkulu">Bengkulu</option>
                                        <option value="Lampung">Lampung</option>
                                        <option value="BaBel">Kep. Bangka Belitung</option>
                                        <option value="kepRiau">Kepulauan Riau</option>
                                        <option value="Jabar">Jawa Barat</option>
                                        <option value="Banten">Banten</option>
                                        <option value="Jateng">Jawa Tengah</option>
                                        <option value="Yogyakarta">Yogyakarta</option>
                                        <option value="Jatim">Jawa Timur</option>
                                        <option value="Kalbar">Kalimantan Barat</option>
                                        <option value="Kalteng">Kalimantan Tengah</option>
                                        <option value="Kalsel">Kalimantan Selatan</option>
                                        <option value="Kaltim">Kalimantan Timur</option>
                                        <option value="Kaltra">Kalimantan Utara</option>
                                        <option value="Bali">Bali</option>
                                        <option value="NTT">Nusa Tenggara Timur</option>
                                        <option value="NTB">Nusa Tenggara Barat</option>
                                        <option value="Sulut">Sulawesi Utara</option>
                                        <option value="Sulteng">Sulawesi Tengah</option>
                                        <option value="Sulsel">Sulawesi Selatan</option>
                                        <option value="Sultengg">Sulawesi Tenggara</option>
                                        <option value="Sulbar">Sulawesi Barat</option>
                                        <option value="Gorontalo">Gorontalo</option>
                                        <option value="Maluku">Maluku</option>
                                        <option value="Maluku Utara">Maluku Utara</option>
                                        <option value="Papua">Papua</option>
                                        <option value="Papua Barat">Papua Barat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Kota: </label>
                                    <input type="text" v-model="add.kota" class="form-control" placeholder="Kota">
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat Lengkap: </label>
                                    <textarea name="alamat" id="" cols="30" rows="10" v-model="add.alamat" class="form-control"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto: </label>
                                    <input type="file" name="file" id="" class='form-control-file' accept='image/*' @change="onChangePhotoAdd">
                                    <img :src="`/images/categories/${add.image_name}`" alt="" class="image-table" id='add-category-image' v-if="add.image_name">
                                </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Tambahz</button>
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
                        <h5 class="modal-title" id="exampleModalLabel">Edit Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="userUpdate(edit.id)">
                        
                        <div class="modal-body">
                                <div class="alert alert-danger" v-if="errors != ''" >
                                    <ul>
                                        <li v-for="error in errors" v-bind:key="error.id">{{ error[0] }}</li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Pengguna: </label>
                                    <input type="text" name="name" class="form-control" v-model="edit.name">
                                </div>
                                <div class="form-group">
                                    <label for="">Email: </label>
                                    <input type="text" name="name" class="form-control" v-model="edit.email">
                                </div>
                                <div class="form-group">
                                    <label for="">Nomor Telepon: </label>
                                    <input type='text' name="description" class="form-control" v-model="edit.phone">
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat: </label>
                                    <textarea name="description" class="form-control" v-model="edit.address"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Level: </label>
                                    <select name="level" id="" v-model="edit.level" class="form-control">
                                        <option value="">Pilih salah satu..</option>
                                        <option value="Admin"  >Admin</option>
                                        <option value="Kasir" >Kasir</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Foto: </label>
                                    <input type="file" name="file" id="" class='form-control-file' accept='image/*' @change="onChangePhotoEdit">
                                    <img :src="`/images/customers/${edit.image_name}`" alt="" class="image-table" id='edit-category-image'>
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
        <!-- Detail Modal -->
        <div class="modal fade" id="modalDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                        
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-4 col-form-label">Nama Lengkap: </label>
                            <div class="col-sm-8">
                                <input class="form-control-plaintext" type="search" id="example-search-input" v-model="detail.name" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-4 col-form-label">Email: </label>
                            <div class="col-sm-8">
                                <input class="form-control-plaintext" type="search" id="example-search-input" v-model="detail.email" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-4 col-form-label">Nomor Telp: </label>
                            <div class="col-sm-8">
                                <input class="form-control-plaintext" type="search" id="example-search-input" v-model="detail.phone" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-4 col-form-label">Alamat: </label>
                            <div class="col-sm-8">
                                <textarea name="" id="" cols="30" rows="4" class="form-control-plaintext" v-model="detail.address"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="example-search-input" class="col-sm-4 col-form-label">Level: </label>
                            <div class="col-sm-8">
                                <input name="" class="form-control-plaintext" v-model="detail.level">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
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
    },

    data() {
        return {
            edit: {
                id: '',
                name: '',
                email: '',
                password: '',
                username: '',
                password: '',
                level: '',
                address: '',
                image_name: '',
                photo: '',
            },
            add: {
                id: '',
                name: '',
                email: '',
                phone: '',
                role: '',
                alamat: '',
                kota: '',
                provinsi: '',
                photo: '',
                image_name: '',
            },
            errors: '',
            detail: {},
            search: '',
            name: '',
            description: '',
            search: '',
            customers: [],
            photo: '',
            
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
            axios.get('/api/v1/customer', { params: { search: this.search, page: this.page } })
                .then(result => {
                    this.customers = result.data.data;
                    this.last_page = result.data.meta.last_page;
                    this.current_page = result.data.meta.current_page;
                    this.next_page_url = result.data.links.next;
                    this.prev_page_url = result.data.links.prev;
                });
        },

        userDetail(id) {
            axios.get(`/api/v1/user/${id}`)
                .then(res => {
                    this.detail = res.data;
                    console.log(res.data);
                    $('#modalDetail').modal('show');
                });
        },
        userDelete(id) {
            let that = this;
            alertify.confirm("Anda yakin ingin menghapus?", function (ev) {
                ev.preventDefault();
                axios.delete(`/api/v1/user/${id}`)
                    .then(res => {
                        console.log(res);
                        that.displayData();
                        alertify.success("Berhasil hapus pengguna!");
                    });
            }, function(ev) {
                ev.preventDefault();
                alertify.error("Berhasil membatalkan");
            });
        },
        userEdit(id) {
            axios.get(`/api/v1/user/${id}/edit`)
                .then(res => {
                    this.edit.id = res.data.id;
                    this.edit.name = res.data.name;
                    this.edit.username = res.data.username;
                    this.edit.password = res.data.password;
                    this.edit.email = res.data.email;
                    this.edit.phone = res.data.phone;
                    this.edit.address = res.data.address;
                    this.edit.level = res.data.level;
                    this.edit.image_name = res.data.image_name;
                })
                
            $('#modalEdit').modal('toggle');
        },

        userUpdate(id) {
            let formData = new FormData();
            
            formData.append('name', this.edit.name);
            formData.append('email', this.edit.email);
            formData.append('password', this.edit.password);
            formData.append('phone', this.edit.phone);
            formData.append('address', this.edit.address);
            formData.append('level', this.edit.level);
            formData.append('username', this.edit.username);
            formData.append('photo', this.edit.photo);

            axios.post(`/api/v1/user/${this.edit.id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(res => {
                    $('#modalEdit').modal('toggle');
                    this.displayData();
                        Swal.fire(
                        `Sukses!`,
                        `Sukses ubah data pengguna ${this.add.level}!`,
                        'success'
                        )

                }).catch(error => {
                    let statusCode = error.response.status;
                    if(statusCode == 500) {
                        this.errors = {"error": "Terjadi kesalahan sistem."};
                    }else if(statusCode == 400) {
                        console.log(error.response.data)
                        this.errors = error.response.data;
                    }
                });
        },
        customerAdd() {
            let formData = new FormData();
            
            formData.append('name', this.add.name);
            formData.append('email', this.add.email);
            formData.append('password', this.add.password);
            formData.append('phone', this.add.phone);
            formData.append('alamat', this.add.alamat);
            formData.append('role', this.add.role);
            formData.append('photo', this.add.photo);
            formData.append('kota', this.add.kota);
            formData.append('provinsi', this.add.provinsi);

            axios.post('/api/v1/customer', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                .then(res => {
                    $('#modalAdd').modal('toggle');
                    this.displayData();
                    setTimeout(() => {
                        Swal.fire(
                        `Sukses!`,
                        `Sukses tambah customer!`,
                        'success'
                        )
                    }, 200);

                }).catch(error => {
                    let statusCode = error.response.status;
                    if(statusCode == 500) {
                        this.errors = {"error": "Terjadi kesalahan sistem."};
                    }else if(statusCode == 400) {
                        console.log(error.response.data)
                        this.errors = error.response.data;
                    }
                });
        },

        onChangePhotoAdd() {
            this.add.photo = event.target.files[0];
            $('#add-category-image').attr('src', URL.createObjectURL(event.target.files[0]));
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
    .image-table-customers {
        border-radius: 50%;
        width: 50px;
        height: 50px;
        margin-right: 10px;
        object-fit: cover;   
    }
</style>
