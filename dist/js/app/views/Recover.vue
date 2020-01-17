<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        שכחתי סיסמה
      </span>
    </simple-header>
    <form class="app-inputs" @submit="send">
      <div class="app-section">
        <p>מייל </p>
        <input v-model="email" type="email" required class="theme-input">
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
      email: '',
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      axios.post('/user/recover', {
        email: this.email,
      }).then(res => {
        this.$router.push('/recover/code');
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
