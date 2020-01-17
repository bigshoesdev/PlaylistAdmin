<template lang="html">
  <main dir="rtl">
    <simple-header inverse>
      <span slot="title">קהילה משחקת</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <section>
      <p class="top-text">
        בחירת ה
        <span class="fugaz">FUN ZONE</span>
        שלך
      </p>
      <div class="search">
        <input type="text" v-model="query" @input="onSearch" placeholder="עיר / ישוב">
        <i class="icon ion-md-search"></i>
      </div>
      <table>
        <tr class="head">
          <td></td>
          <td @click="updateFilter('date_release')">
            תאריך
            <button :class="{ 'desc': filters.date_release == 'DESC' }">
              <i class="icon ion-md-arrow-dropdown"></i>
            </button>
          </td>
          <td>
            מיקום
            <button></button>
          </td>
          <td>
            גיל
            <button></button>
          </td>
          <td>
            משתתפים
          </td>
        </tr>
        <tr
          class="event"
          v-for="item in data"
          :class="{
            full: item.users_count >= item.max_users
          }"
        >
          <td>
            <div class="img bg-img" :style="{ backgroundImage: `url(img/funzone/${item.img}.svg)` }"></div>
          </td>
          <td>{{ new Date(item.date_release * 1000) | dateFormat('DD.MM.YY') }}</td>
          <td>{{ item.geo }}</td>
          <td>{{ item.age }}</td>
          <td class="last">
            <span>{{ item.users_count }}/{{ item.max_users }}</span>
            <button v-if="Date.now() >= item.date_release * 1000" class="join" @click="$router.push('/funzone/' + item.id_event + '/conclusion')">
              פרטים
            </button>
            <button v-else-if="item.users_count >= item.max_users" class="join" @click="fulled(item)">
              פרטים
            </button>
            <button v-else class="join" @click="$router.push('/event/' + item.id_event)">
              פרטים
            </button>
          </td>
        </tr>
      </table>
      <div class="devider heebo">
        אירועים  שעברו
        <button v-if="!ended" @click="onMore" class="more">
          <i class="icon ion-md-add"></i>
          להראות יותר
        </button>
      </div>
      <table class="passed">
        <tr
          class="event"
          v-for="item in passed"
        >
          <td>
            <div class="img bg-img" :style="{ backgroundImage: `url(img/funzone/${item.img}.svg)` }"></div>
          </td>
          <td>{{ new Date(item.date_release * 1000) | dateFormat('DD.MM.YY') }}</td>
          <td>{{ item.geo }}</td>
          <td>{{ item.age }}</td>
          <td class="last">
            <span>{{ item.users_count }}/{{ item.max_users }}</span>
            <button v-if="item.is_registered" class="join registered" @click="$router.push('/event/' + item.id_event + '/join')">
              פרטים
            </button>
            <button v-else class="join" @click="$router.push('/funzone/' + item.id_event + '/conclusion')">
              פרטים
            </button>
          </td>
        </tr>
      </table>
    </section>
    <router-link class="create-event" to="/event/create">
      <span class="top">צור</span>
      <span class="eng">FUN ZONE</span>
      <i class="icon ion-md-add"></i>
    </router-link>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import EventFull from './../components/modals/EventFull.vue';

