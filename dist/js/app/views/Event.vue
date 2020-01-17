<template lang="html">
  <main dir="rtl">
    <simple-header inverse>
      <span slot="title"><span class="fugaz">FUN ZONE</span> פרטי ה</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <section v-if="loaded">
      <div class="head">
        <div class="img bg-img" :style="{ backgroundImage: `url(img/funzone/${data.img}.svg)` }"></div>
        <div class="info">
          <p>{{ data.geo }}</p>
          <small>{{ data.geo_full }}</small>
        </div>
      </div>
      <div class="stripe-info">
        <div class="row param">
          <div class="col">
            נרשמו
          </div>
          <div class="col">
            תאריך
          </div>
          <div class="col">
            שעה
          </div>
          <div class="col">
            גילאים
          </div>
        </div>
        <div class="row value">
          <div class="col">
            {{ data.users_count }}/{{ data.max_users }}
          </div>
          <div class="col">
            {{ new Date(data.date_release * 1000) | dateFormat('DD.MM.YY') }}
          </div>
          <div class="col">
            {{ new Date(data.date_release * 1000) | dateFormat('HH:mm') }}
          </div>
          <div class="col">
            {{ data.age }}
          </div>
        </div>
      </div>
      <div class="private-info">
        <div class="row param">
          <div class="col">
            מספר איש קשר
          </div>
          <div class="col">
            איש קשר
          </div>
        </div>
        <div class="row value">
          <div class="col">
            {{ data.phone }}
          </div>
          <div class="col">
            {{ data.name }}
          </div>
        </div>
      </div>
      <div class="chat">
        <div class="message">
          <img src="img/event-single-3.svg" alt="">
          <template v-if="!data.comment.length">אפשר להביא חטיפים</template>
          <template v-else>{{ data.comment }}</template>
        </div>
        <div class="message">
          <img src="img/event-single-2.svg" alt="">
          הסיסמה להצטרפות בשטח:
          <span>{{ data.pass }}</span>
        </div>
        <div class="message">
          <img src="img/event-single-1.svg" alt="">
          מוזמנים לבוא כי יהיה:
          <span>{{ categories }}</span>
        </div>
      </div>
      <template v-if="!data.is_in">
        <button
          v-if="Date.now() < data.date_release * 1000"
          @click="join"
          class="end theme-button-outline"
        >אני בפנים</button>
      </template>
      <template v-else>
        <button @click="cancel" class="end cancel theme-button-outline">אני מבטל</button>
      </template>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import CancelEvent from './../components/modals/CancelEvent.vue';
import EventJoined from './../components/modals/EventJoined.vue';

export default {
  methods: {
    sync() {
      axios.get('/event/' + this.$route.params.id_event).then(res => {
        this.data = res.data;
        this.loaded = true;
      });
    },
    cancel() {
      this.$modal.show(CancelEvent, {
        event: this.data
      }, {
        adaptive: true,
        height: 'auto',
        width: 300,
      });
    },
    join() {
      axios.put('/event/' + this.$route.params.id_event + '/join').then(res => {
        // this.$router.replace('/funzone/' + this.$route.params.id_event + '/conclusion');
        this.data.is_in = true;
        this.$modal.show(EventJoined, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        })
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    }
  },
  data() {
    return {
      data: {},
      loaded: false,
    };
  },
  computed: {
    categories() {
      return this.data.categories.map(n => {
        return n.name;
      }).join(', ');
    }
  },
  components: {
    SimpleHeader,
    SidebarToggler
  },
  created() {
    axios.get('/activity/' + this.$route.params.id_event + '/funzone-see');
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  section {
    padding-bottom: 20px;
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
    .stripe-info {
      padding-bottom: 30px;
      .row {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        font-size: 22px;
        .col {
          flex-grow: 1;
          text-align: center;
        }
        &.value {
          color: #fff;
        }
      }
    }
    .private-info {
      padding-bottom: 30px;
      .row {
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        font-size: 15px;
        .col {
          width: 150px;
          text-align: center;
        }
        &.value {
          color: #fff;
        }
      }
    }
    .chat {
      width: 300px;
      margin: 0 auto;
      padding-bottom: 30px;
      .message {
        background: #fff;
        width: 150px;
        padding: 10px;
        margin: 20px auto;
        position: relative;
        border-radius: 5px;
        font-size: 15px;
        span {
          font-weight: bold;
        }
        img {
          position: absolute;
          top: 0;
          bottom: 0;
          margin: auto 0;
        }
        &:nth-child(1) {
          img {
            left: 100%;
          }
        }
        &:nth-child(2) {
          img {
            right: 100%;
          }
        }
        &:nth-child(3) {
          img {
            left: 100%;
          }
        }
      }
    }
  }
  .end {
    display: block;
    margin: 0 auto;
    &.cancel {
      background: #1A92B8;
      border: 0;
    }
  }
}

</style>
