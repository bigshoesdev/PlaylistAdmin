<template lang="html">
  <main>
    <simple-header route="/main">
      <span slot="title"><span class="fugaz">PLAY list</span></span>
      <span slot="additional">
        <sidebar-toggler/>
      </span>
    </simple-header>
    <div class="card-wrapper"
      @mousedown="onMouseDown"
      @mousemove="onMouseMove"
      @mouseup="onMouseUp"
      @touchstart="onMouseDown"
      @touchmove="onMouseMove"
      @touchend="onMouseUp"
    >
      <card
        :data="item"
        v-for="(item, i) in data"
        v-if="item"
        :key="item.id_playlist + '-' + item.order_index"
        :first="i == data.length - 1"
        ref="cards"
      />
      <card-dummy v-if="!data.length"/>
    </div>
    <div v-if="data.length" class="buttons" dir="rtl">
      <button class="prev img-arrow-right" @click="skip()">
        לא שחקתי
      </button>
      <button class="like" @click="like" :class="{ liked: currentItem && currentItem.liked }">
        <i class="icon ion-md-heart"></i>
      </button>
      <button class="next img-arrow-left" @click="next()">
        שחקתי
      </button>
    </div>
    <div v-if="data.length" class="small" dir="rtl">
      <button class="settings" @click="goToOptions">
        <i class="icon ion-md-settings"></i>
        הגדרות
      </button>
      <button class="reply" @click="share">
        <i class="icon ion-ios-undo"></i>
        שיתוף
      </button>
      <button class="idea" @click="getCurrentRef().openVideo()">
        <i class="icon ion-md-play-circle"></i>
        הדגמה
      </button>
    </div>
    <game-idea
      v-if="currentItem"
      :user="currentItem.author"
      :school="currentItem.school"
      :city="currentItem.city"
      route="/requests/playlist"
    />
    <gift-line v-if="settings.section != 'adult'" ref="giftline"/>
  </main>
</template>

<script>
import Card from './../components/playlist/Card.vue';
import CardDummy from './../components/playlist/CardDummy.vue';
import GameIdea from './../components/GameIdea.vue';
import GiftLine from './../components/GiftLine.vue';
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import WannaSubscribe from './../components/modals/WannaSubscribe.vue';
import Subscribe from './../components/modals/Subscribe.vue';
import NoGamesFound from './../components/modals/NoGamesFound.vue';
import GamesEnded from './../components/modals/GamesEnded.vue';

