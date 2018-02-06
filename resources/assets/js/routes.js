import Vue from 'vue'
import VueRouter from 'vue-router'
Vue.use(VueRouter)

let Messages = require('./components/Messages.vue');
let Chat = require('./components/Chat.vue');


const routes = [
  {
    path : '/',
    component : Messages,
    props : true
  },
  {
    path : '/chat/:userId',
    name : 'chat',
    component : Chat,
    props : true
  }
]

export default new VueRouter({
    routes,
});
