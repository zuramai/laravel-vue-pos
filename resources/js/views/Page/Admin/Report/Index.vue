<template>
        <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="page-title m-0">Semua Laporan</h4>
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
                                <h4 class="mt-0 header-title">Laporan</h4>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td class="td-30">Total Produk: </td>
                                        <td class="td-70">{{ total.product }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Kategori Produk: </td>
                                        <td class="td-70">{{ total.category }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Transaksi Bulan Ini: </td>
                                        <td class="td-70">{{ total.transaction_thismo }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Keseluruhan Transaksi: </td>
                                        <td class="td-70">{{ total.transaction }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Produk Terjual: </td>
                                        <td class="td-70">{{ total.product_sold }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Produk Terjual (Bulan ini): </td>
                                        <td class="td-70">{{ total.product_sold_thismo }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-30">Total Customer: </td>
                                        <td class="td-70">{{ total.customer }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-xl-12">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-8">
                                <h4 class="mt-0 header-title">Laporan Transaksi</h4>
                            </div>
                        </div>
                        <div id="transaction-chart" class="morris-chart" style="height: 300px"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Jenis Laporan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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

export default {

    mounted() {
        var $barData = [
            {y: '2009-02-12', a: 100}
        ];
        this.getTotalReport();

        setTimeout(() => {
            this.showTransactionChart('transaction-chart',this.total.transactionChart, 'y', ['a'], ['Jumlah Penjualan: '], ['#5985ee']);
        }, 2000);
        
    },

    data() {
        return {
            transactionChart: [],
            total: {
                transactionData: [],
                transaction: "Loading..",
                transaction_thismo: "Loading..",
                product: "Loading..",
                customer: "Loading..",
                category: "Loading..",
                product_sold: "Loading..",
                product_sold_thismo: "Loading..",
            }
        }
    },

    methods: {
        getTotalReport() {
            axios.get('/api/v1/report')
                .then(res => {
                    this.total.transactionChart= res.data.transaction.chart;

                    this.total.transaction= res.data.total.transaction;
                    this.total.transaction_thismo= res.data.total.transaction_thismo;
                    this.total.product= res.data.total.product;
                    this.total.customer= res.data.total.customer;
                    this.total.category= res.data.total.category;
                    this.total.product_sold= res.data.total.product_sold;
                    this.total.product_sold_thismo= res.data.total.product_sold_thismo;

                    
                    // this.showTransactionChart();
                }).catch(err => {

                })
        },
      
        showTransactionChart(element, data, xkey, ykeys, labels, lineColors) {
            console.log(data);
            Morris.Bar({
                element: element,
                data: data,
                xkey: xkey,
                ykeys: ykeys,
                labels: labels,
                gridLineColor: '#eef0f2',
                barSizeRatio: 0.4,
                resize: true,
                hideHover: 'auto',
                barColors: lineColors
            });
        }
    }
}
</script>
<style>
.td-70 {
    width: 70%;
}
.td-30 {
    width: 30%;
}
</style>
