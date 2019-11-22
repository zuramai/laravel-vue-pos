<template>
    <div id="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu" v-if="$route.meta.pageType != 'auth'">
                <button type="button" class="button-menu-mobile button-menu-mobile-topbar open-left waves-effect">
                    <i class="mdi mdi-close"></i>
                </button>

                <div class="left-side-logo d-block d-lg-none">
                    <div class="text-center">
                        
                        <router-link to="/admin" class="logo"><img src="/images/logo_dark.png" height="20" alt="logo"></router-link>
                    </div>
                </div>

                <div class="sidebar-inner slimscrollleft">
                    
                    <div id="sidebar-menu">
                        <ul>

                                <li class="menu-title" v-if="$auth.check(['Kasir','Admin'])">Kasir</li>

                                
                                <li v-if="$auth.check(['Kasir','Admin'])">
                                    <router-link to="/transaksi" class="waves-effect"><i class="dripicons-rocket"></i> <span> Transaksi </span> </router-link>
                                </li>
                                
                                <li v-if="$auth.check(['Kasir','Admin'])">
                                    <router-link to="/transaksi/riwayat" class="waves-effect"><i class="dripicons-to-do"></i> <span> Riwayat Transaksi </span> </router-link>
                                </li>
                        
                            <li class="menu-title">Admin</li>

                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin" class="waves-effect">
                                    <i class="dripicons-home"></i>
                                    <span> Dashboard </span>
                                </router-link>
                            </li>
                            
                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/transaksi" class="waves-effect"><i class="dripicons-archive"></i> <span> Semua Transaksi </span> </router-link>
                            </li>
                            
                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/category" class="waves-effect"><i class="dripicons-archive"></i> <span> Kategori </span> </router-link>
                            </li>
                            
                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/users" class="waves-effect"><i class="dripicons-user"></i> <span> Pengguna </span> </router-link>
                            </li>
                            
                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/customer" class="waves-effect"><i class="dripicons-user"></i> <span> Customer </span> </router-link>
                            </li>

                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/product" class="waves-effect"><i class="dripicons-briefcase"></i> <span> Produk </span> </router-link>
                            </li>

                            <li v-if="$auth.check('Admin')">
                                <router-link to="/admin/report" class="waves-effect"><i class="dripicons-document-new"></i> <span> Laporan </span> </router-link>
                            </li>


                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div> <!-- end sidebarinner -->
            </div>
            <!-- Left Sidebar End -->

            <!-- Start right Content here -->

            <div :class="$route.meta.pageType == 'auth' ? '': `content-page`">
                <!-- Start content -->
                <div class="content">

                    <!-- Top Bar Start -->
                    <Topbar v-if="$route.meta.pageType != 'auth'"></Topbar>
                    <!-- Top Bar End -->

                    <div :class="$route.meta.pageType == 'auth' ? 'vh-100 page-content-wrapper' : 'page-content-wrapper' ">
                        <transition name="slide-fade">
                            <router-view></router-view>
                        </transition>
                    </div> <!-- Page content Wrapper -->

                </div> <!-- content -->

                <footer class="footer" v-if="$route.meta.pageType != 'auth'">
                    © 2019 Zinzer <span class="d-none d-md-inline-block"> - Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign.</span>
                </footer>

            </div>
    </div>
</template>

<script>
import Topbar from './../components/Topbar';

export default {
    components: {
        Topbar
    }
}
</script>
<style scoped>
    .slide-fade-enter-active {
        transition: all .5s ease;
    }
    .slide-fade-leave-active {
        transition: all .1s cubic-bezier(1.0, 0.5, 0.8, 1.0);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active below version 2.1.8 */ {
    transform: translateX(10px);
    opacity: 0;
    }
</style>
