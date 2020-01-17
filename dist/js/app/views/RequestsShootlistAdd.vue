<template lang="html">
  <main>
    <simple-header>
      <span slot="title">הצעת שאלה חדשה</span>
    </simple-header>
    <form @submit="add" class="app-inputs" dir="rtl">
      <div class="app-section">
        <p>רשום שאלה</p>
        <textarea required v-model="question" class="theme-input"></textarea>
      </div>
      <div class="app-section">
        <p>בית ספר <span class="small">(לא חובה)</span> </p>
        <school-selector v-model="school"/>
      </div>
      <div class="app-section">
        <p>עיר / ישוב <span class="small">(לא חובה)</span> </p>
        <input v-model="city" class="theme-input"/>
      </div>
      <div class="app-section credit">
        <label class="heebo">
          <input v-model="credit" class="theme-checkbox-big" type="checkbox">
          רוצה קרדיט
        </label>
      </div>
      <small>הבהרה :לא כל ההצעות יתפרסמו, ואין התחייבות למתן קרדיט</small>
      <button class="theme-button-outline">שלח</button>
    </form>
  </main>
</template>

<script>

import SchoolSelector from './../components/SchoolSelector.vue';
import SimpleHeader from './../components/SimpleHeader.vue';
import RequestShootlistCreated from './../components/modals/RequestShootlistCreated.vue';

export default {
  data() {
    return {
      question: '',
      school: null,
      city: '',
      credit: false,
    };
  },
  methods: {
    add(e) {
      e.preventDefault();
      axios.post('/shootlist/request', {
        question: this.question,
        id_school: this.school ? this.school.id_school : null,
        city: this.city,
        credit: this.credit,
      }).then(res => {
        this.$modal.show(RequestShootlistCreated, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        });
        this.$router.replace({ name: 'shootlist' });
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    }
  },
  components: {
    SimpleHeader,
    SchoolSelector,
  },
  created() {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .theme-input {
    border-radius: 5px;
    padding: 2px;
    height: 30px;
    width: 100%;
  }
  .app-inputs {
    padding: 0 20px;
    padding-bottom: 30px;
    .app-section {
      input[typer="text"], textarea {
        font-size: 16px;
      }
      textarea {
        height: 80px;
        resize: none;
        vertical-align: middle;
      }
    }

    small {
      margin-top: 15px;
      margin-bottom: 5px;
      display: block;
      font-size: 12px;
      text-align: center;
      width: 100%;
      color: #fff;
    }
    .inp-wrapper {
      position: relative;
      span {
        position: absolute;
        left: 10px;
        bottom: 0;
        font-size: 12px;
      }
    }
  }
  .theme-button-outline {
    display: block;
    width: 100%;
  }
  .credit {
    padding-top: 10px;
    font-size: 12px;
    input {
      margin-left: 10px;
    }
  }
}

</style>
