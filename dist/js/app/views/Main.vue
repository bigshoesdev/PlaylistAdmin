<template lang="html">
  <main>
    <header>
      <img src="img/white-logo.svg" alt="">
      <p>
        משחקים חברתיים מחוץ למסך
      </p>
    </header>
    <div class="new-button">
      <button @click="openTutorial">לצפייה בהדגמה</button>
    </div>
    <div class="body sm-cont">
      <div class="row">
        <router-link to="/playlist/sections" class="yellow">
          <img src="img/main-1.svg" alt="">
          <!-- <span>PLAY list</span> -->
          <p>
            משחקי חברה
          </p>
        </router-link>
        <router-link to="/shootlist-options" class="purple">
          <img src="img/main-2.svg" alt="">
          <!-- <span>SHOOT list</span> -->
          <p>
            שאלות לשיחה
          </p>
        </router-link>
      </div>
      <div class="row">
        <router-link to="/parent" class="green">
          <img src="img/main-3.svg" alt="">
          <p>
            כניסת הורים
          </p>
        </router-link>
        <router-link to="/event/slider" class="orange">
          <img src="img/main-4.svg" alt="">
          <p>
            הקהילה שלי
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
export default {
  created() {

  },
  methods: {
    openTutorial() {
      this.$modal.show(Tutorial, {}, {
        height: 'auto',
        width: '320',
      });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  color: #fff;
  header {
    padding: 0 20px;
    text-align: center;
    p {
      font-size: 22px;
      margin: 0;
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
        width: 50%;
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
          max-width: 130px;
          display: block;
          margin: 0 auto;
        }
        p {
          margin: 0;
          line-height: 100%;
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
