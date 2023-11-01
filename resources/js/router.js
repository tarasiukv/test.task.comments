import {createRouter, createWebHistory} from "vue-router";
import CommentsComponent from "./components/CommentsComponent.vue";


const router = createRouter({
  history: createWebHistory(),
  routes: [

    { path: '/', component: CommentsComponent, name: 'home' },


  ]
})

export default router
