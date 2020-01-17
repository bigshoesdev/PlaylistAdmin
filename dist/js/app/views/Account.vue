<template lang="html">
  <main dir="rtl">
    <header dir="ltr">
      <button @click="saveProfile" class="save">שמור</button>
    </header>
    <div class="avatar bg-img" :style="{ backgroundImage: `url(${$store.state.user.img})` }">
      <input type="file" @change="onFileChange" ref="file">
      <i v-if="!$store.state.user.img" class="icon ion-md-images"></i>
      <button class="edit" @click="onImageClick"></button>
    </div>
    <div class="disc">
      <template v-if="!$store.getters.hasSubs">
        <p class="title">
          <span class="big">פרטי המנוי שלי</span>
          <span class="games">
            משחקים שנותרו
            <span>
              {{ $store.state.user.subscribe_total_games }}/
              {{ $store.state.user.subscribe_total_games - $store.state.user.subscribe_games }}
            </span>
            שחקתי <span>{{ $store.state.user.games_summary }}</span>
          </span>
        </p>
        <div class="bordered no-subs">
          <p>אין מנוי</p>
          <router-link to="/subscribe">לרכישה</router-link>
        </div>
      </template>
      <template v-else>
        <p class="title">פרטי המנוי שלי</p>
        <div class="bordered">
          <p v-if="$store.state.user.subscribe_interval == 2.62 * Math.pow(10, 6)">מנוי חודשי</p>
          <p v-else-if="$store.state.user.subscribe_interval == 15700000">6 חודשים</p>

          <p>מתאריך {{ new Date($store.state.user.subscribe * 1000) | dateFormat('DD/MM/YYYY') }}</p>
          <p>עד {{ new Date(($store.state.user.subscribe + $store.state.user.subscribe_interval) * 1000) | dateFormat('DD/MM/YYYY') }}</p>
        </div>
      </template>
    </div>
    <div class="inputs">
      <div class="section">
        <p>
          שם מלא
        </p>
        <input v-model="name" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>
          שם מלא
        </p>
        <input v-model="surname" type="text" class="theme-input">
      </div>
      <div class="section">
        <p>
          גיל
        </p>
        <input v-model="age" type="number" class="theme-input">
      </div>
      <div class="section">
        <p>
          מין
        </p>
        <switcher v-model="gender" :values="[
          { value: 'man', name: 'זכר' },
          { value: 'fem', name: 'נקבה' },
        ]"/>
      </div>
      <div class="section">
        <p>
          מייל
        </p>
        <input v-model="email" type="email" class="theme-input">
      </div>
      <div class="section">
        <p>
          סיסמה
        </p>
        <input v-model="pass" type="password" class="theme-input">
      </div>
      <div class="section">
        <p>
          טלפון
        </p>
        <input v-model="phone" v-mask="'###-#######'" type="tel" class="theme-input">
      </div>
    </div>
    <div class="end" dir="rtl">
      <button @click="logout" class="bot-text">התנתקות</button>
    </div>

  </main>
</template>

<script>
import Switcher from './../components/Switcher.vue';
import Logout from './../components/modals/Logout.vue';
import SavedProfile from './../components/modals/SavedProfile.vue';
export default {
  computed: {

  },
  methods: {
    onImageClick() {
      this.$refs.file.click();
    },
    onFileChange() {
      var data = new FormData();
      data.append('image', this.$refs.file.files[0]);
      axios.post('/user/image/permanent', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        this.$store.state.user.img = res.data.url;
        this.$store.state.user.full_img = res.data.full_url;
      });
    },
    saveProfile() {
      axios.put('/user/edit', {
        pass: this.pass,
        email: this.email,
        name: this.name,
        age: this.age,
        phone: this.phone,
        gender: this.gender,
      }).then(res => {
        if(res.data.auth) {
          this.$store.commit('auth', res.data.auth);
        }
        this.$store.dispatch('updateUserData');
        this.$modal.show(SavedProfile, {}, {
          height: 'auto',
          adaptive: true,
          width: 300,
        });
        this.$router.go(-1);
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    },
    logout() {
      this.$modal.show(Logout, {}, {
        height: 'auto',
        adaptive: true,
        width: 300,
      });
    }
  },
  components: {
    Switcher
  },
  data() {
    return {
      pass: '',
      email: '',
      name: '',
      surname: '',
      age: '',
      phone: '',
      gender: '',
    };
  },
  created() {
    this.email = this.$store.state.user.email;
    this.name = this.$store.state.user.name;
    this.surname = this.$store.state.user.surname;
    this.age = this.$store.state.user.age;
    this.phone = this.$store.state.user.phone;
    this.gender = this.$store.state.user.gender;
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  padding-bottom: 20px;
  background: $clr-blue;
  header {
    padding: 15px 10px;
    padding-bottom: 0;
    button {
      color: #fff;
    }
  }
  .disc {
    padding: 15px;
    padding-bottom: 0;
    .title {
      margin: 0;
      position: relative;
      .big {
        font-size: 18px;
        font-weight: bold;
      }
      .games {
        display: block;
        span {
          font-weight: bold;
        }
      }
    }
    .bordered {
      height: 100px;
      border: 1px solid #fff;
      display: flex;
      padding: 20px;
      flex-direction: column;
      align-items: flex-start;
      justify-content: center;
      color: #fff;
      p {
        margin: 0;
        line-height: 115%;
      }
      &.no-subs {
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        a {
          text-decoration: underline;
          color: #fff;
        }
      }
    }
  }
  .avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 1px solid #fff;
    background-color: #DBDBDB;
    margin: 0 auto;
    position: relative;
    color: #fff;
    text-align: center;
    line-height: 100px;
    font-size: 38px;
    input {
      position: absolute;
      top: 0;
      left: -99999px;
      opacity: 0;
    }
    .edit {
      padding: 0;
      position: absolute;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      background: #fff;
      color: #DBDBDB;
      right: 0;
      bottom: 0;
      font-size: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGZpbGw9IiNDMUMxQzEiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIj48cGF0aCBkPSJNNDE2IDI3Ny4zMzNIMjc3LjMzM1Y0MTZoLTQyLjY2NlYyNzcuMzMzSDk2di00Mi42NjZoMTM4LjY2N1Y5Nmg0Mi42NjZ2MTM4LjY2N0g0MTZ2NDIuNjY2eiIvPjwvc3ZnPg==);
      background-position: center;
      background-repeat: no-repeat;
      background-size: 30px;
    }
  }
  .inputs {
    padding-bottom: 20px;
    .section {
      padding: 0 15px;
      p {
        margin: 0;
        margin-top: 7px;
      }
      input {
        width: 100%;
      }
    }
  }
  .pre-bot-text,
  .bot-text {
    display: block;
    color: #fff;
    padding: 0 20px;
  }
  .bot-text {
    margin-top: 20px;
  }
}

</style>
