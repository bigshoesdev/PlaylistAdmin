<template lang="html">
  <main dir="rtl">
    <simple-header class="header" :route="$route.params.section == 'all' ? '/main' : undefined ">
      <span slot="title" dir="rtl">
        הגדרות
        <span class="fugaz">PLAY list</span>
      </span>
      <button
        v-if="$route.params.section != 'all'"
        @click="$router.push(videoLink)"
        slot="additional"
        class="info"
      >
        <i class="icon ion-md-information-circle"></i>
        הסבר
      </button>
    </simple-header>
    <p class="top-text">
      אני מוגדר
      <button
        @click="$router.push('/playlist/sections')"
        class="top-link-but"
        v-if="$route.params.section == 'family'"
      >משחקים למשפחה</button>
      <button
        @click="$router.push('/playlist/sections')"
        class="top-link-but"
        v-else-if="$route.params.section == 'adult'"
      >משחקים מבוגרים</button>
      <button
        @click="$router.push('/playlist/sections')"
        class="top-link-but"
        v-else
      >משחקים לילדים</button>
    </p>
    <hr>
    <form @submit="onSubmit">
      <div class="app-inputs">
        <!-- <div v-if="[ 'family', 'all' ].includes($route.params.section)" class="app-section-half">
          <p>טווח גילאים</p>
          <div>
            מ
            <select v-model="age_from" class="theme-input">
              <option v-for="(n, i) in new Array(96)" :value="i + 4">{{ i + 4 }}</option>
            </select>
          </div>
          <div>
            עד
            <select v-model="age_to" class="theme-input">
              <option v-for="(n, i) in new Array(96)" :value="i + 4">{{ i + 4 }}</option>
            </select>
          </div>
        </div> -->
        <div class="app-section-half">
          <p>מספר המשתתפים</p>
          <select v-model="num_people" class="theme-input">
            <option v-for="(n, i) in new Array(8)" :value="i + 1">{{ i + 1 }}</option>
          </select>
        </div>
        <hr>
        <div class="app-section sw-sec">
          <p>רמת המשחקים</p>
          <switcher v-model="level" :values="[
            { value: 1, name: 'בכיף' },
            { value: 2, name: 'מאתגר' },
            { value: 3, name: 'מאתגר +' },
          ]"/>
        </div>
        <hr>
        <div class="app-section selector-sec">
          <p>
            מיקום המשחק
            <span class="small-white">ניתן לבחור יותר מאפשרות אחת</span>
          </p>
          <multi-selector
            v-model="gifts"
            :items="settings"
            labels="name"
            values="id_setting"
          ></multi-selector>
          <!-- <multi-selector v-model="gifts" v-if="$route.params.section == 'child'" :items="[
            { value: 1, label: 'בית ספר' },
            { value: 2, label: 'טבע' },
            { value: 3, label: 'בית' },
            { value: 4, label: 'חוץ' },
            { value: 5, label: 'הוראה-הדרכה' },
            { value: 6, label: 'משחקים של פעם' },
          ]"></multi-selector>
          <multi-selector v-model="gifts" v-else-if="$route.params.section == 'family'" :items="[
            { value: 1, label: 'ארוחה' },
            { value: 2, label: 'נסיעה' },
            { value: 3, label: 'משפחה מורחבת' },
            { value: 4, label: 'הורה-ילד' },
          ]"></multi-selector>
          <multi-selector v-model="gifts" v-else-if="$route.params.section == 'adult'" :items="[
            { value: 1, label: 'דייט' },
            { value: 2, label: 'גיבוש' },
            { value: 3, label: 'זוגיות' },
            { value: 4, label: 'היכרות' },
            { value: 5, label: 'חבר\'ה' },
          ]"></multi-selector> -->
        </div>
        <hr>
      </div>
      <div v-if="$route.params.section == 'child'" class="to-gifts">
        <router-link class="theme-a" to="/playlist/mode">שינוי מתנות מהמשחק</router-link>
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
import NoLocations from './../components/modals/NoLocations.vue';
import NoGifts from './../components/modals/NoGifts.vue';

export default {
  name: 'playlist-options',
  data() {
    return {
      level: 1,
      gifts: [],
      age_from: 4,
      age_to: 20,
      num_people: 3,
      settings: [],
      last_section: null,
    };
  },
  methods: {
    onSubmit(e) {
      e.preventDefault();

      if(!this.gifts.length) {
        this.$modal.show(NoLocations, {}, {
          height: 'auto',
          width: 300,
        });
        return;
      }

      if(this.$route.params.section == 'child' && !this.$store.state.user.gifts.length) {
        this.$modal.show(NoGifts, {}, {
          height: 'auto',
          width: 300,
        });
        return;
      }

      this.$router.push({
        name: 'playlist',
        params: {
          gifts: this.gifts,
          level: this.level,
          section: this.$route.params.section,
          num_people: this.num_people,
          // age_to: this.$route.params.section == 'family' ? this.age_to : undefined,
          // age_from: this.$route.params.section == 'family' ? this.age_from : undefined,
        },
      });
    }
  },
  computed: {
    videoLink() {
      if(this.$route.params.section == 'family') {
        return '/playlist/family/video';
      }
      else if(this.$route.params.section == 'child') {
        return '/playlist/children/video';
      }
      else if(this.$route.params.section == 'adult') {
        return '/playlist/adult/video';
      }
    }
  },
  components: {
    SimpleHeader,
    Switcher,
    MultiSelector,
    NoLocations,
    NoGifts,
  },
  created() {
    // axios.get('/playlist/user/selected').then(res => {
    //   this.categories = res.data.map(n => {
    //     return {
    //       value: n.id_category,
    //       label: n.name,
    //     };
    //   });
    //
    //   this.gifts = res.data.map(n => n.id_category);
    // });
  },
  activated() {
    if(this.last_section != this.$route.params.section) {
      this.gifts = [];
    }
    this.last_section = this.$route.params.section;
    axios.get('/playlist/settings/' + this.$route.params.section).then(res => {
      this.settings = res.data;
    });
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: #87D4EC;
  .top-text {
    color: #fff;
    font-size: 22px;
    margin: 0;
    padding: 0 15px;
    .top-link-but {
      color: $clr-wet;
      text-decoration: underline;
    }
  }
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
    margin-top: 15px;
  }
  .selector-sec {
    padding-bottom: 20px;
  }
  .sw-sec {
    padding-bottom: 20px;
  }
  .to-gifts {
    margin-top: 50px;
    text-align: center;
  }
  .small-white {
    position: absolute;
    left: 15px;
    color: #fff;
    font-size: 12px;
  }
}

</style>
