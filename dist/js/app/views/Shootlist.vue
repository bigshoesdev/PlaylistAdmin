<template lang="html">
  <main>
    <simple-header route="/main">
      <span slot="title"><span class="fugaz">SHOOT list</span></span>
      <span slot="additional">
        <sidebar-toggler/>
      </span>
    </simple-header>
    <template v-if="data.length">
      <div class="sm-cont questions">
        <p class="top-text" dir="rtl">
          השאלה היא:
        </p>
        <div
          class="questions-container"
          @mousedown="onMouseDown"
          @mousemove="onMouseMove"
          @mouseup="onMouseUp"
          @touchstart="onMouseDown"
          @touchmove="onMouseMove"
          @touchend="onMouseUp"
          :class="{
            big: currentItem.category == 4,
            middle: currentItem.category == 5
          }"
        >
          <additional
            v-for="(item, i) in data"
            :first="i == data.length - 1"
            ref="questions"
            :key="item.id_shootlist + '-' + item.index"
            :gender="settings.gender"
            :question="item"
          />
        </div>
      </div>
      <div class="buttons" dir="rtl">
        <button class="prev img-arrow-right" @click="skip(false)">
          לא ענינו
        </button>
        <button class="like" @click="like" :class="{ liked: currentItem.liked }">
          <i class="icon ion-md-heart"></i>
        </button>
        <button class="next img-arrow-left" @click="next(false)">
          ענינו
        </button>
      </div>
      <div class="small" dir="rtl">
        <button class="settings" @click="$router.push('/shootlist-options')">
          <i class="icon ion-md-settings"></i>
          הגדרות
        </button>
        <button class="reply" @click="reply">
          <i class="icon ion-ios-undo"></i>
          שיתוף
        </button>
        <button class="idea" @click="$router.push({ name: 'requests-shootlist', params: { back: settings } })">
          <svg width="23" height="30" viewBox="0 0 23 30" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.15 8.66447L1 5.52901M10.8 5.52901V1M18.85 8.66447L22 5.52901" stroke="white" stroke-linecap="round"/>
            <path d="M9.31169 27.0434C9.31169 27.5138 9.61483 27.8498 10.1201 27.8498C10.8611 27.8498 11.5684 27.8498 12.3094 27.8498C12.6462 27.8498 12.9157 27.6818 13.0167 27.3458C12.5452 27.3458 12.0736 27.3458 11.6021 27.3458C11.501 27.3458 11.4337 27.3458 11.3326 27.3122C11.1642 27.2786 11.0632 27.1442 11.0632 26.9426C11.0632 26.7747 11.1642 26.6403 11.3326 26.6067C11.4 26.5731 11.501 26.5731 11.6021 26.5731C12.0736 26.5731 12.5789 26.5731 13.0841 26.5731L13.0841 26.0019C12.5789 26.0019 12.1073 26.0019 11.6358 26.0019C11.5347 26.0019 11.4674 26.0019 11.3663 26.0019C11.1979 25.9683 11.0632 25.8339 11.0632 25.6659C11.0632 25.498 11.1642 25.33 11.3326 25.2964C11.4 25.2628 11.501 25.2628 11.5684 25.2628C12.0736 25.2628 12.5452 25.2628 13.0841 25.2628L13.0841 24.7588C11.8379 25.0948 10.5916 25.1284 9.31169 24.7924L9.31169 25.1956C9.31169 25.8339 9.31169 26.4387 9.31169 27.0434ZM10.8948 29.1265C11.0632 29.2608 11.2653 29.2608 11.4674 29.1265C11.6695 29.0257 11.7705 28.8241 11.7368 28.5889L10.6253 28.5889C10.6253 28.8241 10.6927 28.9921 10.8948 29.1265ZM11.2653 24.7252C12.141 24.7252 13.0504 24.6244 13.8925 24.49C13.8588 24.5236 13.8251 24.5908 13.8251 24.658C13.8251 25.4308 13.8251 26.2035 13.8251 26.9762C13.8251 27.4802 13.6567 27.917 13.2862 28.2193C13.0504 28.3873 12.781 28.4881 12.5115 28.6561C12.4778 28.8241 12.4441 29.0257 12.3431 29.2272C12.1747 29.664 11.8042 29.8656 11.3326 30L10.9621 30C10.5243 29.8992 10.1537 29.664 9.98534 29.2608C9.88429 29.0593 9.88429 28.8577 9.81693 28.6225C9.78324 28.6225 9.74956 28.6225 9.71588 28.5889C9.00855 28.4209 8.57068 27.917 8.537 27.1778C8.50332 26.3715 8.50332 25.5988 8.537 24.7924C8.537 24.658 8.50332 24.5572 8.36859 24.5236C8.30122 24.49 8.23386 24.4564 8.13281 24.4229C9.14328 24.5908 10.1874 24.7252 11.2653 24.7252Z" fill="white"/>
            <path d="M4.37989 13.9393L11.1163 16.6943C11.3184 16.7951 11.4532 16.9966 11.4532 17.1982C11.4532 17.4334 11.3184 17.635 11.1163 17.7022L4.61567 20.3564C5.86191 22.641 8.28704 24.0856 10.9143 24.0856C14.8888 24.0856 18.0886 20.8603 18.0886 16.9295C18.0886 12.965 14.8551 9.77326 10.9143 9.77326C8.08494 9.77326 5.52509 11.4195 4.37989 13.9393ZM3.13365 14.0401C4.34621 10.8148 7.44498 8.66455 10.9143 8.66455C15.495 8.66455 19.2001 12.3602 19.2001 16.9295C19.2001 21.4987 15.495 25.1944 10.9143 25.1944C7.64707 25.1944 4.68303 23.2793 3.33574 20.3228C3.26838 20.1884 3.26838 20.0204 3.33574 19.886C3.40311 19.7516 3.50415 19.6508 3.63888 19.5836L9.43223 17.2318L3.43679 14.7456C3.16733 14.6448 3.0326 14.3425 3.13365 14.0401Z" fill="white"/>
            <path d="M12.4025 15.1765C12.14 15.0256 12.054 14.7046 12.2058 14.4427C12.4421 14.0354 12.3069 13.5309 11.8985 13.2963C11.4901 13.0616 10.9836 13.1972 10.7474 13.6046C10.5955 13.8664 10.2731 13.9528 10.0106 13.8019C9.74809 13.651 9.66203 13.33 9.81392 13.0681C10.354 12.1371 11.5343 11.8455 12.4386 12.3652C13.372 12.9016 13.6624 14.0772 13.1393 14.9792C12.9874 15.241 12.6651 15.3274 12.4025 15.1765Z" fill="white"/>
          </svg>
          רעיון לשאלה
        </button>
      </div>
      <div class="sm-cont">
        <spinner v-if="settings && settings.num > 1" :num="settings.num" :names="settings.names"/>
      </div>
      <game-idea
        hidden
        :user="currentItem.user"
        :school="currentItem.school"
        :city="currentItem.city"
        route="/requests/shootlist"
      />
    </template>
  </main>
