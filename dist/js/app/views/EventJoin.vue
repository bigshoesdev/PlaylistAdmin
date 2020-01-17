<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title"><span class="fugaz">FUN ZONE</span> פרטי ה</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <div class="head">
      <div class="info">
        <div class="col date">
          <span class="value">תאריך</span>
          {{ new Date(data.date_release * 1000) | dateFormat('DD.MM.YY') }}
        </div>
        <div class="col">
          <span class="value">שעה</span>
          {{ new Date(data.date_release * 1000) | dateFormat('HH:mm') }}
        </div>
        <div class="col people">
          <span class="value">גילאים</span>
          {{ data.age }}
        </div>
      </div>
      <div class="img bg-img" :style="{ backgroundImage: `url(img/funzone/${data.img}.svg)` }"></div>
    </div>
    <div class="titles">
      <p class="main">{{ data.geo }}</p>
      <p class="sec">{{ data.geo_full }}</p>
    </div>
    <table class="table" dir="rtl">
      <tr>
        <td>שם לאיש קשר</td>
        <td class="value">{{ data.name }}</td>
      </tr>
      <tr>
        <td>מספר טלפון לאיש קשר</td>
        <td class="value">{{ data.phone }}</td>
      </tr>
      <tr>
        <td>הסיסמה להצטרפות בשטח:</td>
        <td class="value">{{ data.pass }}</td>
      </tr>
    </table>
    <h3 dir="rtl"> סיכום ה <span class="fugaz">FUN ZONE</span></h3>
    <form class="inputs" @submit="join" dir="rtl">
      <div class="section">
        <p>
          מועד ה
          <span class="fugaz">FUN ZONE</span>
          הבא
         </p>
        <input v-model="next" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>המשחק הנבחר</p>
        <input v-model="second" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>
          מה לשפר?
        </p>
        <input v-model="third" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>
          מה היה מוצלח?
        </p>
        <input v-model="fourth" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>
          דירוג האירוע
          <span class="small">
            (דירוג האירוע)
          </span>
        </p>
        <rating v-model="rating" :max="5"/>
      </div>
      <div class="section">
        <p>
          נפתחה קבוצת ווטסאפ?
        </p>
        <switcher v-model="whatsapp" :values="[
          { name: 'כן', value: 'כן' },
          { name: 'עדין לא', value: 'עדין לא' },
        ]"/>
      </div>
      <button class="end theme-button-outline">שלח</button>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import Switcher from './../components/Switcher.vue';
import EventJoined from './../components/modals/EventJoined.vue';
import Rating from './../components/Rating.vue';
export default {
  data() {
    return {
      data: {},
      next: '',
      second: '',
      third: '',
      fourth: '',
      rating: 3,
      whatsapp: 'כן',
    };
  },
  methods: {
    sync() {
      axios.get('/event/' + this.$route.params.id_event).then(res => {
        this.data = res.data;
      });
    },
    join(e) {
      e.preventDefault();
      axios.post('/event/' + this.$route.params.id_event + '/conclusion', {
        next: this.next,
        second: this.second,
        third: this.third,
        fourth: this.fourth,
        rating: this.rating,
        whatsapp: this.whatsapp,
      }).then(res => {
        this.$router.replace('/funzone/' + this.$route.params.id_event + '/conclusion');
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    }
  },
  components: {
    SimpleHeader,
    SidebarToggler,
    Switcher,
    Rating,
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .head {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    padding: 0 20px;
    .info {
      flex-grow: 1;
      padding-right: 20px;
      font-size: 22px;
      display: flex;
      flex-direction: row;
      justify-content: space-between;

      .col {
        text-align: center;
        flex-grow: 1;
        direction: rtl;
        padding: 0 10px;
        &.date, &.people {
          text-align: right;
        }
      }
      .value {
        display: block;
        color: #fff;
      }
    }
    .img {
      border: 2px solid #005D7A;
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background-color: #fff;
      background-size: 60% auto;
    }
    @media(max-width: 380px) {
      .info {
        font-size: 18px;
      }
      .img  {
        width: 40px;
        height: 40px;
      }
    }
  }
  .titles {
    direction: rtl;
    padding: 0 20px;
    padding-top: 20px;
    color: #fff;
    .main {
      margin: 0;
      font-size: 22px;
    }
    .sec {
      margin: 0;
      font-size: 15px;
    }
  }
  .table {
    padding: 20px;
    width: 100%;
    font-size: 15px;
    .value {
      color: #fff;
    }
  }
  h3 {
    font-size: 17px;
    margin: 0;
    padding: 0 20px;
  }
  .inputs {
    padding: 20px;
    input {
      width: 100%;
    }
    p {
      margin: 0;
      margin-top: 20px;
      span {

      }
      .small {
        font-size: 14px;
      }
    }
    .end {
      display: block;
      width: 100%;
      margin: 20px auto;
    }
  }
}

</style>
