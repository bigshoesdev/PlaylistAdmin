<template lang="html">
  <div class="modal" dir="rtl">
    <div class="header">
      <div class="num">
        {{ $store.state.user.subscribe_total_games }} /
        {{ $store.state.user.subscribe_total_games - $store.state.user.subscribe_games }}
      </div>
      <div class="text">
        עד כה צפית ב:
      </div>
    </div>
    <div class="main">
      <div class="head">
        על מנת לקבל גישה  ליותר
        מ 40 משחקים נדרש לרכוש מנוי
      </div>
      <div class="content">
        <div class="title">
          המנוי מעניק גם:
        </div>
        <div class="items">
          <div class="item">
            <div class="img img-subscribe-1"></div>
            <div class="text">
              200 ש”ח שובר <br>
              לסדנת משחקים
            </div>
          </div>

          <div class="item">
            <div class="img img-subscribe-2"></div>
            <div class="text">
              צמיד משחקים
            </div>
          </div>

          <div class="item">
            <div class="img img-subscribe-3"></div>
            <div class="text">
               מעקב הורים
            </div>
          </div>

          <div class="item">
            <div class="img img-subscribe-4"></div>
            <div class="text">
              התאמת <br>
              המשחקים לילד
            </div>
          </div>

          <div class="item">
            <div class="img img-subscribe-5"></div>
            <div class="text">
              גישה חופשית <br>
              ל- <span class="fugaz">Shootlist</span> <br>
              לשיח משפחתי
            </div>
          </div>

          <div class="item">
            <div class="img img-subscribe-6"></div>
            <div class="text">
              גישה חופשית למאות <br>
              משחקים מחוץ למסך
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="end">
      <button @click="go" class="theme-button-outline">רוצה מנוי</button>
      <button @click="$emit('close')" class="no">לא תודה</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {

  },
  data() {
    return {
      points: 0,
    };
  },
  methods: {
    sync() {
      axios.get('/user/playlist/done').then(res => {
        this.points = res.data.points;
      });
    },
    go() {
      this.$emit('close');
      this.$router.push('/subscribe');
    },
    close() {

    }
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  .header {
    padding: 20px;
    color: #F18171;
    position: relative;
    .num {
      position: absolute;
      left: 20px;
      top: 20px;
      font-size: 37px;
      line-height: 100%;
    }
    .text {
      font-size: 27px;
    }
  }
  .main {
    padding: 0 20px;
    .head {
      font-size: 20px;
      padding-bottom: 10px;
    }
    .content {
      border-top: 1px solid $clr-blue;
      padding-top: 10px;
      .title {
        font-size: 17px;
      }
      .items {
        padding-top: 10px;
        .item {
          display: flex;
          flex-direction: row;
          justify-content: flex-start;
          align-items: center;
          min-height: 65px;
          padding: 10px 0;
          &:nth-child(1) .img {
            background-color: #87ECA3;
          }
          &:nth-child(2) .img {
            background-color: #D2EC87;
          }
          &:nth-child(3) .img {
            background-color: #F5ED34;
          }
          &:nth-child(4) .img {
            background-color: #F18171;
          }
          &:nth-child(5) .img {
            background-color: #87D4EC;
          }
          &:nth-child(6) .img {
            background-color: #B987EC;
          }
          .img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            background-position: center;
            background-repeat: no-repeat;
          }
          .text {
            margin-right: 20px;
          }
        }
      }
    }
  }
  .end {
    padding: 20px;
    button {
      display: block;
      margin: 0 auto;
      width: 200px;
      color: $clr-wet;
      &.theme-button-outline {
        border-color: $clr-blue;
        color: $clr-wet;
        margin-bottom: 10px;
      }
    }
  }
}

</style>
