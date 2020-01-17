<template lang="html">
  <main dir="rtl">
    <simple-header route="/main" :inverse="true">
      <span slot="title">
        ערכות משחקים
      </span>
    </simple-header>
    <div class="top-desc">
      <h4>
        על פי מחקרים הילדים כיום נמצאים מול מסכים כ-6 שעות ביום!
      </h4>
      <p>
        המומחים מתריעים על איבוד יכולות חברתיות, דרדור הקשב של הילד, וטשטוש היכולת שלו לחוש את האחר. זאת לצד דיכאון, השמנה וכהות מנטלית. <br> <br>
        הפתרון הוא להנגיש לילדים אלטרנטיבה, כך שיוכלו לשחק עם חברים/אחים בכיף גם מחוץ למסך. <span class="fugaz">Playlist</span> נותנת מענה זה על ידי מגוון עצום של רעיונות למשחקים חברתיים. המשחקים יצירתיים ופשוטים ואינם דורשים ציוד. <br> <br>
        המענה ניתן גם בערכות משחקים פיזיות הנגישות בשטח. <br> <br>
      </p>
    </div>
    <div class="cards-desc">
      <div class="text">
        <p>
          ערכה 35 ש"ח
        </p>
        <p>
          מתנה! מנוי בסיסי חינם
          לרוכשים ערכה
        </p>
        <p>
          משלוח 17 ש"ח
        </p>
      </div>
      <img src="img/funkit-cards.png" alt="You are gay">
    </div>
    <div class="h-line">
      <h3>
        הערכות
      </h3>
    </div>
    <div class="cards">
      <div v-for="item in data" class="item" :style="{ backgroundColor: item.color }">
        <h4>{{ item.name }}</h4>
        <button @click="$router.push('/funkit/' + item.id_funkit)" class="theme-button-outline">
          הזמנה
        </button>
        <div class="img">
          <img :src="item.img" alt="">
        </div>
        <div class="advantages" v-html="item.text"></div>
        <!-- <div class="delivery-disclaimer">
          עלות משלוח  17 ש"ח
        </div> -->
      </div>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      data: [],
    };
  },
  components: {
    SimpleHeader
  },
  created() {
     axios.get('/funkit/select').then(res => {
       res.data = res.data.map(n => {
         if(n.text) {
           n.text = n.text.replace(/\n/g, '<br>');
         }

         return n;
       });
       this.data = res.data;
     });
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .top-desc {
    font-size: 15px;
    padding: 0 20px;
  }
  .cards-desc {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    font-size: 19px;
    padding-left: 5px;
    background: #45A2BE;
    .text {
      p, h3 {
        margin: 5px 0;
        color: #fff;
      }
    }
  }
  .h-line {
    height: 80px;
    position: relative;
    h3 {
      font-weight: normal;
      font-size: 24px;
      padding: 10px;
      margin: 0;
      position: absolute;
      z-index: 1;
      background: $clr-blue;
      left: 50%;
      top: 50%;
      transform: translate(-50%, -50%);
      color: #fff;
    }
    &::before {
      height: 2px;
      content: '';
      width: 100%;
      position: absolute;
      left: 0;
      top: 50%;
      margin-top: -1px;
      background: #fff;
    }
  }
  .cards {
    .item {
      padding: 10px 20px;
      padding-bottom: 30px;
      margin-top: 20px;
      position: relative;
      text-align: right;
      &:first-child {
        margin-top: 0px;
      }
      h4 {
        margin: 0;
        font-size: 20px;
        padding-bottom: 10px;
        padding-right: 120px;
        text-align: right;
      }
      .img {
        position: absolute;
        top: 10px;
        right: 20px;
        width: 100px;
        height: 100px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.5);
        line-height: 100px;
        text-align: center;
        img {
          vertical-align: middle;
          max-height: 80px;
          max-width: 80px;
        }
      }
      button {
        margin-right: 120px;
      }
      .advantages {
        padding-top: 40px;
        font-size: 15px;
        text-align: right;
      }
      .delivery-disclaimer {
        position: absolute;
        left: 10px;
        bottom: 0;
        font-size: 15px;
      }
    }
  }
}

</style>
