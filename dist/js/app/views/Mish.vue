<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title"><span class="fugaz">Playlist</span> המשחקנים של</span>
    </simple-header>
    <p class="sub-title">מתפרסמים כאן עם התואר משחקן <span class="fugaz">Playlist</span> אחרי שמשחקים לפחות 25 משחקים</p>
    <p class="sub-sub-title">ניתן להפוך לאלוף <span class="fugaz">Playlist</span> אם מתקבלת הצעתכם למשחק חדש או לשיפור משחק, או אם אתם משחקים במשך 30 ימים במשחקי <span class="fugaz">Playlist</span></p>
    <div class="top-cnt">
      <button @click="$router.push('/schools')" class="schools"> בתי הספר המובילים </button>
      <div class="search">
        <input type="text" v-model="query" @input="sync(true)" placeholder="שם המשתמש">
        <i class="icon ion-md-search"></i>
      </div>
    </div>
    <table dir="rtl">
      <tr class="head">
        <td>שם </td>
        <td>גיל</td>
        <!-- <td>עיר</td> -->
        <td>אלופים</td>
      </tr>
      <tr v-for="item in data">
        <td>{{ item.name }} {{ item.surname }}</td>
        <td>{{ item.age }}</td>
        <!-- <td></td> -->
        <td>
          <img
            v-if="(item.points >= 25 && ((Date.now() / 1000) > parseInt(item.date) + 2592000)) || item.had_donate"
            src="img/find-online.svg"
            alt="He is prizer"
          >
          <img v-else src="img/find-online-empty.svg" alt="He is not prizer">
        </td>
      </tr>
    </table>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      data: [],
      query: '',
      loading: false,
      ended: false,
      page: 0,
    };
  },
  components: {
    SimpleHeader,
  },
  created() {
    this.sync(true);
  },
  methods: {
    sync(update = false) {
      axios.post('/playlist/prizers', {
        query: this.query,
        page: this.page,
      }).then(res => {
        if(update) {
          this.data = res.data.result;
        } else {
          this.data.push(...res.data.result);
        }
        this.ended = res.data.ended;
      });
    },
    onScroll() {
      var doc = document.documentElement;
      var screen = doc.clientHeight;
      var top = ((window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0));
      if(top >= (doc.scrollHeight - screen) && !this.loading && !this.ended) {
        this.page++;
        this.sync();
      }
    }
  },
  mounted() {
    document.addEventListener('scroll', this.onScroll);
  },
  destroyed() {
    document.removeEventListener('scroll', this.onScroll);
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background-color: $clr-blue;
  table {
    width: 100%;
    text-align: center;
    border-spacing: 0;
    tr {
      td {
        border-bottom: 2px dashed $clr-wet;
        padding: 10px 0;
        img {
          vertical-align: middle;
        }
      }
      &:last-child {
        td {
          border-bottom: 0;
        }
      }
    }
    .head {
      td {
        border-bottom: 2px solid $clr-wet;
      }
    }
  }
  .sub-title {
    font-weight: bold;
    line-height: 1.1;
  }
  .sub-title, .sub-sub-title {
    margin: 0;
    direction: rtl;
    padding: 0 15px;
  }
  .sub-sub-title {
    margin: 10px 0;
    font-size: 14px;
  }
  .top-cnt {
    padding: 20px 0;
    height: 130px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    justify-content: space-between;
    .search {
      width: 250px;
      height: 40px;
      border-radius: 20px;
      overflow: hidden;
      margin: 0 auto;
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
    .schools {
      margin: 0 auto;
      display: block;
      width: 250px;
      background-color: $clr-wet;
      height: 40px;
      border-radius: 20px;
      color: #fff;
      background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiNmZmYiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJNNDAxLjQgMjI0aC0yMTRsODMtNzkuNGMxMS45LTEyLjUgMTEuOS0zMi43IDAtNDUuMnMtMzEuMi0xMi41LTQzLjIgMEw4OSAyMzMuNGMtNiA1LjgtOSAxMy43LTkgMjIuNHYuNGMwIDguNyAzIDE2LjYgOSAyMi40bDEzOC4xIDEzNGMxMiAxMi41IDMxLjMgMTIuNSA0My4yIDAgMTEuOS0xMi41IDExLjktMzIuNyAwLTQ1LjJsLTgzLTc5LjRoMjE0YzE2LjkgMCAzMC42LTE0LjMgMzAuNi0zMiAuMS0xOC0xMy42LTMyLTMwLjUtMzJ6Ii8+PC9zdmc+);
      background-position: 15px center;
      background-repeat: no-repeat;
      background-size: 16px auto;
    }
  }
}

</style>
