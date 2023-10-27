import {createRouter, createWebHistory} from "vue-router";
import HomeComponent from "./components/HomeComponent.vue";
import CommentsComponent from "./components/CommentsComponent.vue";


const router = createRouter({
  history: createWebHistory(),
  routes: [

    { path: '/', component: HomeComponent, name: 'home' },
    { path: '/comments', component: CommentsComponent, name: 'comments' },


  ]
})

export default router
