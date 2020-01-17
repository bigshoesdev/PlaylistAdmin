import Vue from 'vue';
import Vuex from 'vuex';
import Alert from './components/modals/Alert.vue'
import WebSocials from './components/modals/WebSocials.vue'
import dictionary from './dictionary';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    user: {
      loaded: false,
      logged: false,
    },
    sidebar: {
      opened: false,
    },
    education: null,
    alert: false,
    reply_counter: null,
  },
  mutations: {
    closeSidebar(state) {
      state.sidebar.opened = false;
    },
    logout(state) {
      state.user = {
        logged: false,
        loaded: true
      };
      delete axios.defaults.headers.common['Authorization'];
      localStorage.removeItem('auth');
    },
    setPinStatus(state, status) {
      state.user.pin_exists = status;
    },
    auth(state, auth) {
      localStorage.setItem('auth', auth);
      axios.defaults.headers.common['Authorization'] = auth;
      state.user = {
        logged: true,
        loaded: true,
      };
    },
    passEducation(state, data) {
      if(state.education.hasOwnProperty(data)) {
        state.education[data] = true;
        localStorage.setItem('education', JSON.stringify(state.education));
      } else {
        throw Error('No education type found');
      }
    },
    alert(state, text) {
      if(state.alert) return;
      state.alert = true;
      app.$modal.show(Alert, {
        text: text
      }, {
        adaptive: true,
        height: 'auto',
        width: 300,
      }, {
        closed: () => {
          state.alert = false;
        }
      });
    },
    decreaseReplyCounter(state) {
      if (state.reply_counter > 0) {
        state.reply_counter--;
        localStorage.setItem('reply_counter', state.reply_counter);
      }
    },
    setReplyCounter(state, num) {
      state.reply_counter = num;
      localStorage.setItem('reply_counter', state.reply_counter);
    },
    decreaseSubsGamesCounter(state) {
      if(state.user.subscribe_games > 0) {
        state.user.subscribe_games--;
      }
    },
    setUserData(state, data) {
      state.user = {
        ...data,
        logged: true,
        loaded: true,
      };
    },
  },
  actions: {
    updateUserData(context) {
      axios.get('/user/info').then(res => {
        context.commit('setUserData', res.data);
      });
    },
    sharePlaylist(context) {
      if(window.cordova) {
        return new Promise(function(resolve, reject) {
          navigator.share(dictionary.share.playlist, undefined, undefined, () => {
            context.commit('setReplyCounter', vars.GAMES_FOR_REPLY);
            resolve();
          });
        });
      } else {
        return context.dispatch('showWebSocials', {
          text: dictionary.share.playlist,
          title: 'Playlist',
        }).then(() => {
          context.commit('setReplyCounter', vars.GAMES_FOR_REPLY);
        });
      }
    },
    shareShootlist(context) {
      if(window.cordova) {
        return new Promise(function(resolve, reject) {
          navigator.share(dictionary.share.shootlist, undefined, undefined, () => {
            context.commit('setReplyCounter', vars.GAMES_FOR_REPLY);
            resolve();
          });
        });
      } else {
        return context.dispatch('showWebSocials', {
          text: dictionary.share.shootlist,
          title: 'Playlist',
        }).then(() => {
          context.commit('setReplyCounter', vars.GAMES_FOR_REPLY);
        });
      }
    },
    shareFunzone(context) {
      if(window.cordova) {
        return new Promise((resolve, reject) => {
          navigator.share(dictionary.share.funzone, undefined, undefined, resolve);
        });
      } else {
        return context.dispatch('showWebSocials', {
          text: dictionary.share.funzone,
          title: 'Playlist',
        });
      }
    },
    showWebSocials(context, data) {
      return new Promise((resolve, reject) => {
        app.$modal.show(WebSocials, {
          text: data.text,
          title: data.title,
          onSuccess: resolve,
        }, {
          adaptive: true,
          height: 'auto',
          width: 200,
          pivotY: 0.8,
        });
      });
    },
    decreaseSubsGamesCounter(context) {
      if(!context.getters.hasSubs && context.state.user.subscribe_games > 0) {
        context.state.user.subscribe_games--;
      }
    },
    inscreaseGameCounter(context) {
      context.state.user.games_summary++;
    },
  },
  getters: {
    replyCounter(state) {
      if(state.reply_counter) {
        return state.reply_counter;
      }

      state.reply_counter = parseInt(localStorage.getItem('reply_counter'));

      if(state.reply_counter == null) {
        localStorage.setItem('reply_counter', 0);
        state.reply_counter = 0;
      }

      return state.reply_counter;
    },
    hasSubs(state) {
      if(!state.user.subscribe) return false;
      var time = Math.floor(Date.now() / 1000);
      return state.user.subscribe + state.user.subscribe_interval > time;
    },
    canAccessGames(state, getters) {
      return getters.hasSubs || state.user.subscribe_games != 0;
    },
    education(state) {

      if(state.education) {
        return state.education;
      }

      state.education = localStorage.getItem('education');

      if(!state.education) {
        const default_state = {
          'playlist-family': false,
          'playlist-adult': false,
          'playlist-children': false,
          'shootlist': false,
          'parent': false,
        };
        localStorage.setItem('education', JSON.stringify(default_state));
        state.education = default_state;
      } else {
        state.education = JSON.parse(state.education);
      }

      return state.education;
    }
  }
});

export default store;
