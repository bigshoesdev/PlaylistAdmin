<template lang="html">
  <main>
    <simple-header inverse class="header">
      <span slot="title"><span class="fugaz">FUN ZONE</span> הסבר על</span>
    </simple-header>
    <div class="slider-wrapper">
      <carousel
        v-model="slide"
        paginationActiveColor="#005D7A"
        class="slider"
        :perPage="1"
        :rtl="true"
        autoplayDirection="backward"
      >
        <slide class="wrapper">
          <div class="slide" dir="rtl">
            <h2>
              שלב שלישי  -מדרגים
            </h2>
            <img src="img/event-slider-4.svg" alt="">
            <p>
              1. מקריאים את שמות המשחקים ששוחקו <br>
              2. לפי מחיאות הכפיים נקבע המשחק <br>
              המוביל - שיפתח את המפגש הבא <br>
              3. לאחר סיום ה <span class="fugaz">FUN ZONE</span> לוחצים עליו שוב כדי למלא דף סיכום
            </p>
          </div>
        </slide>
        <slide class="wrapper">
          <div class="slide" dir="rtl">
            <span class="absolute">*5 דקות לכל משחק</span>
            <h2>
              שלב שני - משחקים
            </h2>
            <img src="img/event-slider-3.svg" alt="">
            <p>
              1. 15 דקות - ישיבה במעגל עם שאלות <span class="fugaz">Shootlist</span> <br>
              2. 45 דקות - משחקי <span class="fugaz">Playlist</span> <span class="yellow">*</span> <br>
              3. הראשון שהגיע בוחר את המשחק הראשון, מי שהגיע שני בוחר את המשחק השני, וכן הלאה.
            </p>
          </div>
        </slide>
        <slide class="wrapper">
          <div class="slide" dir="rtl">
            <h2>
              שלב ראשון - נפגשים
            </h2>
            <img src="img/event-slider-2.svg" alt="">
            <p>
              1. יוזם ה <span class="fugaz">FUN ZONE</span>, הכובען, מגיע עם כובע <br>
              2. כשמגיע החבר הבא - הכובע עובר אליו <br>
              3.משתתף חדש מזהה את הקבוצה לפי
              הכובע, אומר את הסיסמה, ומצטרף
            </p>
          </div>
        </slide>
        <slide class="wrapper">
          <div class="slide" dir="rtl">
            <h2>
              קהילה משחקת
            </h2>
            <img src="img/event-slider-1.svg" alt="">
            <p>
              <span class="fugaz">Playlist</span> מאפשרת לכם להצטרף לקהילה
              משחקת באזורכם באירוע <span class="fugaz">FUN ZONE</span>
              ולמצוא שותפים למשחקים מחוץ למסך
            </p>
          </div>
        </slide>
      </carousel>
    </div>
    <div class="off-slide-wrapper">
      <div dir="rtl" class="off-slide" v-if="slide == 0">
        <label>
          <input class="theme-checkbox" type="checkbox" v-model="slider_on">
          אל תציג לי שוב
        </label>
        <button class="theme-button-outline" @click="goNext">אז יאללה!</button>
      </div>
    </div>

  </main>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      slide: 0,
      slider_on: false,
    };
  },
  methods: {
    goNext() {
      localStorage.setItem('event-slider', this.slider_on);
      this.$router.push('/events')
    }
  },
  components: {
    Carousel,
    Slide,
    SimpleHeader,
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  width: 100%;
  background: $clr-blue;
  // display: flex;
  // flex-direction: column;
  // align-items: center;
  // justify-content: center;
  .off-slide-wrapper {

  }
  .off-slide {
    bottom: 20px;
    width: 150px;
    margin: 0 auto;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px 0;
    label {
      font-size: 15px;
      line-height: 100%;
      input {
        margin-left: 10px;
        vertical-align: middle;
      }
    }
    button {
      margin-top: 10px;
    }
  }
  .slider {
    text-align: center;
    .wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .slide {
      border-radius: 10px;
      width: 300px;
      height: auto;
      color: #fff;
      position: relative;
      p {
        padding: 0 20px;
        margin: 0;
        padding-bottom: 40px;
        text-align: right;
        line-height: 135%;
        font-size: 16px;
      }
      img {
        width: 280px;
      }
    }
    .wrapper:nth-child(4) .slide {
      background: #F3D74A;
      padding-top: 10px;
      img {
        margin-bottom: 20px;
      }
    }
    .wrapper:nth-child(3) .slide {
      background: #FF7384;
    }
    .wrapper:nth-child(2) .slide {
      background: $clr-wet;
      .yellow {
        color: #FFE352;
      }
      .absolute {
        display: block;
        position: absolute;
        left: 10px;
        bottom: 10px;
        color: #FFE352;
        font-size: 12px;
      }
    }
    .wrapper:nth-child(1) .slide {
      background: #3CD92E;
    }
  }
  .slider-wrapper {
    // flex-grow: 1;
    // display: flex;
    // align-items: stretch;
    // justify-content: center;
  }
}

</style>
