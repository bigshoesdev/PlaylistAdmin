<template lang="html">
  <div class="modal" dir="rtl">
    <p>יש לך קוד זיכוי?</p>
    <input v-model="code" class="theme-input" type="text" placeholder="הכנס את קוד הזיכוי">
    <p v-if="err" class="error">{{ err }}</p>
    <button @click="promo" class="use-promo theme-button-outline">הרשמה חינם</button>
  </div>
</template>

<script>
export default {
  props: {

  },
  data() {
    return {
      err: null,
      sent: false,
      code: '',
    };
  },
  methods: {
    promo() {
      if(this.sent) return;
      this.sent = true;
      axios.post('/subscribe/promo', {
        promo: this.code,
      }).then(res => {
        this.$store.dispatch('updateUserData');
        this.$router.replace('/account');
        this.$emit('close');
      }).catch(res => {
        this.err = res.data.error;
      }).finally(() => {
        this.sent = false;
      });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

p {
  margin-top: 0;
  font-size: 20px;
}

.modal {
  padding: 20px;
}

.promo {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  justify-content: space-between;
}

.use-promo {
  margin-top: 30px;
  border-color: $clr-wet;
  color: $clr-wet;
  width: 100%;
}

.theme-input {
  width: 100%;
}

.error {
  color: red;
  margin: 0;
  font-size: 14px;
}

</style>
