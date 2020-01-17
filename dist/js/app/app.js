import App from './App.vue';
import Vue from 'vue';
import VueModal from 'vue-js-modal'
import VueFilterDateFormat from 'vue-filter-date-format'
import VueMask from 'v-mask'

window.ebus = document.documentElement;

document.addEventListener('deviceready', function () {
  MobileAccessibility.usePreferredTextZoom(false);
}, false);

if(!window.cordova) {
  FB.init({
    appId: '269566853940695',
    status: true,
    xfbml: true,
    version: 'v3.3'
  });
}

import './preferences';
import './vars';

Vue.use(VueModal, { dynamic: true, injectModalsContainer: true });
Vue.use(VueFilterDateFormat);
Vue.use(VueMask);

import store from './store.js';
import router from './router.js';

window.app = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App),
  data: {
    animation: '',
    include: [
      'reg',
      'playlist',
      'shootlist',
      'shootlist-options',
      'playlist-options',
      'playlist-mode',
    ],
  },
  methods: {
    debug(data) {
      axios.post('/debug', data);
    },
  },
  created() {
    if(window.clientHeight < 575) {
      this.$store.non_mobile = true;
    }
    window.addEventListener('popstate', e => {
      this.animation = 'slide-back';
    }, false);
    this.$store.watch(state => {
      if(state.user.logged) {
        this.include = [
          'playlist',
          'shootlist',
          'shootlist-options',
          'playlist-options',
          'playlist-mode',
        ];
      } else {
        this.include = [
          'reg',
          'login',
        ];
      }
      return state.user.logged;
    }, state => {

    })
  }
});