export default {
  name: 'playlist',
  data() {
    return {
      data: [],
      index: 1,
      order_index: 1,
      settings: {},
      grapped: false,
      starting_x: 0,
      animating: false,
      loading: false,
    };
  },
  watch: {
    index(to) {
      if(to % 13 == 0 && !localStorage.getItem('subs_block')) {
        this.$modal.show(WannaSubscribe, {}, {
          height: 'auto',
          adaptive: true,
          // width: 300,
        });
      }
      else if(to % 21 == 0 && !this.$store.getters.hasSubs) {
        this.$modal.show(Subscribe, {}, {
          height: 'auto',
          adaptive: true,
          scrollable: true,
        });
      }
    },
    data(to, from) {

      if(to.length) {
        var last = to[to.length - 1];

        if(!last && !this.subs_show) {
          this.subs_show = true;
          this.$modal.show(Subscribe, {}, {
            height: 'auto',
            adaptive: true,
            scrollable: true,
          }, {
            'before-close': () => {
              this.$router.replace('/main');
            },
            'closed': () => {
              this.subs_show = false;
            }
          });
        }

        if(
          !this.animating &&
          last &&
          ((last.order_index % this.count == 1) || (this.count == 1))  &&
          last.order_index > 1 &&
          !this.random
        ) {
          this.$modal.show(GamesEnded, {
            toOptions: this.goToOptions,
          }, {
            height: 'auto',
            width: 320,
            adaptive: true,
            scrollable: true,
          });
        }
      }
    },
  },
  components: {
    Card,
    GameIdea,
    GiftLine,
    SimpleHeader,
    SidebarToggler,
    CardDummy,
  },
  computed: {
    currentItem() {
      return this.data[this.data.length - 1];
    }
  },
  methods: {
    goToOptions() {
      return new Promise((resolve, reject) => {
        if(this.settings.section) {
          this.$router.push('/playlist/options/' + this.settings.section);
        } else {
          this.$router.push('/playlist/options/family');
        }
        resolve();
      });
    },
    getCurrentRef() {
      return this.$refs['cards'][0];
    },
    getNextRef() {
      return this.$refs['cards'][1];
    },
    onMouseDown(e) {

      if(!this.currentItem) return;

      var ref = this.getCurrentRef();

      if(ref.advertisment || this.animating) return;

      ref.$el.classList.add('grapped');
      this.grapped = true;
      this.starting_x = (e.x ? e.x : e.touches[0].clientX);
    },
    onMouseMove(e) {
      // сделать кеширование селекторов
      if(!this.grapped) return;

      var el = this.getCurrentRef().$el;
      var next_el = this.getNextRef().$el;

      var offset = (e.x ? e.x : e.touches[0].clientX) - this.starting_x;

      var translate = offset.map(0, el.offsetWidth, 0, 100);
      var rotate = offset.map(0, el.offsetWidth, 0, 60);
      el.style.transform = `translateX(${translate}%) rotate(${rotate}deg)`;

      var side = translate > 0 ? 'right' : 'left';
      var opacity = Math.abs(offset.map(0, el.offsetWidth / 2, 0, 1));

      el.getElementsByClassName('back-placeholder')[0].style.display = 'none';
      el.getElementsByClassName('next-placeholder')[0].style.display = 'none';

      if(side == 'right') {
        el.getElementsByClassName('back-placeholder')[0].style.opacity = opacity;
        el.getElementsByClassName('back-placeholder')[0].style.display = 'block';
      } else {
        el.getElementsByClassName('next-placeholder')[0].style.opacity = opacity;
        el.getElementsByClassName('next-placeholder')[0].style.display = 'block';
      }

      next_el.getElementsByClassName('title')[0].style.opacity = opacity;

      if(window.cordova && window.cordova.platformId == 'ios') {
        e.preventDefault();
      }

    },
    onMouseUp(e) {
      if(!this.grapped) return;

      var el = this.getCurrentRef().$el;
      var next_el = this.getNextRef();
      if(next_el) {
        next_el = this.getNextRef().$el;
      }

      el.classList.remove('grapped');
      el.classList.add('swiping');
      var offset = (e.x ? e.x : e.changedTouches[0].clientX) - this.starting_x;
      var percentage = offset.map(0, el.offsetWidth, 0, 100);
      var side = percentage > 0 ? 'right' : 'left';
      percentage = Math.abs(percentage);

      if(percentage >= 50) {
        if(side == 'left') {
          el.classList.add('swipe-left');
          this.next(true);
        } else {
          el.classList.add('swipe-right');
          this.skip(true);
        }
        if(next_el) {
          next_el.getElementsByClassName('title')[0].style.opacity = '1';
        }
      } else {
        el.style.transform = 'translateX(0) rotate(0)';
        el.getElementsByClassName('back-placeholder')[0].style.display = '';
        el.getElementsByClassName('next-placeholder')[0].style.display = '';
        el.getElementsByClassName('back-placeholder')[0].style.opacity = '';
        el.getElementsByClassName('next-placeholder')[0].style.opacity = '';
        if(next_el) {
          next_el.getElementsByClassName('title')[0].style.opacity = '';
        }
      }

      this.animating = true;
      this.grapped = false;

      setTimeout(() => {
        this.animating = false;
        if(percentage < 50) {
          el.classList.remove('swiping', 'swipe-right', 'swipe-left');
        } else {
          this.data.splice(-1, 1);
        }
      }, 1000);
    },
    share() {
      // this.$store.commit('setReplyCounter', 50);
      this.$store.dispatch('sharePlaylist');
    },
    next(swipe = false) {

      var ref = this.getCurrentRef();

      if(this.loading || ref.advertisment || this.animating) return;
      axios.put('/playlist/' + this.currentItem.id_playlist + '/activity/ans');
      this.getRandom();
      if(!swipe) {
        this.animating = true;
        ref.$el.classList.add('animate', 'animate-left');
        var next_ref = this.getNextRef();
        if(next_ref) {
          next_ref.$el.getElementsByClassName('title')[0].classList.add('show');
        }
        setTimeout(() => {
          this.animating = false;
          this.data.splice(-1, 1);
        }, 2000);
      }
    },
    skip(swipe = false) {

      var ref = this.getCurrentRef();

      if(this.loading || ref.advertisment || this.animating) return;
      axios.put('/playlist/' + this.currentItem.id_playlist + '/activity/skp');
      this.getRandom();

      if(!swipe) {
        this.animating = true;
        ref.$el.classList.add('animate', 'animate-right');
        var next_ref = this.getNextRef();
        if(next_ref) {
          next_ref.$el.getElementsByClassName('title')[0].classList.add('show');
        }
        setTimeout(() => {
          this.animating = false;
          this.data.splice(-1, 1);
        }, 2000);
      }
    },
    getRandom() {
      return new Promise((resolve, reject) => {
        var data = { ...this.settings, order_index: this.order_index };

        if(this.$route.params.id_playlist) {
          data.id_playlist = this.$route.params.id_playlist;
        }

        this.loading = true;

        axios.post('/playlist/random', data).then(res => {

          delete this.$route.params.id_playlist;
          res.data.result.index = this.index;
          res.data.result.order_index = this.order_index;
          this.data.unshift(res.data.result);

          this.index++;
          this.order_index++;

          this.$store.commit('decreaseReplyCounter');
          this.$store.dispatch('decreaseSubsGamesCounter');
          this.$store.dispatch('inscreaseGameCounter');
          this.random = res.data.meta.random;
          this.count = res.data.meta.count;

          if(res.data.meta.random && !this.popup_showed) {
            this.$modal.show(NoGamesFound, {}, {
              height: 'auto',
              width: 300,
            });
            this.popup_showed = true;
          }
          this.$nextTick(() => {
            resolve();
          });
        }).catch(res => {
          if(res.status == 402) {
            this.data.unshift(null);
            reject();
          }
        }).finally(() => {
          this.loading = false;
        });
      });
    },
    getState() {
      return new Promise((resolve, reject) => {
        axios.post('/playlist/filter-state/get', this.settings).then(res => {
          if(res.data.order_index) {
            this.order_index = res.data.order_index - 1;
          } else {
            this.order_index = 1;
          }
          resolve();
        });
      });
    },
    like() {
      if(!this.currentItem.liked) {
        axios.put('/playlist/' + this.currentItem.id_playlist + '/activity/lik');
      }
      this.currentItem.liked = !this.currentItem.liked;
      axios.put('/playlist/' + this.currentItem.id_playlist + '/like');
    },
  },
  activated() {
    window.scrollTo(0, 0);

    var update = (JSON.stringify(this.settings) != JSON.stringify(this.$route.params)) && this.$route.params.section;

    if(update && !this.$route.params.id_playlist) {
      this.settings = this.$route.params;
    }

    if(
      !this.data.length ||
      update ||
      this.$route.params.id_playlist
    ) {
      this.popup_showed = false;
      this.data = [];

      this.getState().then(() => {
        this.getRandom().then(() => {
          this.getRandom().then();
        });
      });
    }
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  padding-bottom: 0 !important;
  .card-wrapper {
    height: 450px;
    width: 100%;
    position: relative;
    z-index: 1;
  }
  .buttons {
    padding: 15px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    button {
      color: #fff;
      height: 34px;
      border-radius: 20px;
      border: 2px solid #fff;
      font-size: 18px;
      line-height: 30px;
      padding: 0;
    }
    .prev {
      padding-left: 8px;
      text-align: left;
      width: 110px;
      background-color: #FF7384;
      background-position: right 5px center;
      background-repeat: no-repeat;
    }
    .next {
      width: 110px;
      background-color: $clr-green;
      background-position: left 5px center;
      background-repeat: no-repeat;
    }
    .like {

      padding: 0 20px;
      &.liked {
        border-color: #00B4DB;
        color: #00B4DB;
        i {
          opacity: 1;
        }
      }
      i {
        opacity: 0.5;
        vertical-align: middle;
      }
    }
  }
  .small {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    justify-content: space-between;
    padding: 10px 15px;
    padding-top: 0;
    button {
      padding: 0;
      color: #fff;
      font-size: 16px;
      &.reply {
        i::before {
          transform: scale(-1, 1);
        }
      }
      i {
        font-size: 24px;
        margin: 0 5px;
        vertical-align: middle;
      }
    }
  }
  .small-white {
    position: absolute;
    left: 15px;
    color: #fff;
    font-size: 12px;
  }
}

</style>
