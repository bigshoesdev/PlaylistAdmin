<template lang="html">
  <main>
    <simple-header>
      <span slot="title">הצעת משחק חדש / שיפור משחק</span>
    </simple-header>
    <form @submit="add" class="inputs" dir="rtl">
      <div class="section">
        <p>שלב 1</p>
        <textarea required v-model="question_1" class="theme-input" placeholder="תיאור השלב הראשון במשחק"></textarea>
      </div>
      <div class="section">
        <p>שלב 2</p>
        <textarea required v-model="question_2" class="theme-input" placeholder="תיאור השלב השני במשחק"></textarea>
      </div>
      <div class="section">
        <p>שלב 3</p>
        <textarea required v-model="question_3" class="theme-input" placeholder="תיאור השלב השלישי במשחק"></textarea>
      </div>
      <div class="section">
        <p>אפשרויות גיוון</p>
        <div class="inp-wrapper">
          <span>לא חובה</span>
          <textarea v-model="comment" placeholder="אפשרות נוספת כיצד לשחק את המשחק" class="theme-input"></textarea>
        </div>
      </div>
      <div class="section additional">
        <div class="section">
          <p>בית ספר <span class="small">(לא חובה)</span> </p>
          <school-selector v-model="school"/>
        </div>
        <div class="section city">
          <p>עיר / ישוב <span class="small">(לא חובה)</span> </p>
          <input v-model="city" class="theme-input"/>
        </div>
        <div class="section credit">
          <label class="heebo">
            <input v-model="credit" class="theme-checkbox-big" type="checkbox">
            רוצה קרדיט
          </label>
        </div>
      </div>
      <p class="new-task">אין התחייבות לקבלת ההצעה או למתן קרדיט</p>
      <button class="theme-button-outline">שלח</button>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import RequestPlaylistCreated from './../components/modals/RequestPlaylistCreated.vue';
import SchoolSelector from './../components/SchoolSelector.vue';

export default {
  data() {
    return {
      school: null,
      city: '',
      question_1: '',
      question_2: '',
      question_3: '',
      additional: false,
      comment: '',
      credit: false,
    };
  },
  methods: {
    add(e) {
      e.preventDefault();
      axios.post('/playlist/request', {
        id_school: this.school ? this.school.id_school : null,
        question_1: this.question_1,
        question_2: this.question_2,
        question_3: this.question_3,
        additional: this.additional,
        comment: this.comment,
        city: this.city,
        credit: this.credit,
      }).then(res => {
        this.$modal.show(RequestPlaylistCreated, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        });
        this.$router.replace('/playlist');
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
    padding-right: 5px;
  }
  .inputs {
    padding: 0 20px;
    padding-bottom: 30px;
    .small {
      font-size: 14px;
    }
    .section {
      p {
        margin: 0;
        margin-top: 15px;
      }
      input[typer="text"], textarea {
        font-size: 16px;
      }
      textarea {
        height: 80px;
        resize: none;
        vertical-align: middle;
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
    .additional {
      border-top: 2px solid #fff;
      margin: 0 -20px;
      margin-top: 15px;
      padding: 0 20px;
      padding-bottom: 20px;
      .sec {
        margin-top: 15px;
      }
    }
    .check-credit {
      input {
        vertical-align: middle;
      }
      span {
        font-size: 12px;
      }
    }
  }
  .theme-button-outline {
    width: 100%;
    display: block;
    margin: 0 auto;
  }
  .credit {
    padding-top: 10px;
    font-size: 12px;
    input {
      margin-left: 10px;
    }
  }
  .new-task {
    margin-top: 0;
    font-size: 14px;
  }
}

</style>
