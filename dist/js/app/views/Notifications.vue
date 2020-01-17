<template lang="html">
  <main>
    <simple-header button="שמור" @back="save">
      <span slot="title">התראות</span>
    </simple-header>
    <section dir="rtl">
      <div class="email">
        <p>לקבלת התראות במייל</p>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.email_funzone" class="theme-checkbox-big">
             ארועי <span class="fugaz">FUN ZONE</span> באזורך
          </label>
        </div>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.email_dev" class="theme-checkbox-big">
             דו”ח חודשי להתפתחות הילד\ה
          </label>
        </div>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.email_code" class="theme-checkbox-big">
            קוד הורים
          </label>
        </div>
        <div class="input">
          <input type="text" class="theme-input" v-model="user.email">
          <button @click="saveEmail" class="theme-button-outline">אישור</button>
        </div>
      </div>


      <div class="phone">
        <p>לקבלת התראות בסמס</p>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.phone_funzone" class="theme-checkbox-big">
             ארועי <span class="fugaz">FUN ZONE</span> באזורך
          </label>
        </div>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.phone_dev" class="theme-checkbox-big">
             דו”ח חודשי להתפתחות הילד\ה
          </label>
        </div>
        <div class="section">
          <label>
            <input type="checkbox" v-model="data.phone_code" class="theme-checkbox-big">
            קוד הורים
          </label>
        </div>
        <div class="input">
          <input type="text" class="theme-input" v-model="user.phone">
          <button @click="savePhone" class="theme-button-outline">אישור</button>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      data: {},
      user: {},
    };
  },
  components: {
    SimpleHeader,
  },
  methods: {
    saveEmail() {
      axios.put('/user/edit', {
        email: this.user.email
      }).then(res => {
        // TODO:
      });
    },
    savePhone() {
      axios.put('/user/edit', {
        phone: this.user.phone
      }).then(res => {
        // TODO:
      });
    },
    save() {
      axios.put('/notification', this.data).then(res => {
        this.$router.go(-1);
      });
    }
  },
  created() {
    axios.get('/notification').then(res => {
      this.data = res.data;
    });
    axios.get('/user/info').then(res => {
      this.user = res.data;
    });
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background-color: $clr-wet;
  color: #fff;
  .input {
    padding: 20px 0;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    input {
      flex-grow: 1;
    }
    button {
      width: 90px;
      margin-right: 20px;
    }
  }
  .email {
    margin-bottom: 50px;
  }
  section {
    padding: 0 20px;
    p {
      font-size: 22px;
    }
    .section {
      padding: 5px 0;
      .theme-checkbox-big {
        vertical-align: middle;
        margin-left: 10px;
      }
    }
  }
}

</style>
