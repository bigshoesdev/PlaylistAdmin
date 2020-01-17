<template lang="html">
  <div class="modal" dir="rtl">
    <h2>
      <i class="icon ion-md-lock"></i>
      אזור הורים
    </h2>
    <p>הכנס קוד גישה להורים</p>
    <pin-code ref="code" v-model="code" :length="4"/>
    <small v-if="!setting && err">קוד שגוי</small>
    <button @click="login" class="theme-button-outline">אישור</button>
    <button v-if="!setting" @click="forgetPass" class="forget">שלח לי קוד חדש למייל</button>
  </div>
</template>

<script>
import PinCode from './PinCode.vue';
export default {
  props: {
    to: {},
    setting: {
      default: false,
      type: Boolean,
    }
  },
  data() {
    return {
      code: '',
      err: false,
    };
  },
  methods: {
    login() {
      if(this.setting) {
        axios.post('/parent/set-pass', {
          code: this.code
        }).then(res => {
          axios.defaults.headers.common['Parent-Authorization'] = this.code;
          this.$store.state.user.parent = true;
          this.err = false;
          this.$emit('close');
          this.$router.push('/parent');
        }).catch(res => {
          this.err = true;
        });
      } else {
        axios.post('/parent/login', {
          code: this.code
        }).then(res => {
          axios.defaults.headers.common['Parent-Authorization'] = this.code;
          this.$store.state.user.parent = true;
          this.err = false;
          this.$emit('close');
          this.$router.push(this.to.path);
        }).catch(res => {
          this.err = true;
        });
      }
    },
    forgetPass() {
      this.$router.push({ name: 'restore-parent-code', params: { to: this.to } });
      axios.put('/parent/code/first');
      this.$emit('close');
    }
  },
  components: {
    PinCode,
  },
  mounted() {
    this.$refs.code.focus();
    // this.code = '1234';
    // this.login();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  background: $clr-blue;
  border-radius: 10px;
  color: #fff;
  padding: 20px;
  small {
    color: #D13232;
  }
  button {
    margin-top: 20px;
    width: 100%;
  }
  p {
    margin: 0;
    margin-bottom: 20px;
  }
  h2 {
    margin: 0;
  }
  .forget {
    font-size: 17px;
    text-decoration-line: underline;
    color: $clr-wet;
    padding-top: 10px;
  }
}

</style>
