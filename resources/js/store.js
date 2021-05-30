import Vue from 'vue'
import Vuex from 'vuex'
import { router } from './router.js'

Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        currentUser: null,
        isLoggedIn: null,
        loading: false,
        auth_error: null,
        reg_error:null,
        registeredUser: null,
    },

    getters: {
        isLoading(state){
            return state.loading;
        },
        isLoggedin(state){
            return state.isLoggedin;
        },
        currentUser(state){
            return state.currentUser;
        },
        authError(state){
            return state.auth_error;
        },
        regError(state){
            return state.reg_error;
        },
        registeredUser(state){
            return state.registeredUser;
        },
    },

    mutations: {
        login(state){
            state.loading = true;
            state.auth_error = null;
        },
        loginSuccess(state, payload){
            state.auth_error = null;
            state.isLoggedin = true;
            state.loading = false;
            state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});
            localStorage.setItem('token', payload.data.access_token);
        },
        loginFailed(state, payload){
            state.loading = false;
            state.auth_error = payload.error;
        },
        logout(state){
            localStorage.removeItem("token");
            state.isLoggedin = false;
            state.currentUser = null;
            console.log('logout')
            router.push({path: '/login'});
        },
        registerSuccess(state, payload){
            state.reg_error = null;
            state.registeredUser = payload.user;
        },
        registerFailed(state, payload){
            state.reg_error = payload.error;
        },
    },

    actions: {
        login(context){
            context.commit("login");
        },
    }
})