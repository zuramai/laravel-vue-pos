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
                            <h4 class="mb-3 mt-0 float-right">Rp {{ numberFormat(data.pendapatan_bulan_lalu) }}</h4>
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
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Average Price</h6>
                            <h4 class="mb-3 mt-0 float-right">15.9</h4>
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
                            <h6 class="text-uppercase mt-0 float-left text-white-50">Product Sold</h6>
                            <h4 class="mb-3 mt-0 float-right">1890</h4>
                        </div>
                        <div>
                            <span class="badge badge-light text-info"> +89% </span> <span class="ml-2">From previous period</span>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="float-right">
                            <a href="#" class="text-white-50"><i class="mdi mdi-briefcase-check h5"></i></a>
                        </div>
                        <p class="font-14 m-0">Last : 1776</p>
                    </div>
                </div>
            </div>
        </div>  
        <!-- end row -->

        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Sales Report</h4>
                        <div class="row">
                            <div class="col-lg-8">
                                <div id="morris-line-example" class="morris-chart" style="height: 300px"></div>
                            </div>
                            <div class="col-lg-4">
                                <div>
                                    <h5 class="font-14 mb-5">Yearly Sales Report</h5>

                                    <div>
                                        <h5 class="mb-3">2018 : $19523</h5>
                                        <p class="text-muted mb-4">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis atque quos dolores et</p>
                                        <a href="#" class="btn btn-primary btn-sm">Read more <i class="mdi mdi-chevron-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Sales Analytics</h4>
                        <div id="morris-donut-example" class="morris-chart" style="height: 300px"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Latest Messages</h4>
                        <div class="latest-massage">
                            <a href="#" class="latest-message-list">
                                <div class="border-bottom position-relative">
                                    <div class="float-left user mr-3">
                                        <h5 class="bg-primary text-center rounded-circle text-white mt-0">v</h5>
                                    </div>
                                    <div class="message-time">
                                        <p class="m-0 text-muted">Just Now</p>
                                    </div>
                                    <div class="massage-desc">
                                        <h5 class="font-14 mt-0 text-dark">Victor Zamora</h5>
                                        <p class="text-muted">Hey! there I'm available...</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="latest-message-list">
                                <div class="border-bottom mt-3 position-relative">
                                    <div class="float-left user mr-3">
                                        <h5 class="bg-success text-center rounded-circle text-white mt-0">p</h5>
                                    </div>
                                    <div class="message-time">
                                        <p class="m-0 text-muted">2 Min. ago</p>
                                    </div>
                                    <div class="massage-desc">
                                        <h5 class="font-14 mt-0 text-dark">Patrick Beeler</h5>
                                        <p class="text-muted">I've finished it! See you so...</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="latest-message-list">
                                <div class="border-bottom mt-3 position-relative">
                                    <div class="float-left user mr-3">
                                        <img src="assets/images/users/avatar-3.jpg" alt="" class="rounded-circle mb-3">
                                    </div>
                                    <div class="message-time">
                                        <p class="m-0 text-muted">6 Min. ago</p>
                                    </div>
                                    <div class="massage-desc">
                                        <h5 class="font-14 mt-0 text-dark">Ralph Ramirez</h5>
                                        <p class="text-muted">This theme is awesome!</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="latest-message-list">
                                <div class="border-bottom mt-3 position-relative">
                                    <div class="float-left user mr-3">
                                        <h5 class="bg-pink text-center rounded-circle text-white mt-0">b</h5>
                                    </div>
                                    <div class="message-time">
                                        <p class="m-0 text-muted">01:34 pm</p>
                                    </div>
                                    <div class="massage-desc">
                                        <h5 class="font-14 mt-0 text-dark">Bryan Lacy</h5>
                                        <p class="text-muted">I've finished it! See you so...</p>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="latest-message-list">
                                <div class="mt-3 position-relative">
                                    <div class="float-left user mr-3">
                                        <img src="assets/images/users/avatar-4.jpg" alt="" class="rounded-circle mb-3">
                                    </div>
                                    <div class="message-time">
                                        <p class="m-0 text-muted">02:05 pm</p>
                                    </div>
                                    <div class="massage-desc">
                                        <h5 class="font-14 mt-0 text-dark">James Sorrells</h5>
                                        <p class="text-muted">Hey! there I'm available...</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>

            </div>
            <!-- end col -->

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Recent Activity</h4>
                        <ol class="activity-feed mb-0">
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date text-white-50">Jan 10</span>
                                    <span class="activity-text text-white">Responded to need “Volunteer Activities”</span>
                                </div>
                            </li>
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date text-white-50">Jan 09</span>
                                    <span class="activity-text text-white">Added an interest “Volunteer Activities”</span>
                                </div>
                            </li>
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date text-white-50">Jan 08</span>
                                    <span class="activity-text text-white">Joined the group “Boardsmanship Forum”</span>
                                </div>
                            </li>
                            <li class="feed-item">
                                <div class="feed-item-list">
                                    <span class="date text-white-50">Jan 07</span>
                                    <span class="activity-text text-white">Responded to need “In-Kind Opportunity”</span>
                                </div>
                            </li>
                        </ol>

                    </div>
                </div>
            </div>
            <!-- end col -->

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Social Source</h4>
                        <div class="text-center">
                            <div class="social-source-icon lg-icon mb-3">
                                <i class="mdi mdi-facebook h2 bg-primary text-white"></i>
                            </div>
                            <h5 class="font-16"><a href="#" class="text-dark">Facebook - <span class="text-muted">125 sales</span> </a></h5>
                            <p class="text-muted">Maecenas nec odio et ante tincidunt tempus. Donec vitae sapien ut libero venenatis tincidunt.</p>
                            <a href="#" class="text-primary font-14">Learn more <i class="mdi mdi-chevron-right"></i></a>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-4">
                                <div class="social-source text-center mt-3">
                                    <div class="social-source-icon mb-2">
                                        <i class="mdi mdi-facebook h5 bg-primary text-white"></i>
                                    </div>
                                    <p class="font-14 text-muted mb-2">125 sales</p>
                                    <h6>Facebook</h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="social-source text-center mt-3">
                                    <div class="social-source-icon mb-2">
                                        <i class="mdi mdi-twitter h5 bg-info text-white"></i>
                                    </div>
                                    <p class="font-14 text-muted mb-2">112 sales</p>
                                    <h6>Twitter</h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="social-source text-center mt-3">
                                    <div class="social-source-icon mb-2">
                                        <i class="mdi mdi-instagram h5 bg-pink text-white"></i>
                                    </div>
                                    <p class="font-14 text-muted mb-2">104 sales</p>
                                    <h6>Instagram</h6>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

        <div class="row">
            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Latest Trasaction</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">ID No.</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Quantity</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row">#14567</th>
                                        <td>Michael Mitchell</td>
                                        <td>14 Jan</td>
                                        <td>$74</td>
                                        <td>2</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-danger" role="progressbar" style="width: 89%;" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>$148</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">#14568</th>
                                        <td>Dennis Cervantes</td>
                                        <td>15 Jan</td>
                                        <td>$72</td>
                                        <td>2</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 64%;" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>$144</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">#14569</th>
                                        <td>Bernard Moats</td>
                                        <td>16 Jan</td>
                                        <td>$86</td>
                                        <td>1</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>$86</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">#14570</th>
                                        <td>Patrick Esquivel</td>
                                        <td>17 Jan</td>
                                        <td>$59</td>
                                        <td>2</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-warning" role="progressbar" style="width: 64%;" aria-valuenow="64" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>$118</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">#14571</th>
                                        <td>Michael Barger</td>
                                        <td>18 Jan</td>
                                        <td>$62</td>
                                        <td>2</td>
                                        <td>
                                            <div class="progress" style="height: 5px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </td>
                                        <td>$124</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title mb-4">Document files</h4>
                        <table class="table table-striped mb-0">
                            <tbody>
                                <tr>
                                    <td><i class="far fa-file-pdf text-primary h2"></i></td>
                                    <td>
                                        <h6 class="mt-0">2015</h6>
                                        <p class="text-muted mb-0">dolor sit amet</p></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="far fa-file-pdf text-primary h2"></i></td>
                                    <td>
                                        <h6 class="mt-0">2016</h6>
                                        <p class="text-muted mb-0">dolor sit amet</p></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="far fa-file-pdf text-primary h2"></i></td>
                                    <td>
                                        <h6 class="mt-0">2017</h6>
                                        <p class="text-muted mb-0">pretium quis</p></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><i class="far fa-file-pdf text-primary h2"></i></td>
                                    <td>
                                        <h6 class="mt-0">2018</h6>
                                        <p class="text-muted mb-0">ultricies nec</p></td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

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
