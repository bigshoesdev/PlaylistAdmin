import Vue from 'vue';
import VueRouter from 'vue-router';

import store from './store';
import middleware from './middleware';

Vue.use(VueRouter);

import Index from './views/Index.vue';
import Slider from './views/Slider.vue';
import Login from './views/Login.vue';
import Welcome from './views/Welcome.vue';
import Reg from './views/Reg.vue';
import Confirm from './views/Confirm.vue';
import Main from './views/Main.vue';
import Shootlist from './views/Shootlist.vue';
import ShootlistOptions from './views/ShootlistOptions.vue';
import Playlist from './views/Playlist.vue';
import TeacherLessons from './views/TeacherLessons.vue';
import PlaylistOptions from './views/PlaylistOptions.vue';
import PlaylistMode from './views/PlaylistMode.vue';
import PlaylistSections from './views/PlaylistSections.vue';
import Funkit from './views/Funkit.vue';
import Event from './views/Event.vue';
import Events from './views/Events.vue';
import EventCreate from './views/EventCreate.vue';
import EventJoin from './views/EventJoin.vue';
import EventSlider from './views/EventSlider.vue';
import Parent from './views/Parent.vue';
import ParentLikes from './views/ParentLikes.vue';
import ParentCategories from './views/ParentCategories.vue';
import Account from './views/Account.vue';
import Liked from './views/Liked.vue';
import Mish from './views/Mish.vue';
import Search from './views/Search.vue';
import RequestsPlaylistAdd from './views/RequestsPlaylistAdd.vue';
import RequestsPlaylist from './views/RequestsPlaylist.vue';
import RequestsShootlistAdd from './views/RequestsShootlistAdd.vue';
import RequestsShootlist from './views/RequestsShootlist.vue';
import NewPin from './views/NewPin.vue';
import About from './views/About.vue';
import Terms from './views/Terms.vue';
import Notifications from './views/Notifications.vue';
import RestoreParentCode from './views/RestoreParentCode.vue';
import Workshop from './views/Workshop.vue';
import WorkshopSingle from './views/WorkshopSingle.vue';
import Contact from './views/Contact.vue';
import Recover from './views/Recover.vue';
import RecoverCheckCode from './views/RecoverCheckCode.vue';
import RecoverNewPass from './views/RecoverNewPass.vue';
import FunKitDelivery from './views/FunKitDelivery.vue';
import Subscribe from './views/Subscribe.vue';
import Video from './views/Video.vue';
import Delivery from './views/Delivery.vue';
import FunZoneConclusion from './views/FunZoneConclusion.vue';
import Schools from './views/Schools.vue';

import VideoShootlist from './views/video/Shootlist.vue';
import VideoPlaylistAdult from './views/video/PlaylistAdult.vue';
import VideoPlaylistChildren from './views/video/PlaylistChildren.vue';
import VideoPlaylistFamily from './views/video/PlaylistFamily.vue';
import VideoParent from './views/video/Parent.vue';

