/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import VueRouter from 'vue-router';
window.Vue = require('vue');
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import 'es6-promise/auto'
import moment from 'moment'
import auth from './auth'
import VueAuth from '@websanova/vue-auth'
import VueAxios from 'vue-axios'

import Home from './views/Page/Home.vue';
import KasirTransactionIndex from './views/Page/Kasir/Transaction/Index.vue';
import KasirTransactionEdit from './views/Page/Kasir/Transaction/Edit.vue';
import KasirTransactionHistory from './views/Page/Kasir/Transaction/History.vue';
import KasirTransactionDetail from './views/Page/Kasir/Transaction/Detail.vue';
import TransactionInvoice from './views/Page/Kasir/Transaction/Invoice.vue';

import AdminHome from './views/Page/Admin/Home.vue';

import AdminTransactionIndex from './views/Page/Admin/Transaction/Index.vue';
import AdminTransactionEdit from './views/Page/Admin/Transaction/Edit.vue';
import AdminTransactionDetail from './views/Page/Admin/Transaction/Detail.vue';

import AdminUsersIndex from './views/Page/Admin/Users/Index.vue';
import AdminUsersAdd from './views/Page/Admin/Users/Add.vue';
import AdminUsersEdit from './views/Page/Admin/Users/Edit.vue';
import AdminUsersDetail from './views/Page/Admin/Users/Detail.vue';

import AdminCustomerIndex from './views/Page/Admin/Customer/Index.vue';

import AdminCategoryIndex from './views/Page/Admin/Category/Index.vue';
import AdminCategoryAdd from './views/Page/Admin/Category/Add.vue';
import AdminCategoryEdit from './views/Page/Admin/Category/Edit.vue';

import AdminProductIndex from './views/Page/Admin/Product/Index.vue';
import AdminProductDetail from './views/Page/Admin/Product/Detail.vue';
import AdminProductAdd from './views/Page/Admin/Product/Add.vue';
import AdminProductEdit from './views/Page/Admin/Product/Edit.vue';

import AdminReport from './views/Page/Admin/Report/Index.vue'

import AuthLogin from './views/Auth.vue';

Vue.component('app', require('./views/App.vue').default);
Vue.component('auth-page', require('./views/Auth.vue').default);
Vue.use(VueRouter);
Vue.use(VueAxios, axios)
moment.locale('id')
let numberFormat = function(value) {
        let val = (value / 1).toFixed(0).replace('.', ',')
        return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
    }
Vue.prototype.moment = moment
Vue.prototype.numberFormat = numberFormat;


const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: '/', component: Home, meta: { auth: true }},
        { path: '/transaksi', component: KasirTransactionIndex, meta: { auth:true }},
        { path: '/transaksi/riwayat', component: KasirTransactionHistory, meta: { auth:true }},
        { path: '/transaksi/invoice/:invoice_id', component: TransactionInvoice, meta: { auth:true }},
        { path: '/admin', component: AdminHome, meta: { auth: true }, name: 'admin.dashboard'},
        { path: '/admin/transaksi', component: AdminTransactionIndex, meta: {auth:true }},
        { path: '/admin/transaksi/edit/:id', component: AdminTransactionEdit, meta: {auth:true }},
        { path: '/admin/transaksi/:id', component: AdminTransactionDetail, meta: { auth:true }},
        
        { path: '/admin/users', component: AdminUsersIndex, meta: { auth:true }},
        { path: '/admin/users/edit/:id', component: AdminUsersEdit, meta: { auth:true }},
        { path: '/admin/users/:id', component: AdminUsersDetail, meta: { auth:true }},
        { path: '/admin/users/add', component: AdminUsersAdd, meta: {auth:true }},

        { path: '/admin/customer', component: AdminCustomerIndex, meta: {auth:true }},

        { path: '/admin/category', component: AdminCategoryIndex, meta: {auth:true }},
        { path: '/admin/category/edit/:id', component: AdminCategoryEdit, meta: {auth:true }},
        { path: '/admin/category/add', component: AdminCategoryAdd, meta: { auth:true }},

        { path: '/admin/product', component: AdminProductIndex, meta: { auth:true }},
        { path: '/admin/product/edit/:id', component: AdminProductEdit, meta: { auth:true }},
        { path: '/admin/product/add', component: AdminProductAdd, meta: {auth:true }},
        { path: '/admin/product/:id', component: AdminProductDetail, meta: { auth:true }},
        
        { path: '/admin/report', component: AdminReport, meta: { auth:true }},

        { path: '/login', component: AuthLogin, meta: { auth: false, pageType: 'auth' } },
    ]
})
router.beforeEach((to, from, next) => {
    $vs.loading();
})
router.beforeEach((to, from, next) => {
    $vs.loading.close()
})


Vue.use(VueAuth, auth)

const app = new Vue({
    el: '#app',
    router
});
