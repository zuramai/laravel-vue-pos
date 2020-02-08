<template>
    
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title m-0">Diskon</h4>
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
                            <h4 class="mt-0 header-title">Daftar Semua Diskon</h4>
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
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id='transaction-table'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Jumlah Diskon</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <tr v-for="discount in discounts">
                                    <td>{{ discount.id }}</td>
                                    <td>{{ discount.product.name }}</td>
                                    <td>{{ discount.amount }}%</td>
                                    <td><div :class="{ 'badge':true, 'badge-danger': discount.status == false, 'badge-success': discount.status == true }">{{ discount.status ? 'Aktif' : 'Tidak Aktif' }}</div></td>
                                    <td>
                                        <button class='btn btn-danger' @click="deleteDiscount(discount.id)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

        <!-- Modal -->
        <div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Diskon Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="" method='POST' enctype="multipart/form-data" @submit.prevent="addDiscount">
                        
                        <div class="modal-body">
                                <div class="alert alert-danger" v-if="errors != ''" >
                                    <ul>
                                        <li v-for="error in errors" v-bind:key="error.id">{{ error[0] }}</li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama/Kode Produk</label>
                                    <input type="text" class="form-control" placeholder="Cari Kode Atau Nama Produk" id="kode-produk" v-model="search" @keyup="searchProduct()">
                                    <div class="dropdown-search">
                                        <ul>
                                            <li v-for="data in productSearch" :key="data.id" @click="addProduct(data)"><img :src="`/images/products/${data.image_name}`" alt="" class='dropdown-image'><span><b>{{ data.code.toUpperCase() }}</b> - {{ data.name }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Jumlah Diskon (Dalam %): </label>
                                    <input type="number" name="amount" id="" class="form-control" v-model="add.amount">
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
    <!-- end row -->            

</div><!-- container fluid -->
</template>

<script>
import Swal from 'sweetalert2'

export default {
    mounted() {
        this.viewData();
    },

    data() {
        return {
            products: [],
            discounts: [],
            productSearch: [],
            search: '',
            addLoading: false,
            errorAdd: false,
            errors: '',
            add: {
                product: '',
                amount: '',
            }
        }
    },

    methods: {
       viewData() {
           axios.get("/api/v1/discount")
            .then(res => {
                this.discounts = res.data.data
            });
       },
       searchProduct() {
            let searchProduct = this.search;

            if(this.search == '') {
                this.productSearch = []
            }else{
                axios.get('/api/v1/product/search', { params: { 'search': searchProduct } })
                    .then(res => {
                        console.log(res.data);
                        this.productSearch = res.data;
                    });
            }
       },
       addProduct(data) {
           let productName = data.name;
           let productCode = data.code;
           let productId = data.id;

           this.search = productCode;

           this.productSearch = [];

       },
       addDiscount() {
           var form = new FormData;
           form.append('product',this.search);
           form.append('amount',this.add.amount);

           axios.post('/api/v1/discount', form)
                .then(res => {
                    console.log(res.data);
                }).catch(err => {
                    this.errors = err.response.data.errors 
                    console.log(this.errors)
                });
       },
       deleteDiscount(id) {
           axios.delete(`/api/v1/discount/${id}`)
            .then(res => {
                Swal.fire(
                        'Sukses',
                        'Sukses Hapus Diskon!',
                        'success'
                    );
                this.viewData();
            });
       }
    }
}
</script>

<style >
    .dropdown-search {
        background-color: #fdfdfd;
        border: 1px solid #eee;
        box-shadow: 2px 5px 10px #ccc;
    }

    .dropdown-search ul {
        list-style: none;
        margin-bottom: 0;
        padding-left: 0;
    }

    .dropdown-search ul li{
        padding: 10px 0 10px 10px;
        cursor: pointer;
    }

    .dropdown-search ul li:hover{
        background-color: #eee;
    }

    .dropdown-search ul li span{
        margin-left: 10px;
    }

    .dropdown-image {
        width: 40px;
        height: 40px;
        object-fit: cover
    }
</style>
