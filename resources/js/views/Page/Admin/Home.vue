<template>
    
    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">Dashboard</h4>
                        </div>
                        <div class="col-md-4">
                            <div class="float-right d-none d-md-block">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ti-settings mr-1"></i> Settings
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                        <a class="dropdown-item" href="#">Something else here</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#">Separated link</a>
                                    </div>
                                </div>
                            </div>
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
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary mini-stat text-white">
                    <div class="p-3 mini-stat-desc">
                        <div class="clearfix">
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Orders</h6>
                            <h4 class="mb-3 mt-0 float-right">{{ numberFormat(data.transaksi_bulan_ini) }}</h4>
                        </div>
                        <div>
                            <span class="badge badge-light text-info"> {{data.peningkatan_transaksi}} </span> <span class="ml-2">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="float-right">
                            <a href="#" class="text-white-50"><i class="mdi mdi-cube-outline h5"></i></a>
                        </div>
                        <p class="font-14 m-0">Bulan lalu : {{ data.transaksi_bulan_lalu }}</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-info mini-stat text-white">
                    <div class="p-3 mini-stat-desc">
                        <div class="clearfix">
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Pendapatan</h6>
                            <h4 class="mb-3 mt-0 float-right">Rp {{ numberFormat(data.pendapatan_bulan_ini) }}</h4>
                        </div>
                        <div>
                            <span class="badge badge-light text-danger"> {{data.peningkatan_pendapatan}} </span> <span class="ml-2">Dari bulan lalu</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="float-right">
                            <a href="#" class="text-white-50"><i class="mdi mdi-buffer h5"></i></a>
                        </div>
                        <p class="font-14 m-0">Bulan lalu : Rp {{numberFormat(data.pendapatan_bulan_lalu)}}</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-pink mini-stat text-white">
                    <div class="p-3 mini-stat-desc">
                        <div class="clearfix">
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Harga Rata-rata</h6>
                            <h4 class="mb-3 mt-0 float-right">Rp {{ numberFormat(data.average_price) }}</h4>
                        </div>
                        <div>
                            <span class="badge badge-light text-primary"> 0% </span> <span class="ml-2">From previous period</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="float-right">
                            <a href="#" class="text-white-50"><i class="mdi mdi-tag-text-outline h5"></i></a>
                        </div>
                        <p class="font-14 m-0">Last : 15.8</p>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card bg-success mini-stat text-white">
                    <div class="p-3 mini-stat-desc">
                        <div class="clearfix">
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Produk Terjual</h6>
                            <h4 class="mb-3 mt-0 float-right">{{ data.product_sold }}</h4>
                        </div>
                        <div>
                            <span class="badge badge-light text-info"> +89% </span> <span class="ml-2">From previous period</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="float-right">
                            <a href="#" class="text-white-50"><i class="mdi mdi-briefcase-check h5"></i></a>
                        </div>
                        <p class="font-14 m-0">Bulan lalu : {{ data.product_sold_lastmo }}</p>
                    </div>
                </div>
            </div>
        </div>  
        <!-- end row -->

        <!-- end row -->

        <!-- end row -->

    </div><!-- container fluid -->
</template>
<script>
export default {
    mounted() {
        // console.log("INI HOME ADMINz");
        this.displayData();
    },

    data() {
        return {
            data: [],
        }
    },

    methods: {
        displayData() {
            axios.get('/api/v1/admin/home')
                .then(res => {
                    this.data = res.data;
                })
        }
    }
}
</script>
