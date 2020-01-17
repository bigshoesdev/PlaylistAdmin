<template>
  <main v-if="prevRoute.name !== 'reg'" class="img-slider-bg-index coupon">
    <div class="popup">
      <div class="welcome">
        <div class="body">
          <div class="title heebo" dir="rtl">
            קבלת קוד הטבה?
          </div>
        </div>
        <div class="footer">
          <input type="text" placeholder="הקלד קוד שקבלת  " v-model="code">
        </div>
        <div class="footer">
          <a @click="applyCode" class="theme-button-primary">
            המשך
          </a>
          <router-link to="/main" class="theme-button-outline border-regular">
            אין לי קוד
          </router-link>
        </div>
      </div>
    </div>
  </main>
  <main v-else>
    <header>
      <div class="wrapper" :class="{ 'sm-cont': !$env.CORDOVA }">
        <sidebar-toggler/>
      </div>
      <img src="img/home-logo.svg" alt="" style="margin-top: 2rem;">
      <p>משחקים מחוץ למסך</p>
      <!--p>
        משחקים חברתיים מחוץ למסך
      </p-->
    </header>
    <!--div class="new-button">
      <button @click="openTutorial">לצפייה בהדגמה</button>
    </div-->
    <div class="body sm-cont">
      <div class="row">
        <router-link to="/playlist" class="full-box yellow">
          <img src="img/main-1.svg" alt="">
          <p>משחקים בכיף</p>
        </router-link>
      </div>
      <div class="row">
        <router-link to="/parent" class="small-box first-btn">
          <img src="img/games-first-btn.svg" alt="">
          <p>מעקב התקדמות</p>
        </router-link>
        <router-link to="/shootlist-options" class="small-box second-btn">
          <img src="img/games-second-btn.svg" alt="">
          <!-- <span>SHOOT list</span> -->
          <p>מדברים בכיף</p>
        </router-link>
        <router-link to="/event/slider" class="small-box third-btn">
          <img src="img/games-third-btn.svg" alt="">
          <p>
            חברים מהשכונה
          </p>
        </router-link>
      </div>
      <router-link to="/workshop" class="blue">
        <img src="img/main-5.svg" alt="">
        <div class="text">
          <p class="upper">
            סדנאות משחקים
          </p>
          <!-- <p class="lower heebo">
            לעבודה
            <i class="icon ion-md-radio-button-on"></i>
            למורים
            <i class="icon ion-md-radio-button-on"></i>
            לתלמידים
          </p> -->
        </div>
      </router-link>
      <router-link to="/funkit" class="pink">
        <img src="img/main-6.svg" alt="">
        <div class="text">
          <p class="upper">
            ערכות משחקים
          </p>
          <!-- <p class="lower heebo">
            למשפחה
            <i class="icon ion-md-radio-button-on"></i>
            להפסקות
            <i class="icon ion-md-radio-button-on"></i>
            למנחים
          </p> -->
        </div>
      </router-link>
    </div>
  </main>
</template>

<script>
import Tutorial from './../components/modals/Tutorial.vue';

import SidebarToggler from './../components/SidebarToggler.vue';

