import {createRouter, createWebHistory} from "vue-router";
import HomeComponent from "./components/HomeComponent.vue";


const router = createRouter({
  history: createWebHistory(),
  routes: [

    { path: '/', component: HomeComponent, name: 'home' },


  ]
})

export default router