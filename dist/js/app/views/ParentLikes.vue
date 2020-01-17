<template lang="html">
  <main dir="rtl">
    <simple-header>
      <span slot="title">דו"ח מעקב</span>
      <span slot="additional">
        <sidebar-toggler/>
      </span>
    </simple-header>
    <p class="text-new">
      כהורים תוכלו לצפות כאן אלו משחקים שיחקו ילדיכם, ואלו מתנות לחיזוק האופי הם אספו באמצעות המשחקים
    </p>
    <div class="">
      <table>
        <tr class="header">
          <td>
            צפייה בין תאריכים
          </td>
          <td>
            מ-
            <input v-model="from" @change="sync" type="date">
          </td>
          <td>
            עד-
            <input v-model="to" @change="sync" type="date">
          </td>
        </tr>
        <tr class="sub-header">
          <td>משחקים</td>
          <td>מתנות</td>
          <td>אהבתי</td>
        </tr>
        <tr v-for="item in data" class="content">
          <td class="main">
            <p @click="$router.push({ name: 'playlist', params: { id_playlist: item.id_playlist } })">
              {{ item.name }}
            </p>
            <small>
              <span class="date">{{ new Date(item.date * 1000) | dateFormat('DD.MM.YYYY') }}</span>
              <span class="time">{{ new Date(item.date * 1000) | dateFormat('HH:mm') }}</span>
            </small>
          </td>
          <td class="category">
            <span v-for="(category, i) in item.categories">{{ i != 0 ? ',' : '' }} {{ category.name }}</span>
          </td>
          <td class="like-wr">
            <button
              class="like"
              :class="{ liked: item.liked }"
              @click="like(item)"
            >
              <i class="icon ion-md-heart"></i>
            </button>
          </td>
        </tr>
      </table>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
export default {
  data() {

    var date = new Date();
    var timestamp = date.getTime() / 1000; // Num of sec to substract a week from it
    var week_ago = new Date((timestamp - (2.62 * Math.pow(10, 6))) * 1000);

    return {
      data: [],
      from: this.$options.filters.dateFormat(week_ago, 'YYYY-MM-DD'),
      to: this.$options.filters.dateFormat(date, 'YYYY-MM-DD'),
    };
  },
  methods: {
    sync() {
      axios.post('/parent/get-activity', {
        from: this.from,
        to: this.to,
      }).then(res => {
        this.data = res.data;
      });
    },
    like(item) {
      axios.put('/playlist/' + item.id_playlist + '/like');
      item.liked = !item.liked;
    }
  },
  created() {
    this.sync();
  },
  components: {
    SimpleHeader,
    SidebarToggler,
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  color: #fff;
  table {
    width: 100%;
    border-spacing: 0;
  }
  .header {
    font-size: 15px;
    td {
      padding-bottom: 10px;
      &:last-child {
        text-align: center;
      }
    }
  }
  .sub-header {
    background: #fff;
    color: $clr-wet;
    font-size: 15px;
    td {
      padding: 10px;
      &:last-child {
        text-align: center;
      }
    }
  }
  .like-wr {
    text-align: center;
    .like {
      color: #fff;
      &.liked {
        color: #FF7384;
      }
    }
  }

  .content {
    td {
      border-top: 2px solid $clr-blue;
      min-width: 120px;
    }
    .category {
      font-size: 13px;
      p {
        margin: 0;
      }
    }
    .main {
      p {
        margin: 0;
        font-size: 18px;
        text-decoration: underline;
      }
      .time {
        margin-right: 10px;
      }
    }
  }
  input[type="date"] {
    background: transparent;
    border: 0;
    text-decoration: underline;
    -webkit-appearance: none;
    padding: 0;
    color: #24A0C6;
  }
  .text-new {
    padding: 0 15px;
  }
}

</style>