export default {
  data() {
    return {
      data: [],
      ended: false,
      loading: false,
      page: 0,
      passed: [],
      filters: {
        date_release: 'ASC',
      },
      query: '',
    };
  },
  methods: {
    onSearch() {
      this.page = 0;
      this.sync(true);
    },
    sync(update = false) {
      this.loading = true;
      axios.post('/event/select', {
        page: this.page,
        filters: this.filters,
        query: this.query,
      }).then(res => {
        if(update) {
          this.data = res.data.events;
        } else {
          this.data.push(...res.data.events);
        }
        this.ended = res.data.ended;
        this.$nextTick(() => {
          this.loading = false;
        })
      });
    },
    updateFilter(filter) {
      if(this.filters[filter] == 'DESC') {
        this.filters[filter] = 'ASC';
      } else {
        this.filters[filter] = 'DESC';
      }
      this.page = 0;
      this.sync(true);
    },
    fulled(item) {
      if(item.is_registered) {
        this.$router.push('/event/' + item.id_event)
      } else {
        this.$modal.show(EventFull, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        });
      }
    },
    onMore(e) {
      // var doc = document.documentElement;
      // var screen = doc.clientHeight;
      // var top = ((window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0));
      // if(top >= (doc.scrollHeight - screen) && !this.loading && !this.ended) {
      //   this.page++;
      //   this.sync();
      // }
      if(!this.loading) {
        this.page++;
        this.sync();
      }
    },
  },
  computed: {

  },
  components: {
    SimpleHeader,
    SidebarToggler
  },
  created() {
    this.sync();
    axios.get('/event/past').then(res => {
      this.passed = res.data;
    });
    // document.addEventListener('scroll', this.onScroll);
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  color: $clr-wet;
  padding-bottom: 110px;
  .top-text {
    font-size: 15px;
    padding: 0 20px;
    span {
      font-family: 'Fugaz One';
    }
  }
  section {
    padding-bottom: 100px;
  }
  table {
    width: 100%;
    border-spacing: 0;
    .head {
      td {
        border-bottom: 1px solid #fff;
        font-size: 13px;
      }
      td:first-child {
        padding-right: 5px;
      }
      button {
        color: #fff;
        transition: transform 0.1s ease;
        &.desc {
          transform: rotate(180deg);
        }
      }
    }
    .event {
      font-size: 16px;
      .img {
        width: 50px;
        height: 50px;
        background-color: #fff;
        background-size: 60%;
        border-radius: 50%;
        margin: 5px 0;
      }
      td {
        line-height: 18px;
        padding: 5px;
        button {
          background: $clr-wet;
          padding: 0 10px;
          float: left;
          height: 20px;
          border-radius: 15px;
          line-height: 20px;
          color: #fff;
          font-size: 12px;
          display: inline-block;
          &.registered {
            border: 1px solid $clr-wet;
          }
        }
      }
      td:first-child {
        padding-right: 20px;
      }
      td:last-child {
        padding-left: 5px;
      }
      @media(max-width: 400px) {
        font-size: 15px;
        .img {
          width: 30px;
          height: 30px;
        }
        td:first-child {
          padding-right: 5px;
        }
      }
      @media(max-width: 370px) {
        font-size: 13px;
      }
      &.full {
        color: #E41E1E;
        button {
          background: #E41E1E;
        }
      }
    }
    &.passed {
      color: #CEE7EF;
      td button {
        background: #CEE7EF;
      }
      .last {
        span {
          color: $clr-wet;
        }
      }
    }
  }
  .devider {
    padding: 8px 25px;
    background-color: $clr-wet;
    font-size: 15px;
    color: #fff;
    position: relative;
    .more {
      position: absolute;
      border: 1px solid #fff;
      background: transparent;
      height: 24px;
      font-size: 13px;
      line-height: 22px;
      border-radius: 12px;
      left: 20px;
      top: 50%;
      margin-top: -12px;
      color: #fff;
      padding: 0 10px;
    }
  }
  .create-event {
    position: fixed;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    border: 3px solid #fff;
    color: #fff;
    background: #3CD92E;
    box-shadow: 6px 7px 6px rgba(0, 0, 0, 0.25);
    bottom: 20px;
    left: 20px;
    line-height: 120%;
    .top {

    }
    .eng {
      font-size: 14px;
      font-family: 'Fugaz One';
    }
  }
  .search {
    width: 250px;
    height: 40px;
    border-radius: 20px;
    overflow: hidden;
    margin: 20px auto;
    background: #fff;
    position: relative;
    border: 2px solid $clr-wet;
    input {
      width: 100%;
      height: 100%;
      border: 0;
      background: transparent;
      padding: 0 20px;
      padding-left: 40px;
      &::placeholder {
        text-align: center;
        color: $clr-wet;
        opacity: 1;
      }
    }
    i {
      position: absolute;
      left: 20px;
      font-size: 18px;
      height: 100%;
      line-height: 40px;
      color: $clr-wet;
      top: 0;
    }
  }
}

</style>
