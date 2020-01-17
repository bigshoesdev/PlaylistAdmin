import ParentCodeModal from './components/ParentCodeModal.vue';

import store from './store.js';

export default function(route) {

  store.state.sidebar.opened = false;

  if(!route.meta.middleware) {
    return route;
  }

  if(route.name == 'event-slider' && localStorage.getItem('event-slider') === 'true') {
    return { name: 'events' };
  }

  // if(route.name == 'shootlist' && (!route.params.gender || !route.params.num)) {
  //   return { name: 'shootlist-options' };
  // }

  // education block
  if((route.meta.middleware == 'parent' || route.name == 'parent') && !store.getters.education['parent']) {
    store.commit('passEducation', 'parent');
    return { name: 'video-parent' };
  }

  if(route.name == 'shootlist-options' && !store.getters.education['shootlist']) {
    store.commit('passEducation', 'shootlist');
    return { name: 'video-shootlist' };
  }

  if(route.name == 'playlist-options' && route.params.section == 'adult' && !store.getters.education['playlist-adult']) {
    store.commit('passEducation', 'playlist-adult');
    return { name: 'video-playlist-adult' };
  }

  if(route.name == 'playlist-mode' && !store.getters.education['playlist-children']) {
    store.commit('passEducation', 'playlist-children');
    return { name: 'video-playlist-children' };
  }

  if(route.name == 'playlist-options' && route.params.section == 'family' && !store.getters.education['playlist-family']) {
    store.commit('passEducation', 'playlist-family');
    return { name: 'video-playlist-family' };
  }
  // end education

  if(route.name == 'playlist-options' && !route.params.section) {
    return { name: 'playlist-sections' };
  }

  if(route.meta.middleware == 'guest') {
    if(store.state.user.logged)
      return { name: 'main' };
    else
      return route;
  }

  if(route.meta.middleware == 'auth' || route.meta.middleware == 'parent') {
    if(!store.state.user.logged) {
      return { name: 'login' };
    }
    if(route.meta.middleware == 'parent') {
      if(!store.state.user.parent && !store.state.user.no_pin) {
        if(store.state.user.pin_exists) {
          app.$modal.show(ParentCodeModal, {
            to: route
          }, {
            adaptive: true,
            height: 'auto',
            width: 300,
          });
          return { name: 'parent' };
        } else {
          return { name: 'new-pin', params: { to: route } };
        }
      } else {
        return route;
      }
    } else {
      return route;
    }
  }
}