</template>

<script>
import Additional from './../components/shootlist/Additional.vue';
import Spinner from './../components/shootlist/Spinner.vue';
import GameIdea from './../components/GameIdea.vue';
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';

export default {
  name: 'shootlist',
  data() {
    return {
      data: [],
      index: 1,
      order_index: 1,
      settings: null,
      animating: false,
      grapped: false,
    };
  },
  components: {
    Additional,
    Spinner,
    GameIdea,
    SimpleHeader,
    SidebarToggler,
  },
  computed: {
    currentItem() {
      return this.data[this.data.length - 1];
    },
  },
  methods: {
    getCurrentRef() {
      return this.$refs['questions'][0];
    },
    onMouseDown(e) {
      var ref = this.getCurrentRef();

      if(ref.advertisment || this.animating) return;

      ref.$el.classList.add('grapped');
      this.grapped = true;
      this.starting_x = (e.x ? e.x : e.touches[0].clientX);
    },
    onMouseMove(e) {
      if(!this.grapped) return;

      var el = this.getCurrentRef().$el;

      var offset = (e.x ? e.x : e.touches[0].clientX) - this.starting_x;

      var translate = offset.map(0, el.offsetWidth, 0, 100);
      var rotate = offset.map(0, el.offsetWidth, 0, 60);
      el.style.transform = `translateX(${translate}%) rotate(${rotate}deg)`;

      var side = translate > 0 ? 'right' : 'left';
      var opacity = Math.abs(offset.map(0, el.offsetWidth / 2, 0, 1));

      // this.$refs.card[0].$el.getElementsByClassName('back-placeholder')[0].style.display = 'none';
      // this.$refs.card[0].$el.getElementsByClassName('next-placeholder')[0].style.display = 'none';

      // if(side == 'right') {
      //   this.$refs.card[0].$el.getElementsByClassName('back-placeholder')[0].style.opacity = opacity;
      //   this.$refs.card[0].$el.getElementsByClassName('back-placeholder')[0].style.display = 'block';
      // } else {
      //   this.$refs.card[0].$el.getElementsByClassName('next-placeholder')[0].style.opacity = opacity;
      //   this.$refs.card[0].$el.getElementsByClassName('next-placeholder')[0].style.display = 'block';
      // }

      if(window.cordova && window.cordova.platformId == 'ios') {
        e.preventDefault();
      }
    },
    onMouseUp(e) {
      if(!this.grapped) return;

      var el = this.getCurrentRef().$el;
      el.classList.remove('grapped');
      el.classList.add('swiping');
      var offset = (e.x ? e.x : e.changedTouches[0].clientX) - this.starting_x;
      var percentage = offset.map(0, el.offsetWidth, 0, 100);
      var side = percentage > 0 ? 'right' : 'left';
      percentage = Math.abs(percentage);

      if(percentage >= 50) {
        if(side == 'left') {
          el.classList.add('swipe-left');
          this.next();
        } else {
          el.classList.add('swipe-right');
          this.skip();
        }
      } else {
        el.style.transform = 'translateX(0) rotate(0)';
        // this.$refs.card[0].$el.getElementsByClassName('back-placeholder')[0].style.display = '';
        // this.$refs.card[0].$el.getElementsByClassName('next-placeholder')[0].style.display = '';
        // this.$refs.card[0].$el.getElementsByClassName('back-placeholder')[0].style.opacity = '';
        // this.$refs.card[0].$el.getElementsByClassName('next-placeholder')[0].style.opacity = '';
      }

      this.animating = true;
      this.grapped = false;

      setTimeout(() => {
        this.animating = false;
        if(percentage < 50) {
          el.classList.remove('swiping', 'swipe-right', 'swipe-left');
          el.style.transform = '';
        } else {
          this.data.splice(-1, 1);
        }
      }, 1000);
    },
    reply() {
      this.$store.dispatch('shareShootlist');
    },
    next(swipe = true) {
      var ref = this.getCurrentRef();

      if(!this.loaded || this.animating || ref.advertisment) return;
      axios.put('/shootlist/' + this.currentItem.id_shootlist + '/activity/ans');
      this.getRandom();
      if(!swipe) {
        this.animating = true;
        ref.$el.classList.add('animate', 'animate-left');
        setTimeout(() => {
          this.data.splice(-1, 1);
          this.animating = false;
        }, 2000);
      }
    },
    skip(swipe = true) {
      var ref = this.getCurrentRef();

      if(!this.loaded || this.animating || ref.advertisment) return;
      axios.put('/shootlist/' + this.currentItem.id_shootlist + '/activity/skp');
      this.getRandom();
      if(!swipe) {
        this.animating = true;
        ref.$el.classList.add('animate', 'animate-right');
        setTimeout(() => {
          this.data.splice(-1, 1);
          this.animating = false;
        }, 2000);
      }
    },
    getRandom(swipe = false) {
      return new Promise((resolve, reject) => {
        this.loaded = false;
        var data = { ...this.settings, order_index: this.order_index };
        axios.post('/shootlist/random', data).then(res => {
          delete this.$route.params.id_shootlist;
          res.data.result.index = this.index;
          this.data.unshift(res.data.result);
          this.loaded = true;
          this.index++;
          this.order_index++;
          this.$store.commit('decreaseReplyCounter');
          resolve();
        });

      });
    },
    like() {
      if(!this.currentItem.liked) {
        axios.put('/shootlist/' + this.currentItem.id_shootlist + '/activity/lik');
      }
      this.currentItem.liked = !this.currentItem.liked;
      axios.put('/shootlist/' + this.currentItem.id_shootlist + '/like');
    },
    getState() {
      return new Promise((resolve, reject) => {
        axios.post('/shootlist/filter-state/get', this.settings).then(res => {
          if(res.data.order_index) {
            this.order_index = res.data.order_index - 1;
          } else {
            this.order_index = 1;
          }
          resolve();
        });
      });
    },
  },
  activated() {
    var update = (JSON.stringify(this.settings) != JSON.stringify(this.$route.params))
      && this.$route.params.num;

    if(update && !this.$route.params.id_shootlist) {
      this.settings = { ...this.$route.params };
    }

    if(!this.settings) {
      this.settings = {
        gender: this.$store.state.user.gender,
        num: 5,
        names: [ 0, 0, 0, 0, 0 ].map((n, i) => {
          return (i + 1) + ' גיימר';
        })
      };
    }

    if(!this.data.length || update || this.$route.params.id_shootlist) {
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
  color: #fff;
  background: $clr-wet;
  padding-bottom: 0 !important;
  .questions {
    padding: 5px 15px;
    .top-text {
      margin: 0;
      font-size: 24px;
    }
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
    }
    .prev {
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
        border: 2px solid #00B4DB;
        color: #00B4DB;
        i {
          opacity: 1;
        }
      }
      i {
        vertical-align: middle;
        opacity: 0.5;
      }
    }
  }
  .small {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    justify-content: space-between;
    padding: 0 15px;
    button {
      padding: 0;
      color: #fff;
      font-size: 16px;
      flex-basis: 120px;
      &.reply {
        i::before {
          transform: scale(-1, 1);
        }
      }
      i {
        margin: 0 5px;
        font-size: 24px;
        vertical-align: middle;
      }
      svg {
        vertical-align: middle;
        margin: 0 5px;
        margin-bottom: 4px;
      }
    }
  }
  .questions-container {
    position: relative;
    height: 170px;
    // .additional {
    //   position: absolute;
    //   left: 0;
    //   top: 0;
    // }
    &.big {
      height: 230px;
    }
    &.middle {
      height: 210px;
    }
  }
}

</style>
