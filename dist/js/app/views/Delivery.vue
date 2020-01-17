<template lang="html">
  <main dir="rtl">
    <simple-header :inverse="true">
      <span slot="title">
        כתובת למשלוח
      </span>
    </simple-header>
    <form @submit="send">
      <div class="app-inputs">
        <div class="app-section">
          <p>
            עיר
          </p>
          <input required v-model="city" type="text" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            רחוב
          </p>
          <input required v-model="street" type="text" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            מספר
          </p>
          <input required v-model="num" type="text" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            מיקוד
          </p>
          <input required v-model="postal" type="text" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            תא דואר
          </p>
          <input v-model="mail" type="text" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            הערות
          </p>
          <textarea v-model="comment" class="theme-input"></textarea>
        </div>
      </div>
      <div class="end">
        <button class="theme-button-outline">
          הבא
        </button>
      </div>
    </form>
  </main>
</template>

<script>
import FunKitBought from './../components/modals/FunKitBought.vue';
import SimpleHeader from './../components/SimpleHeader.vue';
import Payment from './../components/modals/Payment.vue';
export default {
  data() {
    return {
      city: '',
      street: '',
      num: '',
      mail: '',
      postal: '',
      comment: '',
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      axios.post('/gift/buy', {
        city: this.city,
        street: this.street,
        num: this.num,
        mail: this.mail,
        postal: this.postal,
        comment: this.comment,
      }).then(res => {
        this.$modal.show(FunKitBought, {}, {
          adaptive: true,
          width: 300,
          height: 'auto',
        });
        this.$router.push('/main');
      });
    }
  },
  components: {
    SimpleHeader
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .end {
    text-align: center;
    .theme-button-outline {
      margin-top: 20px;
      width: 250px;
      margin-bottom: 20px;
    }
  }
}

</style>
