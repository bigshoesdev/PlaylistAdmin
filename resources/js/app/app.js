import Vue from 'vue';
import VueRouter from 'vue-router';
import Vuex from 'vuex';
import VueCookie from 'vue-cookie';
import Vuetify from 'vuetify'
import VueFilterDateFormat from 'vue-filter-date-format'

import Authorized from './roots/Authorized.vue'
import Guest from './roots/Guest.vue'

window.axios = require('axios');
axios.interceptors.response.use(res => {
  return res;
}, err => {
  return Promise.reject(err.response);
});

Vue.use(VueRouter);
Vue.use(Vuex);
Vue.use(VueCookie);
Vue.use(Vuetify);
Vue.use(VueFilterDateFormat);

import Index from './views/Index.vue';
import Playlist from './views/Playlist.vue';
import PlaylistEdit from './views/PlaylistEdit.vue';
import Gifts from './views/Gifts.vue';
import Login from './views/Login.vue';
import Workshop from './views/Workshop.vue';
import Funkit from './views/Funkit.vue';
import Shootlist from './views/Shootlist.vue';
import ShootlistEdit from './views/ShootlistEdit.vue';
import EventCategories from './views/EventCategories.vue';
import PlaylistLocations from './views/PlaylistLocations.vue';
import PlaylistSections from './views/PlaylistSections.vue';
import School from './views/School.vue';
import Users from './views/Users.vue';
import Parent from './views/Parent.vue';
import Funzone from './views/Funzone.vue';
import Teachers from './views/Teachers';

const router = new VueRouter({
  routes: [
    { path: '/', component: Index, name: 'index' },
    { path: '/login', component: Login, name: 'login' },
    { path: '/playlist', component: Playlist, name: 'playlist' },
    { path: '/playlist/locations', component: PlaylistLocations, name: 'playlist-locations' },
    { path: '/playlist/sections', component: PlaylistSections, name: 'playlist-sections' },
    { path: '/playlist/:id_playlist', component: PlaylistEdit, name: 'playlist-edit' },
    { path: '/gifts', component: Gifts, name: 'gifts' },
    { path: '/workshop', component: Workshop, name: 'workshop' },
    { path: '/shootlist', component: Shootlist, name: 'shootlist' },
    { path: '/shootlist/:id_shootlist', component: ShootlistEdit, name: 'shootlist-edit' },
    { path: '/funkit', component: Funkit, name: 'funkit' },
    { path: '/event/categories', component: EventCategories, name: 'event-categories' },
    { path: '/school', component: School, name: 'school' },
    { path: '/users', component: Users, name: 'users' },
    { path: '/parent', component: Parent, name: 'parent' },
    { path: '/funzone', component: Funzone, name: 'funzone' },
    { path: '/teachers', component: Teachers, name: 'teachers' }
  ]
});

const store = new Vuex.Store({
  state: {

  },
  mutations: {

  }
});

const app = new Vue({
  el: '#app',
  router,
  store,
  /*vuetify: new Vuetify({}),*/
  data: {
    logged: false,
    loaded: false,
  },
  methods: {

  },
  watch: {

  },
  components: {
    Guest,
    Authorized,
  },
  created() {
    if(this.$cookie.get('auth')) {
      axios.defaults.headers.common['Authorization'] = this.$cookie.get('auth');
      axios.get('/user/info').then(res => {
        this.logged = true;
      }).catch(res => {
        this.logged = false;
        this.$router.push('/login');
      }).finally(res => {
        this.loaded = true;
      });
    } else {
      this.logged = false;
      this.$router.push('/login');
    }
  },
  mounted() {

  }
});

router.beforeEach((to, from, next) => {

  if(!app.logged && app.loaded) {
    next('/login');
  } else {
    console.log(to);
    next();
  }
});

import 'vuetify/dist/vuetify.min.css';
