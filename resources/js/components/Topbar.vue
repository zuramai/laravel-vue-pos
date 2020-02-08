<template>
    
    <div class='topbar'>
        
        <div class="topbar-left	d-none d-lg-block">
            <div class="text-center">
                <a href="/admin" class="logo"><img src="/images/logo2.png" height="22" alt="logo"></a>
            </div>
        </div>

        <nav class="navbar-custom">

                <!-- Search input -->
                <div class="search-wrap" id="search-wrap">
                <div class="search-bar">
                    <input class="search-input" type="search" placeholder="Search" />
                    <a href="#" class="close-search toggle-search" data-target="#search-wrap">
                        <i class="mdi mdi-close-circle"></i>
                    </a>
                </div>
            </div>

            <ul class="list-inline float-right mb-0">
                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link waves-effect toggle-search" href="#"  data-target="#search-wrap">
                        <i class="mdi mdi-magnify noti-icon"></i>
                    </a>
                </li>

           


                <li class="list-inline-item dropdown notification-list nav-user">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                        <img :src="`/images/users/${$auth.user().image_name}`" alt="user" class="rounded-circle object-fit-cover">
                        <span class="d-none d-md-inline-block ml-1">{{$auth.user().name}} <i class="mdi mdi-chevron-down"></i> </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                        
                        <a class="dropdown-item" href="#" @click.prevent="$auth.logout()"><i class="dripicons-exit text-muted"></i> Logout</a>
                    </div>
                </li>

            </ul>

            <ul class="list-inline menu-left mb-0">
                <li class="list-inline-item">
                    <button type="button" class="button-menu-mobile open-left waves-effect">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </li>
            </ul>

        </nav>
    </div>
</template>

<script>
import Swal from 'sweetalert2'
export default {
    mounted() {
        console.log('TOPBAR')
    },

    methods: {
        logout() {
            this.$auth.logout();
            Swal.fire(
                        `Sukses!`,
                        `Sukses logout!`,
                        'success'
                        )
        }
    }
}
</script>

<style>
.object-fit-cover {
    object-fit: cover
}
</style>

