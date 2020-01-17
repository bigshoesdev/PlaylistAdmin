<template lang="html">
  <main>
    <simple-header class="header" route="/main">
      <span slot="title" dir="rtl">
        הגדרות
        <span class="fugaz">SHOOT list</span>
      </span>
      <button @click="$router.push('/shootlist/video')" slot="additional" class="info">
        <i class="icon ion-md-information-circle"></i>
        הסבר
      </button>
    </simple-header>
    <form @submit="onSubmit">
      <div class="app-inputs">
        <div class="app-section">
          <p>נוסח השאלות</p>
          <switcher v-model="gender" :values="[
            { value: 'man', name: 'זכר' },
            { value: 'fem', name: 'נקבה' },
          ]"/>
        </div>
        <div class="app-section-half">
          <p>גיל המשתתפים</p>
          <select v-model="for_type" class="theme-input">
            <option :value="1">מעורב</option>
            <option :value="2">ילדים</option>
            <option :value="3">בוגרים</option>
          </select>
        </div>
        <hr>
        <div class="app-section selector-sec">
          <p>
            סגנון השאלות
            <!-- <span class="small-white">מיקום המשחק</span> -->
          </p>
          <multi-selector v-model="gifts" :items="[
            {
              value: 3,
              label: 'נועז',
            },
            {
              value: 4,
              label: 'וואלה?!',
            },
            {
              value: 5,
              label: 'קליל',
            },
            {
              value: 2,
              label: 'טריוויה',
            },
            {
              value: 1,
              label: 'מי השבוע',
            },
          ]"></multi-selector>
        </div>
        <hr>
        <div class="app-section-half">
          <p>מספר המשתתפים</p>
          <select @change="renderNames" v-model="num" class="theme-input">
            <option :value="1">1</option>
            <option :value="2">2</option>
            <option :value="3">3</option>
            <option :value="4">4</option>
            <option :value="5">5</option>
            <option :value="6">6</option>
            <option :value="7">7</option>
            <option :value="8">8</option>
          </select>
        </div>
        <hr>
        <div class="names">
          <div class="app-section">
            <p>
              הכנס שם פרטי של המשתתפים
            </p>
          </div>
          <div v-for="(name, i) in names" class="app-section-half">
            <p>משתתף {{ i + 1 }}</p>
            <input placeholder="שם פרטי" v-model="names[i]" type="text" class="theme-input">
          </div>
        </div>
      </div>
      <button type="submit" class="next theme-button-outline">
        התחל לשחק
      </button>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import Switcher from './../components/Switcher.vue';
import MultiSelector from './../components/MultiSelector.vue';
export default {
  name: 'shootlist-options',
  data() {
    return {
      num: 4,
      gender: 'man',
      gifts: 1,
      names: [],
      for_type: 1,
    };
  },
  methods: {
    onSubmit(e) {
      e.preventDefault();
      this.$router.push({
        name: 'shootlist',
        params: {
          num: this.num,
          gender: this.gender,
          for_type: this.for_type,
          names: this.names.map((n, i) => {
            if(!n.length) {
              n = (i + 1) + ' גיימר';
            }
            return n;
          }),
          gifts: this.gifts,
        },
      });
    },
    renderNames() {
      // хз как оно работает , в будущем перепроверить , а то я не в состоянии был (код мой)
      if(this.num < this.names.length) {
        this.names = this.names.filter((n, i) => i < this.num);
      } else {
        for (var i = this.names.length; i < this.num; i++) {
          this.names.push('');
        }
      }
    },
  },
  components: {
    SimpleHeader,
    Switcher,
    MultiSelector,
  },
  created() {
    this.renderNames();
  }
}
</script>

<style lang="scss" scoped>

main {
  background: #87D4EC;
  .header {
    .info {
      width: 50px;
      position: absolute;
      font-size: 20px;
      right: 10px;
      top: 15px;
      padding: 0;
      line-height: 20px;
      color: #fff;
      text-align: center;
      transform: scale(0.9);
      i {
        font-size: 26px;
      }
    }
  }
  .next {
    height: 40px;
    width: 260px;
    border-radius: 20px;
    border: 2px solid #fff;
    color: #fff;
    display: block;
    margin: 0 auto;
    margin-top: 50px;
  }
  .selector-sec {
    padding-bottom: 20px;
  }
}

</style>
