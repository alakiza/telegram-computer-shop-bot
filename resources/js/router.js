import Vue from 'vue'
import VueRouter from 'vue-router'

import Patients from "./views/Patients.vue"
import Doctors from "./views/Doctors.vue"
import Sensors from "./views/Sensors.vue"
import Login from "./views/Login.vue"

Vue.use(VueRouter)

export const router = new VueRouter({
    mode: 'history',
    routes: [
      {
        path: '/',
        name: 'patients',
        component: Patients
      },
      {
        path: '/doctors',
        name: 'doctors',
        component: Doctors
      },
      {
        path: '/sensors',
        name: 'sensors',
        component: Sensors
      },
      {
        path: '/login',
        name: 'login',
        component: Login,
      }
    ],
  });
