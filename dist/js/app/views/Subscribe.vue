<template lang="html">
  <main class="img-but-subs-bg" dir="rtl">
    <!-- SUBSCRIBE TO PEWDIEPIE -->
    <simple-header>
      <span slot="title">רכישת מנוי</span>
    </simple-header>
    <div class="top">
      <img src="img/idea-subs.svg" alt="">
    </div>
    <div class="buttons">
      <div class="line">
        <button @click="subscribe('games')" class="theme-button-outline">
          {{ prices.games }}
          ש“ח
        </button>
        <div class="text">
          מנוי ל-20 משחקים
        </div>
      </div>
      <div class="line">
        <button @click="subscribe('month')" class="theme-button-outline">
          {{ prices.month }}
          ש“ח
        </button>
        <div class="text">
          מנוי לחודש
          <div class="subtext">
            ניתן להפסיק לאחר חודש
          </div>
        </div>
      </div>
      <div class="line">
        <button @click="subscribe('six')" class="theme-button-outline">{{ prices.six }} ש“ח</button>
        <div class="text">
          מנוי לחצי שנה
          <div class="subtext">
            ניתן להפסיק לאחר חצי שנה
          </div>
        </div>
      </div>
    </div>
    <div class="bottom">
      <button @click="usePromo" class="promo theme-button-outline">קוד</button>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import Payment from './../components/modals/Payment.vue';
import PromoCode from './../components/modals/PromoCode.vue';

export default {
  data() {
    return {
      sent: false,
      prices: {},
    };
  },
  methods: {
    getPrices() {
      axios.get('/variable/prices').then(res => {
        this.prices = res.data.value;
      });
    },
    startTransaction(type) {
      return new Promise((resolve, reject) => {
        axios.get('/subscribe/url/' + type).then(res => {
          this.$modal.show(Payment, {
            url: res.data.url,
            id_transaction: res.data.id_transaction,
            ended: () => {
              this.$store.dispatch('updateUserData');
              this.$router.replace('/account');
            }
          }, {
            adaptive: true,
            height: '90%',
            width: '90%',
          });
        }).finally(() => {
          resolve();
        });
      });
    },
    usePromo() {
      this.$modal.show(PromoCode, {}, {
        height: 'auto',
        width: 300,
      });
    },
    subscribe(type) {

      if(this.sent) return;
      this.sent = true;
      this.startTransaction(type).then(() => {
        this.sent = false;
      });

    }
  },
  components: {
    SimpleHeader,
  },
  created() {
    this.getPrices();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  color: #fff;
  background-color: $clr-wet;
  background-position: bottom -40px right -40px;
  background-repeat: no-repeat;
  background-size: auto;
  padding-bottom: 200px;
  .top {
    padding: 0 15px;
    text-align: left;
  }
  .buttons {
    border: 1px solid #fff;
    border-left: 0;
    border-right: 0;
    margin: 0 15px;
    padding: 15px 0;
    .line {
      padding: 10px 0;
      line-height: 40px;
      position: relative;
      .subtext {
        position: absolute;
        line-height: 0;
        font-size: 10px;
        bottom: 10px;
      }
      button {
        width: 120px;
        background-color: #E36574;
        float: left;
      }
    }
  }
  .bottom {
    padding: 15px;
    width: 100%;
    padding-top: 25px;
  }
  .promo {
    width: 100%;
  }
}

</style>
