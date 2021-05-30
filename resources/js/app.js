import Vue from 'vue'

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

import {router} from './router.js'
import {store} from './store.js'

import App from './views/App.vue'

const app = new Vue({
  el: '#app',
  store,
  components: { App },
  router,
}); 
