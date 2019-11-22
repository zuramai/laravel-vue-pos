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
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> {!! session('success') !!}
                        </div>
                        <div class="alert alert-danger">
                            <ul class='mb-0'>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-lg" id='transaction-table'>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Bayar</th>
                                    <th>Kembali</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
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
export default {
    mounted() {

    },

    data() {
        return {
            produk: [],
        }
    },

    methods: {
       viewData() {
           axios.get();
       },
    }
}
</script>
