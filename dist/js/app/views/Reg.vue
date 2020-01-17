<template lang="html">
  <main>
    <simple-header inverse route="/">
      <span slot="title">הרשמה</span>
    </simple-header>
    <div class="additional" dir="rtl">
      <div class="top">
        <span>
          אני כבר רשום
        </span>
        <router-link to="/login">
          התחבר
        </router-link>
      </div>
      <div class="bottom">
        <span>
          הרשמה דרך
        </span>
        <div class="socials">
          <button class="facebook" @click="regFacebook"></button>
        </div>
      </div>
    </div>
    <form @submit="reg">
      <div class="inputs" dir="rtl">
        <div class="section">
          <p>
            שם פרטי
          </p>
          <input v-model="name" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
            שם משפחה
          </p>
          <input v-model="surname" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
            דוא"ל
          </p>
          <input v-model="email" required type="email" class="theme-input">
        </div>
        <div class="section">
          <p>
            סיסמה
          </p>
          <input maxlength="32" required v-model="pass"  type="password" class="theme-input">
        </div>
        <div class="section">
          <p>
            גיל
          </p>
          <input max="99" min="4" v-model="age" type="number" class="theme-input">
        </div>
        <div class="section">
          <p>
            מין
          </p>
          <div class="gender">
            <button
              @click="gender = 'fem'"
              :class="{ selected: gender == 'fem' }"
              class="female"
              type="button"
            ></button>
            <div class="spacer">

            </div>
            <button
              @click="gender = 'man'"
              :class="{ selected: gender == 'man' }"
              class="male"
              type="button"
            ></button>
          </div>
        </div>
      </div>
      <div class="end" dir="rtl">
        <label>
          <input v-model="conditions" required class="theme-checkbox" type="checkbox">
          אישור תנאי שימוש
        </label>
        <router-link to="/terms">
          <span>
            צפייה
          </span>
        </router-link>
        <div>
          <button :disabled="!conditions" type="submit" class="theme-button">
            הרשמה
          </button>
        </div>
      </div>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  name: 'reg',
  data() {
    return {
      email: '',
      pass: '',
      gender: null,
      name: '',
      surname: '',
      age: null,
      conditions: false,
    };
  },
  methods: {
    reg(e) {

      // if(!this.gender) {
      //   this.$store.commit('alert', 'בחר את המין שלך, בבקשה');
      // }

      if(!this.conditions) {
        this.$store.commit('alert', 'קבל תנאים, בבקשה');
      }

      e.preventDefault();
      axios.post('/user/registrate', {
        email: this.email,
        pass: this.pass,
        gender: this.gender,
        age: this.age,
        name: this.name,
        surname: this.surname,
      }).then(res => {
        axios.post('/user/login', {
          email: this.email,
          pass: this.pass,
        }).then(res => {
          this.$store.commit('auth', res.data.auth);
          this.$store.dispatch('updateUserData');
          this.$router.replace({ name: 'main' });
        });
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    },
    regFacebook() {
      // для того что бы получить 'user_birthday', 'user_gender' надо пройти App Review
      var permissions = ['public_profile', 'email'];
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
            // alert('SUCCESS');
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
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .additional .top,
  .additional .bottom {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 5px;
  }
  .additional {
    padding: 20px;
    padding-bottom: 0;
    .top {
      a {
        color: #1A92B8;
        text-decoration: underline;
      }
    }
    .bottom {
      line-height: 40px;
    }
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
          background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIiIGhlaWdodD0iMjUiIHZpZXdCb3g9IjAgMCAxMiAyNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTEuMzY3MiAxMkg4VjI1SDNWMTJIMFY4SDNWNS41ODk4NEMzLjAwMzkxIDIuMDgyMDMgNC40NjA5NCAwIDguNTkzNzUgMEgxMlY0SDkuNzE0ODRDOC4xMDU0NyA0IDggNC42MDE1NiA4IDUuNzIyNjZWOEgxMkwxMS4zNjcyIDEyWiIgZmlsbD0id2hpdGUiLz48L3N2Zz4=);
          background-position: 20px 10px;
        }
      }
    }
  }
  .inputs {
    padding: 20px;
    padding-top: 0;
    .section {
      padding-top: 10px;
      color: $clr-wet;
      input {
        width: 100%;
      }
      p {
        margin: 0;
      }
    }
  }
  .gender {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: stretch;
    .female, .male  {
      width: 120px;
      height: 120px;
      border-radius: 5px;
      border: 2px solid #fff;
      line-height: 100px;
      font-size: 80px;
      color: #fff;
      display: block;
      background-position: center;
      background-repeat: no-repeat;
      background-size: 70% auto;
      &.selected {
        border-color: $clr-pink;
      }
    }
    .spacer {
      flex-grow: 1;
    }
    .female {
      background-color: #FF7384;
      background-image: url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjZmZmIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJNNDAwIDE3NmMwLTc5LjUtNjQuNS0xNDQtMTQ0LTE0NFMxMTIgOTYuNSAxMTIgMTc2YzAgNzEuNCA1MS45IDEzMC42IDEyMCAxNDJ2NTBoLTcydjQ4aDcydjY0aDQ4di02NGg3MnYtNDhoLTcydi01MGM2OC4xLTExLjQgMTIwLTcwLjYgMTIwLTE0MnptLTI0MCAwYzAtNTIuOSA0My4xLTk2IDk2LTk2czk2IDQzLjEgOTYgOTYtNDMuMSA5Ni05NiA5Ni05Ni00My4xLTk2LTk2eiIvPjwvc3ZnPg==);
    }
    .male {
      background-color: $clr-wet;
      background-image: url(data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjZmZmIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJNNDE2IDQ4SDI4OHY0OGg5NC4xTDI3NS40IDIwMi42QzI1MS45IDE4NS45IDIyMy4xIDE3NiAxOTIgMTc2Yy03OS41IDAtMTQ0IDY0LjUtMTQ0IDE0NHM2NC41IDE0NCAxNDQgMTQ0IDE0NC02NC41IDE0NC0xNDRjMC0zMS4xLTkuOS01OS45LTI2LjYtODMuNEw0MTYgMTI5LjlWMjI0aDQ4VjQ4aC00OHpNMTkyIDQxNmMtNTIuOSAwLTk2LTQzLjEtOTYtOTZzNDMuMS05NiA5Ni05NiA5NiA0My4xIDk2IDk2LTQzLjEgOTYtOTYgOTZ6Ii8+PC9zdmc+);
    }
  }
  .end {
    padding: 20px;
    padding-top: 0;
    span {
      color: #fff;
    }
    button {
      width: 100%;
    }
  }
}

</style>
