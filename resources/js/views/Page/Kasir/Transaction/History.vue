<template>
    
<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-box">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="page-title m-0">Riwayat Transaksi</h4>
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
                            <h4 class="mt-0 header-title">Semua Transaksi</h4>
                        </div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id='transaction-table'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Customer</th>
                                    <th>Total Harga</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="data in transactions" :key="data.id">
                                    <td>{{ data.id }}</td>
                                                                        <td><span v-for="(detail, index) in data.details" :key="index" v-if="index ==0">{{ detail.product.name }}</span> <span v-if="data.details.length > 1">dan {{ data.details.length - 1 }} lainnya</span></td>

                                    <td>{{ data.customer.name }}</td>
                                    <td>Rp {{ numberFormat(data.total) }}</td>
                                    <td>{{ moment(data.created_at).format('DD MMMM YYYY') }}</td>
                                    <td>
                                        <router-link  :to="{ path: `/transaksi/invoice/${data.invoice}` }" class="btn btn-primary btn-sm">Invoice</router-link>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- end row -->            

</div><!-- container fluid -->
</template>

<script>
import moment from 'moment';
export default {
    mounted() {
        this.displayData();
    },

    data() {
        return {
            transactions: [],

            page: 1,
            search: '',
            last_page: null,
            current_page: this.$route.query.page || 1,
            next_page_url: '',
            prev_page_url: '',
            
        }
    },

    methods: {
       displayData() {
           axios.get('/api/v1/transaction/history')
            .then(res => {
                this.transactions = res.data.data;
            }).catch(err => console.log(err.response));
       },

       invoiceTransaction(id) {
           
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
    }
}
</script>