export default {
  data() {
    return {
      prevRoute: null,
      code: null,
    }
  },
  created() {

  },
  methods: {
    openTutorial() {
      this.$modal.show(Tutorial, {}, {
        height: 'auto',
        width: '90%',
      });
    },
    applyCode() {
      if (this.code) {
        axios.post('/playlist/promo-apply', { code: this.code }).then(res => {
          if (res.data.message) {
            console.log(res.data.message);
          }
        });
      }
      this.prevRoute = null;
    }
  },
  components: {
    SidebarToggler
  },
  beforeRouteEnter(to, from, next) {
    next(vm => {
      vm.prevRoute = from;
    })
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  &.coupon {
    display: flex;
    flex-direction: column;
    background-color: $clr-wet;
    background-position: center;
    background-repeat: no-repeat;
    background-size: contain;
    .popup {
      display: flex;
      flex-direction: column;
      margin: 31px 13px 23px;
      flex: 1;
      background-color: $clr-viking;
      border-radius: 10px;
    }
    button {
      margin-left: 23px;
      margin-right: 23px;
      margin-bottom: 33px;
      height: 44px;
      background: #043443;
      border: 1px solid #118188;
    }
    .welcome {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      flex: 1;
    }

    .body {
      margin-top: 93px;
    }

    .title {
      text-align: center;
      font-size: 21px;
      font-weight: bold;
      line-height: 31px;
      color: $clr-white;
    }

    .footer {
      display: flex;
      flex-direction: column;
      margin-bottom: 75px;
      padding-left: 45px;
      padding-right: 45px;
    }

    .footer a {
      height: 42px;
    }

    .footer .theme-button-primary {
      margin-bottom: 18px;
    }

    input {
      padding: 1rem;
      border: 0;
      border-radius: 0.5rem;
      text-align: center;
    }
  }
  background: $clr-wet;
  color: #fff;
  header {
    padding: 0 20px;
    margin-bottom:2rem;
    text-align: center;
    p {
      font-size: 1.8rem; /*22px;*/
      margin: 0;
    }
    .wrapper {
      position: relative;
      top: 1rem;
    }
  }
  .body {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: space-between;
    .row {
      flex-grow: 1;
      display: flex;
      flex-direction: row;
      align-items: stretch;
      justify-content: space-between;
      margin: 5px;
      height: 170px;
      a {
        /*width: 50%;*/
        padding: 10px;
        color: #fff;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        align-items: center;
        font-size: 20px;
        padding-bottom: 20px;
        padding-top: 20px;
        &:nth-child(1) {
          margin-right: 5px;
        }
        &:nth-child(2) {
          margin-left: 5px;
        }
        img {
          width: 130px;
          display: block;
          /*margin: 0 auto;*/
          padding: 0 0.3rem;
        }
        p {
          margin: 0;
          line-height: 100%;
          text-align: center;
        }
        &.yellow {
          background: #FFD600;
          span {
            font-size: 20px;
            font-family: 'Fugaz One', cursive;
          }
        }
        &.purple {
          background: #CC7BF2;
          span {
            font-size: 20px;
            font-family: 'Fugaz One', cursive;
          }
        }
        &.green {
          background: #8CCB05;
        }
        &.orange {
          background: #FF984D;
        }
      }
    }

    .pink, .blue {
      height: 60px;
      border-radius: 10px;
      color: #fff;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      margin: 5px;
      padding: 10px;
      .text {
        direction: rtl;
      }
      .upper {
        font-size: 20px;
        margin: 0;
        line-height: 100%;
      }
      .lower {
        margin-top: 8px;
        font-size: 11px;
        margin: 0;
        line-height: 100%;
        font-weight: 400;
      }
      img {
        height: 100%;
      }
      i {
        margin: 0 2px;
        vertical-align: top;
      }
    }
    .pink {
      background: #F2828F;
      padding-right: 30px;
    }
    .blue {
      padding-right: 30px;
      background: #29AAD2;
    }
  }
  .full-box {
    display: flex;
    flex-direction: row!important;
    width: 100%;
    margin: 0!important;
    img {
      width: 8rem!important;
    }
    p {
      font-size: 1.8rem;
      padding-right: 0.5rem;
    }
  }
  .small-box {
    width: 32%;
    margin: 0!important;
    &.first-btn {
      background-color: #8BCB05;
    }
    &.second-btn {
      background-color: #FF984D;
    }
    &.third-btn {
      background-color: #F2828F;
    }
    img {
      height: 6rem;
    }
  }
  .new-button {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px 0;
    button {
      height: 22px;
      width: 180px;
      background: #29AAD2;
      border-radius: 11px;
      padding: 0;
      line-height: 22px;
      font-size: 14px;
      color: #fff;
    }
  }
}

</style>
