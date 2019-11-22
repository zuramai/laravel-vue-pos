<template>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 offset-lg-3 col-12">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="p-2">
                            <h4 class="text-muted float-right font-18 mt-4">Sign In</h4>
                            <div>
                                <a href="index.html" class="logo logo-admin"><img src="/images/logo_dark.png" height="28" alt="logo"></a>
                            </div>
                        </div>

                        <div class="p-2">

                            <form class="form-horizontal m-t-20" @submit.prevent="login()">
                                <transition name="fade">
                                    <div class="alert alert-danger" v-if="auth.has_error">
                                        Username atau password salah.
                                    </div>
                                    <div class="alert alert-success" v-else-if="auth.success">
                                        Sukses login.
                                    </div>
                                </transition>
                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="text" required="" placeholder="Username" v-model='auth.username'>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <input class="form-control" type="password" required="" placeholder="Password" v-model="auth.password">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1">
                                            <label class="custom-control-label" for="customCheck1">Remember me</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group text-center row m-t-20">
                                    <div class="col-12">
                                            <button class="btn btn-primary btn-block waves-effect waves-light"  :disabled="button_loading==true" type="submit">
                                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" v-if="button_loading==true"></span>
                                                    Login
                                            </button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
</template>
<script>
export default {
    mounted() {
        
    },

    data() {
        return {
            auth: {
                username: '',
                password: '',
                has_error: false,
                success: false,
            },
            button_loading: false
        }
    },

    methods: {
        login() {
            this.button_loading = true;
            var redirect = this.$auth.redirect()
            var app = this
            this.$auth.login({
                data: {
                    username: app.auth.username,
                    password: app.auth.password
                },
                success: function() {
                    // handle redirection
                    app.auth.has_error = false;
                    app.auth.success = true;
                    app.button_loading = true;
                    setTimeout(() => {
                        const redirectTo =  this.$auth.user().level === 'Admin' ? '/admin' : '/transaksi';
                        // this.$router.push(redirectTo)
                        window.location= '/admin';
                    }, 1000);
                },
                error: function() {
                    app.button_loading = false;
                    app.auth.has_error = true
                },
                rememberMe: true,
                fetchUser: true
            })
        },

        
    }
}
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity 1s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
