<template lang="html">
  <section class="gift-line" ref="wrapper">
    <div class="bg-wrapper img-curve-line-gift-pt" :style="{ height: red_height + 'px' }"></div>
    <div class="bg-wrapper img-curve-line-gift">
      <div class="wrapper" ref="wrapper">
        <div class="point rofl" :class="{ current: current == 1 }">
          <div class="circle bg-img img-gift-lol"></div>
          <div class="disc">
            המרוץ לצמיד <br>
            התחיל...
            <div class="small">
              מתקדמים בתחנות לפי מספר המשחקים ששחקתם,
              לאחר 40 ימים של משחקים מקבלים במתנה צמיד משחקים!
            </div>
          </div>
        </div>
        <div class="point ten" :class="{ current: current == 2 }">
          <div class="circle">
            <div class="num">
              10
            </div>
            <div class="text">
              משחקים
            </div>
          </div>
          <div class="disc">
            כל הכבוד, זו התחלה טובה!!
          </div>
        </div>
        <div class="point fifteen" :class="{ current: current == 3 }">
          <div class="circle">
            <div class="num">
              15
            </div>
            <div class="text">
              משחקים
            </div>
          </div>
          <div class="disc">
            אתם בדרך הנכונה
          </div>
        </div>
        <div class="point twenty" :class="{ current: current == 4 }">
          <div class="circle">
            <div class="num">
              20
            </div>
            <div class="text">
              משחקים
            </div>
          </div>
          <div class="disc">
            קללל!!!
          </div>
        </div>
        <div class="point twenty-five" :class="{ current: current == 5 }">
          <div class="circle">
            <div class="num">
              25
            </div>
            <div class="text">
              משחקים
            </div>
          </div>
          <div class="disc">
             הפכתם למשחקנים
          </div>
        </div>
        <div class="point thirty" :class="{ current: current == 6 }">
          <div class="circle">
            <div class="num">
              30
            </div>
            <div class="text">
              ימים
            </div>
          </div>
          <div class="disc">
            שיחקתם
            30 ימים,
            <br> קבלתם דרגת משחקן אלוף
            <br> ברשימת המשחקנים!
          </div>
        </div>
        <div class="point lamp">
          <div class="circle bg-img img-gift-lamp"></div>
          <div class="disc">
            <span class="app-bold">קיצור דרך...</span> <br>
            שלחתם רעיון למשחק, <br>
            ושמכם התפרסם עליוו <br>
            התקדמתם ישר לצמיד המשחקים
          </div>
        </div>
        <div class="point thirty-five" :class="{ current: current == 7 }">
          <div class="circle">
            <div class="num">
              35
            </div>
            <div class="text">
              ימים
            </div>
          </div>
          <div class="disc">
            עוד קצת....
          </div>
        </div>
        <div class="point people">
          <div class="circle bg-img img-gift-people"></div>
          <div class="disc">
            הצטרפתם לקהילה,
            <br> ואתם צוברים עוד חברים
          </div>
        </div>
        <div class="point fourty" :class="{ current: current == 8 }">
          <div class="circle">
            <div class="num">
              40
            </div>
            <div class="text">
              ימים
            </div>
          </div>
          <div class="disc">
            בום!!!
            <br> שחקתם
            40 ימים
            <br> צמיד משחקים בדרך אליכם!
          </div>
        </div>
        <div class="point end">
          <img src="img/gift/гавно.png" alt="">
        </div>
      </div>
    </div>
    <div class="end-btn">
      <button
        class="theme-button-outline"
        @click="delivery"
      >לקבלת צמיד המשחקים</button>
    </div>
  </section>
</template>

