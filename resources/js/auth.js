import bearer from '@websanova/vue-auth/drivers/auth/bearer'
import axios from '@websanova/vue-auth/drivers/http/axios.1.x'
import router from '@websanova/vue-auth/drivers/router/vue-router.2.x'
/**
 * Authentication configuration, some of the options can be override in method calls
 */
const config = {
    auth: bearer,
    http: axios,
    router: router,
    tokenDefaultName: 'laravel-jwt-auth',
    tokenStore: ['localStorage'],
    rolesVar: 'level',

    // API endpoints used in Vue Auth.
    registerData: {
        url: '/api/v1/auth/register',
        method: 'POST',
        redirect: '/auth/login'
    },
    loginData: {
        url: '/api/v1/auth/login',
        method: 'POST',
        redirect: '',
        fetchUser: true
    },
    logoutData: {
        url: '/api/v1/auth/logout',
        method: 'POST',
        redirect: '/auth/login',
        makeRequest: true
    },
    fetchData: {
        url: '/api/v1/auth/user',
        method: 'GET',
        enabled: true
    },
    refreshData: {
        url: '/api/v1/auth/refresh',
        method: 'GET',
        enabled: true,
        interval: 30
    }
}
export default config