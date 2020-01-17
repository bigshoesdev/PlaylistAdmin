<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        הזנת סיסמה חדשה
      </span>
    </simple-header>
    <form class="app-inputs" @submit="send">
      <div class="app-section">
        <p>סיסמה חדשה </p>
        <input v-model="pass" type="password" required class="theme-input">
      </div>
      <div class="app-section">
        <p> אימות סיסמה </p>
        <input v-model="confirm" type="password" required class="theme-input">
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
      pass: '',
      confirm: '',
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      axios.post('/user/recover/new-pass', {
        pass: this.pass,
        confirm: this.confirm,
        code: this.$route.params.code,
      }).then(res => {
        this.$router.push('/login');
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