<script>
import GiftLineError from './modals/GiftLineError.vue';
export default {
  data() {
    return {
      red_height: 0,
    };
  },
  methods: {
    onNewLevel() {
      var doc = document.documentElement;
      var screen = doc.clientHeight;
      var el = this.$refs.wrapper.querySelector('.point.current');
      var offset = (el.getBoundingClientRect().top + (window.pageYOffset || document.documentElement.scrollTop)) - (screen / 2);

      window.scrollSmoothTo(offset, 1000).then(() => {
        this.updateRedLine();
      });
    },
    processScale() {
      var doc = document.documentElement;
      var screen = doc.clientHeight;
      var start_from = this.$refs.wrapper.offsetTop;
      var top = ((window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0)) + (screen / 2);
      top = top - start_from;
      for (let el of document.querySelectorAll('.gift-line .point')) {
        let pos = el.offsetTop + (el.clientHeight / 2);
        if (pos < top + (screen / 2) && pos > top - (screen / 1.5)) {
          if(!el.classList.contains('spawn')) el.classList.add('spawn');
        } else {
          // if(el.classList.contains('spawn')) el.classList.remove('spawn');
        }
      }
    },
    updateRedLine() {
      var el = this.$refs.wrapper.querySelector('.point.current');
      // 76 = "top" style

      if(this.current == 1) {
        this.red_height = 0;
      } else {
        this.red_height = el.offsetTop - (el.clientHeight / 2) + 76;
      }

    },
    delivery() {
      if(this.points >= 30 && (Date.now() / 1000) > this.$store.state.user.date + 3456000) {
        this.$router.push('/delivery');
      } else {
        this.$modal.show(GiftLineError, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        });
      }
    },
  },
  computed: {
    points() {
      return this.$store.state.user.games_summary;
    },
    current() {
      var now = Date.now() / 1000;
      var registered = this.$store.state.user.date;

      if(this.points >= 30 && now >= registered + 3456000) {
        return 8;
      }
      else if(this.points >= 30 && now >= registered + 3024000 && now < registered + 3456000) {
        return 7;
      }
      else if(this.points >= 25 && now >= registered + 2592000 && now < registered + 3024000) {
        return 6;
      }
      else if(this.points >= 25 && now < registered + 2592000) {
        return 5;
      }
      else if(this.points >= 20 && this.points < 25) {
        return 4;
      }
      else if(this.points >= 15 && this.points < 20) {
        return 3;
      }
      else if(this.points >= 10 && this.points < 15) {
        return 2;
      }
      else if(this.points < 10) {
        return 1;
      }
    },
  },
  watch: {
    current(to, from) {
      console.log('changed');
      this.onNewLevel();
    }
  },
  created() {
    // document.addEventListener('scroll', this.processScale);
  },
  destroyed() {
    // document.removeEventListener('scroll', this.processScale);
  },
  mounted() {
    console.log('mounted');
    this.updateRedLine();
  }
}
</script>

<style lang="scss" scoped>

section {
  background: linear-gradient(180deg, #01465B 6.21%, #F26161);
  padding: 80px 0;
  position: relative;
  .wrapper {
    position: relative;
    width: 300px;
    margin: 0 auto;
    height: 1900px;
    // background-image: $curve-line-gift;

    .point {
      position: absolute;
      height: 60px;
      width: 100%;
      left: 0;
      .circle {
        position: relative;
        // transition: transform 0.3s ease, opacity 0.3s ease;
        // opacity: 0;
        // transform: scale(1.2);
      }
      &.current {
        z-index: 1;
        .circle {
          &::before {
            z-index: -1;
            position: absolute;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #D63636;
            left: 50%;
            top: 50%;
            margin-left: -40px;
            margin-top: -40px;
            content: '';
          }
        }
      }
      &.spawn {
        transform: scale(1);
        opacity: 1;
      }
      &.rofl {
        .circle {
          left: 0;
        }
        .disc {
          right: 20px;
          font-size: 26px;
          .small {
            width: 250px;
            position: absolute;
            top: 100%;
            right: 0;
            font-size: 14px;
            line-height: 110%;
          }
        }
        top: -10px;
      }
      &.ten {
        .circle {
          right: 0;
        }
        top: 200px;
      }
      &.fifteen {
        .circle {
          left: 0;
        }
        .disc {
          right: 20px;
        }
        top: 400px;
      }
      &.twenty {
        .circle {
          right: 0;
        }
        top: 530px;
      }
      &.twenty-five {
        .circle {
          left: 0;
        }
        .disc {
          right: 20px;
        }
        top: 700px;
      }
      &.thirty {
        .circle {
          right: 0;
        }
        top: 850px;
      }
      &.lamp {
        .circle {
          left: 0;
        }
        .disc {
          right: -10px;
          top: 15px;
        }
        top: 1000px;
      }
      &.thirty-five {
        .circle {
          right: 0;
        }
        top: 1200px;
      }
      &.people {
        .circle {
          left: 0;
        }
        .disc {
          right: 20px;
        }
        top: 1350px;
      }
      &.fourty {
        .circle {
          right: 0;
        }
        top: 1500px;
      }
      &.end {
        text-align: right;
        top: 1760px;
      }
      &.rofl {
        .circle {
          // background-image: $gift-lol;
        }
      }
      &.lamp {
        .circle {
          margin-top: 10px;
          // background-image: $gift-lamp;
          background-size: 70% auto;
        }
      }
      &.people {
        .circle {
          // background-image: $gift-people;
          background-size: 70% auto;
        }
      }
      .disc {
        width: auto;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        color: #fff;
        direction: rtl;
        line-height: normal;
      }
      .circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #fff;
        color: #BD3C3C;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        position: absolute;
        .num {
          font-size: 38px;
          text-align: center;
          line-height: 100%;
        }
        .text {
          font-size: 12px;
          direction: rtl;
          text-align: center;
          line-height: 100%;
        }
      }
    }
  }
  .bg-wrapper {
    background-position: center top;
    background-repeat: no-repeat;
    background-size: 400px 1830px;
  }
  .img-curve-line-gift-pt {
    position: absolute;
    width: 100%;
    left: 0;
    top: 76px;
    margin: 0 auto;
    height: 1900px;
    background-size: 287px 1830px;
    transition: height 2s ease;
  }
  .end-btn {
    padding-top: 30px;
    text-align: center;
    button {
      width: 300px;
    }
  }
}

</style>
