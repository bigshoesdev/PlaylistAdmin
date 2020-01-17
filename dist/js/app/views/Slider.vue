<template lang="html">
  <main class="img-slider-bg-index">
    <carousel
      paginationActiveColor="#005D7A"
      class="slider"
      :rtl="true"
      :perPage="1"
      autoplayDirection="backward"
    >
      <slide class="wrapper">
        <div class="slide">
          <img src="img/index-slider-4.svg" alt="">
          <h2>
            קהילה משחקת
          </h2>
          <p>
            מוצאים ב
            <span class="fugaz">Playlist</span>
            שותפים למשחקים
            מחוץ למסך, ומצטרפים לארועי
            משחקים שכונתיים באזורך
            <button @click="$router.push('/reg')" class="theme-button-outline">כניסה / הרשמה</button>
          </p>
        </div>
      </slide>
      <slide class="wrapper">
        <div class="slide">
          <img src="img/index-slider-3.svg" alt="">
          <h2>
            משפחה משחקת
          </h2>
          <p>
            ארוחה משפחתית בכיף עם
            משחקים שמחברים את כולם.
            וכן שאלות יצירתיות לשיחה זורחמת
          </p>
        </div>
      </slide>
      <slide class="wrapper">
        <div class="slide">
          <img src="img/index-slider-2.svg" alt="">
          <h2>
            מתנות לילדים
          </h2>
          <p>
            מגדירים מתנות מהמשחקים,
            כמו: בטחון עצמי, קשב, זרימה, חיוביות,
            ומקבלים משחקים שמעניקים מיומנות אלו
          </p>
        </div>
      </slide>
      <slide class="wrapper">
        <div class="slide">
          <img src="img/index-slider-1.svg" alt="">
          <h2>
            משחקים מחוץ למסך
          </h2>
          <p>
            נפגשים עם חברים, מדפדפים בין מאות
            הרעיונות למשחקים יצירתיים ופשוטים,
            ומשחקים בכיף ללא ציוד וללא מפסידים
          </p>
        </div>
      </slide>
    </carousel>
  </main>
</template>

<script>
import { Carousel, Slide } from 'vue-carousel';
export default {
  components: {
    Carousel, Slide
  },
  created() {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  width: 100%;
  background-color: $clr-wet;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: center;
  background-position: center;
  background-repeat: no-repeat;
  background-size: contain;
  .slider {
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
      text-align: center;
      h2 {
        margin-bottom: 0;
      }
      p {
        font-size: 18px;
        line-height: 135%;
        padding: 0 30px;
        direction: rtl;
      }
    }
    .wrapper:nth-child(4) .slide {
      background: #29AAD2;
      padding: 40px 0;
    }
    .wrapper:nth-child(3) .slide {
      padding: 35px 0;
      background: #0BEF59;
    }
    .wrapper:nth-child(2) .slide {
      padding: 40px 0;
      background: #FBE15D;
    }
    .wrapper:nth-child(1) .slide {
      padding: 30px 0;
      background: #FF7384;
    }
    .theme-button-outline {
      margin-top: 20px;
    }
  }
}

</style>
