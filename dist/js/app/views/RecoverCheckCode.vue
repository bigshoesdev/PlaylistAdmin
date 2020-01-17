<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        שחכתי סיסמה
      </span>
    </simple-header>
    <form class="app-inputs" @submit="send">
      <div class="app-section">
        <p>ממתין לך במייל קוד לשחזור סיסמה</p>
        <input v-model="code" type="text" required class="theme-input">
      </div>
      <div class="end">
        <button type="submit" class="theme-button">שלח</button>
      </div>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      code: '',
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      axios.get('/user/recover/' + this.code).then(res => {
        this.$router.push('/recover/' + this.code + '/pass');
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    }
  },
  components: {
    SimpleHeader,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .end {
    padding: 20px 0;
    button {
      width: 100%;
    }
  }
}

</style>
