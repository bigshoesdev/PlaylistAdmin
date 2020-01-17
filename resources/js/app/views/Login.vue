<template lang="html">
  <v-container>
    <v-form v-model="valid">
      <v-text-field
        v-model="email"
        label="E-mail"
        type="email"
        required
      ></v-text-field>

      <v-text-field
        v-model="pass"
        label="Password"
        required
        type="password"
      ></v-text-field>

      <v-btn
        :disabled="!valid"
        color="success"
        @click="login"
      >
        Validate
      </v-btn>
    </v-form>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      email: '',
      pass: '',
      valid: false,
    };
  },
  methods: {
    login() {
      if(this.valid) {
        axios.post('/user/login', {
          email: this.email,
          pass: this.pass,
        }).then(res => {
          axios.defaults.headers.common['Authorization'] = res.data.auth;
          this.$root.logged = true;
          this.$cookie.set('auth', res.data.auth);
          this.$router.push('/');
        });
      }
    }
  }
}
</script>

<style lang="scss" scoped>



</style>
