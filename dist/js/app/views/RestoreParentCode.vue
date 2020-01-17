<template lang="html">
  <main>
    <simple-header inverse route="/parent">
      <span slot="title">הגדרת קוד למתנות מהמשחקים</span>
    </simple-header>
    <section dir="rtl">
      <i class="icon ion-md-lock"></i>
      <p>הכנס את הקוד שקבלת במייל</p>
      <pin-code v-model="code" :length="4"/>
      <small v-if="err">קוד שגוי</small>
      <button class="theme-button-outline" @click="login">הגדר קוד להורים</button>
      <button class="no-style" @click="noReceive">קבל את הקוד ב- <span class="fugaz">SMS</span> </button>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import PinCode from './../components/PinCode.vue';
export default {
  data() {
    return {
      code: '',
      err: false,
      sent: false,
    };
  },
  methods: {
    login() {
      axios.post('/parent/login', {
        code: this.code
      }).then(res => {
        axios.defaults.headers.common['Parent-Authorization'] = this.code;
        this.$store.state.user.parent = true;
        this.err = false;
        this.$emit('close');
        this.$router.push(this.$route.params.to.path);
      }).catch(res => {
        this.err = true;
      });
    },
    noReceive() {
      if (!this.sent) {
        this.sent = true;
        axios.put('/parent/code/second');
        this.$store.commit('alert', 'SMS נשלח אל הטלפון שלך');
      }
    }
  },
  components: {
    SimpleHeader,
    PinCode,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  section {
    text-align: center;
  }
  p {
    margin: 30px auto;
    color: #fff;
    width: 290px;
  }
  i {
    font-size: 120px;
    color: #fff;
    line-height: 0;
  }
  button {
    width: 250px;
    display: block;
    margin: 15px auto;
  }
  small {
    color: #D13232;
  }
}

</style>
