<template lang="html">
  <main>
    <simple-header inverse route="/reg">
      <span slot="title">התחברות</span>
    </simple-header>
    <div class="additional" dir="rtl">
      <span>
        התחבר דרך
      </span>
      <div class="socials">
        <button class="facebook" @click="loginFB"></button>
      </div>
    </div>
    <!-- keep alive -->
    <form @submit="login">
      <div class="app-inputs" dir="rtl">
        <div class="app-section">
          <p>
            מייל
          </p>
          <input required v-model="email" type="email" class="theme-input">
        </div>
        <div class="app-section">
          <p>
            סיסמה
          </p>
          <input required v-model="pass" type="password" class="theme-input">
          <p class="recover">
            <router-link to="/recover">שכחתי ססמה</router-link>
          </p>
        </div>
      </div>
      <div class="end" dir="rtl">
        <button type="submit" class="theme-button">
          התחבר
        </button>
        <p>
          עדין לא נרשמת
          <router-link to="/reg">הרשמה</router-link>
        </p>
      </div>
    </form>

  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  name: 'login',
  data() {
    return {
      email: '',
      pass: '',
      gender: '',
      name: '',
      surname: '',
      age: 0,
    };
  },
  methods: {
    login(e) {
      e.preventDefault();
      axios.post('/user/login', {
        email: this.email,
        pass: this.pass,
      }).then(res => {
        this.$store.commit('auth', res.data.auth);
        this.$store.dispatch('updateUserData');
        this.$router.replace({ name: 'main' });
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    },
    loginFB() {
      var permissions = [ 'public_profile', 'email' ];
      facebookConnectPlugin.login(permissions, auth => {
        facebookConnectPlugin.api('/me?fields=email,first_name,last_name,picture', permissions, data => {
          // alert(JSON.stringify(data));
          axios.post('/user/facebook/enter', {
            email: data.email,
            name: data.first_name,
            surname: data.last_name,
            id_facebook: auth.authResponse.userID,
            access_token: auth.authResponse.accessToken,
            img: data.picture.data.url,
          }).then(res => {
            this.$store.commit('auth', res.data.auth);
            this.$store.dispatch('updateUserData');
            this.$router.replace({ name: 'main' });
          }).catch(res => {
            this.$store.commit('alert', res.data.error);
          });
        }, err => {
          this.$root.debug(err);
        })
      }, err => {
        this.$root.debug(err);
      })
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
  .additional {
    padding: 20px;
    padding-bottom: 0;
    line-height: 40px;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    .socials {
      button {
        width: 40px;
        display: inline-block;
        height: 40px;
        border-radius: 5px;
        border: 1px solid #fff;
        &.facebook {
          background: #005D7A;
          background-repeat: no-repeat;
          background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMjUiIHZpZXdCb3g9IjAgMCAxMiAyNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTEuMzY3MiAxMkg4VjI1SDNWMTJIMFY4SDNWNS41ODk4NEMzLjAwMzkxIDIuMDgyMDMgNC40NjA5NCAwIDguNTkzNzUgMEgxMlY0SDkuNzE0ODRDOC4xMDU0NyA0IDggNC42MDE1NiA4IDUuNzIyNjZWOEgxMkwxMS4zNjcyIDEyWiIgZmlsbD0id2hpdGUiLz48L3N2Zz4=');
          background-position: 20px 10px;
        }
      }
    }
  }
  .app-inputs {
    .recover {
      text-align: left;
      text-decoration: underline;
      a {
        color: #005D7A;
      }
    }
  }
  .end {
    flex-grow: 1;
    padding: 20px;
    button {
      width: 100%;
    }
    p {
      margin: 0;
      font-size: 18px;
      text-align: center;
      a {
        color: #1A92B8;
        text-decoration: underline;
      }
    }
  }
}

</style>
