<template>
    <div class="container-fluid">

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="page-title-box d-print-none">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h4 class="page-title m-0">Invoice</h4>
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
                                <div class="col-12">
                                    <div class="card m-b-30">
                                        <div class="card-body">
            
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="invoice-title">
                                                        <h4 class="float-right font-16"><strong>Order # {{ data_order.id }}</strong></h4>
                                                        <h3 class="m-t-0">
                                                            <img src="/images/logo3.png" alt="logo" height="28"/>
                                                        </h3>
                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <address>
                                                                <strong>Billed To:</strong><br>
                                                                {{ data_order.customer.name }}<br>
                                                                {{data_order.customer.alamat}}<br>
                                                                {{data_order.customer.kota}}, {{data_order.customer.provinsi}}
                                                            </address>
                                                        </div>
                                                        <div class="col-6 text-right">
                                                            <address>
                                                                <strong>Shipped To:</strong><br>
                                                                {{ data_order.customer.name }}<br>
                                                                {{data_order.customer.alamat}}<br>
                                                                {{data_order.customer.kota}}, {{data_order.customer.provinsi}}
                                                            </address>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6 m-t-30">
                                                            <address>
                                                                <strong>Metode Pembayaran:</strong><br>
                                                                {{ data_order.payment_method.name }} <br>
                                                            </address>
                                                        </div>
                                                        <div class="col-6 m-t-30 text-right">
                                                            <address>
                                                                <strong>Tanggal Pesanan:</strong><br>
                                                                {{ moment(data_order.created_at).format('DD MMMM YYYY') }}<br><br>
                                                            </address>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="panel panel-default">
                                                        <div class="p-2">
                                                            <h3 class="panel-title font-20"><strong>Ringkasan Pesanan</strong></h3>
                                                        </div>
                                                        <div class="">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                    <tr>
                                                                        <td><strong>Barang</strong></td>
                                                                        <td class="text-center"><strong>Harga</strong></td>
                                                                        <td class="text-center"><strong>Jumlah</strong>
                                                                        </td>
                                                                        <td class="text-right"><strong>Total</strong></td>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    
                                                                    <tr v-for="order_detail in data_order.details" v-bind:key="order_detail.id">
                                                                        <td>{{order_detail.product.name}}</td>
                                                                        <td class="text-center">Rp {{ numberFormat(order_detail.price - order_detail.ppn) }}</td>
                                                                        <td class="text-center">{{ order_detail.quantity }}</td>
                                                                        <td class="text-right">Rp {{ numberFormat(order_detail.subtotal - order_detail.ppn*order_detail.quantity)}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="no-line"></td>
                                                                        <td class="no-line"></td>
                                                                        <td class="no-line text-center">
                                                                            <strong>PPN</strong></td>
                                                                        <td class="no-line text-right">Rp {{  numberFormat(totalPPN) }} </span></td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="thick-line"></td>
                                                                        <td class="thick-line"></td>
                                                                        <td class="thick-line text-center">
                                                                            <strong>Subtotal</strong></td>
                                                                        <td class="thick-line text-right">Rp {{numberFormat(data_order.total)}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="no-line"></td>
                                                                        <td class="no-line"></td>
                                                                        <td class="no-line text-center">
                                                                            <strong>Total</strong></td>
                                                                        <td class="no-line text-right"><h4 class="m-0">{{numberFormat(data_order.total)}}</h4></td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
            
                                                            <div class="d-print-none mo-mt-2">
                                                                <div class="float-right">
                                                                    <a href="javascript:window.print()" class="btn btn-success waves-effect waves-light"><i class="fa fa-print"></i> Print</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
            
                                                </div>
                                            </div> <!-- end row -->
            
                                        </div>
                                    </div>
                                </div> <!-- end col -->
                            </div> <!-- end row -->            

                        </div><!-- container fluid -->
</template>
<script>
export default {
    mounted() {
        let invoice_id = this.$route.params.invoice_id;
        
        this.displayData(invoice_id);
    },

    data() {
        return {
            invoice_id: '',
            order_detail: [],
            data_order: {},
            total_ppn: '',
        }
    },

    computed: {
        totalPPN() {
            let ppn = 0;
            this.data_order.details.forEach(data => {
                ppn += data.ppn * data.quantity
            });

            return ppn
        }
    },

    methods: {
        displayData(invoice_id) {
            axios.get(`/api/v1/transaction/invoice/${invoice_id}`)
                .then(res => {
                    this.data_order = res.data;
                });
        }
    }
}
</script>