const router = new VueRouter({
  routes: [
    {
      path: '/',
      component: Index,
      name: 'index',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/slider',
      component: Slider,
      name: 'slider',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/welcome',
      component: Welcome,
      name: 'welcome',
      meta: {
        middleware: 'guest',
      },
    },
    {
      path: '/reg',
      component: Reg,
      name: 'reg',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/confirm',
      component: Confirm,
      name: 'confirm',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/login',
      component: Login,
      name: 'login',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/main',
      component: Main,
      name: 'main',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/shootlist',
      component: Shootlist,
      name: 'shootlist',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/shootlist-options',
      component: ShootlistOptions,
      name: 'shootlist-options',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/sections',
      component: PlaylistSections,
      name: 'playlist-sections',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/mode',
      component: PlaylistMode,
      name: 'playlist-mode',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/options/:section',
      component: PlaylistOptions,
      name: 'playlist-options',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist',
      component: Playlist,
      name: 'playlist',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/teacher-lessons',
      component: TeacherLessons,
      name: 'teacher-lessons',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/funkit',
      component: Funkit,
      name: 'funkit',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/event/slider',
      component: EventSlider,
      name: 'event-slider',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/event/create',
      component: EventCreate,
      name: 'event-create',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/event/:id_event/join',
      component: EventJoin,
      name: 'event-join',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/event/:id_event',
      component: Event,
      name: 'event',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/events',
      component: Events,
      name: 'events',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/parent',
      component: Parent,
      name: 'parent',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/parent-likes',
      component: ParentLikes,
      name: 'parent-likes',
      meta: {
        middleware: 'parent',
      }
    },
    {
      path: '/parent-categories',
      component: ParentCategories,
      name: 'parent-categories',
      meta: {
        middleware: 'parent',
      }
    },
    {
      path: '/account',
      component: Account,
      name: 'account',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/liked',
      component: Liked,
      name: 'liked',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/requests/playlist/add',
      component: RequestsPlaylistAdd,
      name: 'requests-playlist-add',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/requests/playlist',
      component: RequestsPlaylist,
      name: 'requests-playlist',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/requests/shootlist/add',
      component: RequestsShootlistAdd,
      name: 'requests-shootlist-add',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/requests/shootlist',
      component: RequestsShootlist,
      name: 'requests-shootlist',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/search',
      component: Search,
      name: 'search',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/mish',
      component: Mish,
      name: 'mish',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/new-pin',
      component: NewPin,
      name: 'new-pin',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/about',
      component: About,
      name: 'about',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/terms',
      component: Terms,
      name: 'terms',
    },
    {
      path: '/restore-parent-code',
      component: RestoreParentCode,
      name: 'restore-parent-code',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/notifications',
      component: Notifications,
      name: 'notifications',
      meta: {
        middleware: 'parent',
      }
    },
    {
      path: '/workshop',
      component: Workshop,
      name: 'workshop',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/workshop/:id_workshop',
      component: WorkshopSingle,
      name: 'workshop-single',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/contact',
      component: Contact,
      name: 'contact',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/recover',
      component: Recover,
      name: 'recover',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/recover/code',
      component: RecoverCheckCode,
      name: 'recover-code',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/recover/:code/pass',
      component: RecoverNewPass,
      name: 'recover-new-pass',
      meta: {
        middleware: 'guest',
      }
    },
    {
      path: '/funkit/:id_funkit',
      component: FunKitDelivery,
      name: 'funkit-delivery',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/subscribe',
      component: Subscribe,
      name: 'subscribe',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/shootlist/video',
      component: VideoShootlist,
      name: 'video-shootlist',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/family/video',
      component: VideoPlaylistFamily,
      name: 'video-playlist-family',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/children/video',
      component: VideoPlaylistChildren,
      name: 'video-playlist-children',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/playlist/adult/video',
      component: VideoPlaylistAdult,
      name: 'video-playlist-adult',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/parent/video',
      component: VideoParent,
      name: 'video-parent',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/video',
      component: Video,
      name: 'video',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/delivery',
      component: Delivery,
      name: 'delivery',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/funzone/:id_event/conclusion',
      component: FunZoneConclusion,
      name: 'funzone-conclusion',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/schools',
      component: Schools,
      name: 'schools',
      meta: {
        middleware: 'auth',
      }
    },
    {
      path: '/share/:type/:id',
      component: Index,
      name: 'shared',
      meta: {
        middleware: 'guest',
      }
    },
  ]
});

router.beforeEach(async (to, from, next) => {

  if(!store.getters['user/isLoaded']) {
    if(store.getters['user/token']) {
      await axios.get('/user/info', {
        headers: {
          'Authorization': store.getters['user/auth']
        }
      }).then(res => {
        store.commit('user/auth', store.getters['user/token']);
        store.commit('user/saveData', res.data);
      }).catch(res => {
        console.error('User wrong auth token!');
      });
    }
    store.commit('user/loaded');
  }

  var route = middleware(to);
  if(route.name != to.name) {
    next(route);
  } else {
    next();
  }

});

export default router;
