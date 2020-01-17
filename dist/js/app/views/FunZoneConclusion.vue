<template lang="html">
  <main dir="rtl">
    <simple-header inverse>
      <span slot="title"><span class="fugaz">FUN ZONE</span> סיכום ה</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <div class="plate-wrapper">
      <div class="plate img-conc-bg-plate">
        <span>
          תודה שהגעת ל <br>
          <span class="fugaz">FUN ZONE</span> <br>
          תודה על הסיכום
        </span>
      </div>
    </div>
    <div class="head">
      <div class="img bg-img" :style="{ backgroundImage: `url(img/funzone/${data.img}.svg)` }"></div>
      <div class="info">
        <p>{{ data.geo }}</p>
        <small>{{ data.geo_full }}</small>
      </div>
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
    </table>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import Switcher from './../components/Switcher.vue';
import EventJoined from './../components/modals/EventJoined.vue';
import Rating from './../components/Rating.vue';
export default {
  methods: {
    sync() {
      axios.get('/event/' + this.$route.params.id_event).then(res => {
        this.data = res.data;
      });
    },
    join(e) {
      e.preventDefault();
      axios.put('/event/' + this.$route.params.id_event + '/join').then(res => {
        this.$router.push('/events');
        this.$modal.show(EventJoined, {}, {
          adaptive: true,
          height: 'auto',
        })
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    }
  },
  data() {
    return {
      data: {},
      group: 'כן',
      rating: 3,
    };
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
  .plate {
    min-width: 300px;
    max-width: 400px;
    margin: 0 auto;
    background-color: #fff;
    height: 230px;
    border-radius: 20px;
    background-position: center;
    background-repeat: no-repeat;
    background-size: 95% auto;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    &-wrapper {
      padding: 10px;
      padding-bottom: 80px;
    }
  }
  .head {
    padding: 0 15px;
    padding-bottom: 30px;
    display: flex;
    flex-direction: row;
    align-items: stretch;
    justify-content: space-between;
    .info {
      color: #fff;
      flex-grow: 1;
      padding-right: 20px;
      p {
        margin: 0;
        font-size: 22px;
      }
      small {
        font-size: 15px;
      }
    }
    .img {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      background-color: #fff;
      background-size: 60% auto;
      border: 2px solid #005D7A;
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
}

</style>
