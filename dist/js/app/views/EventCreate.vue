<template lang="html">
  <main dir="rtl">
    <simple-header inverse>
      <span slot="title"><span class="fugaz">FUN ZONE</span> יצירת</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <section>
      <div class="select-img">
        <p>בחר אייקון לאירוע</p>
        <div class="slide">
          <button
            v-for="i in [ 1, 2, 3, 4, 5, 6 ]"
            :class="{ selected: img == i }"
            class="item bg-img"
            :style="{ backgroundImage: `url(img/funzone/${i}.svg)` }"
            @click="select(i)"
          ></button>
        </div>
      </div>
      <form class="input" @submit="create" dir="rtl">
        <div class="section">
          <p>
            עיר \ ישוב
          </p>
          <input required v-model="geo" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
            מיקום מדויק
          </p>
          <input required v-model="geo_full" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
             תאריך
          </p>
          <input required v-model="date" type="date" class="theme-input">
        </div>
        <div class="section">
          <p>
             שעה
          </p>
          <input required v-model="time" type="time" class="theme-input">
        </div>

        <div class="section">
          <p>
             גילאים
          </p>
          <vue-slider ref="secondslider" :min="5" :max="99" v-model="age"></vue-slider>
        </div>

        <div class="section">
          <p>
             כמות משתתפים עד
          </p>
          <vue-slider :min="1" ref="firstslider" v-model="max_users"></vue-slider>
        </div>

        <div class="section">
          <p>שם איש קשר</p>
          <input v-model="name" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
            מספר טלפון לאיש קשר
            <span class="small">(לא חובה)</span>
          </p>
          <input v-model="phone" v-mask="'###-#######'" type="tel" class="theme-input">
        </div>
        <div class="section">
          <p>
            סיסמה להצטרפות לאירוע
            <span class="small">(כמו בחסמבה...)</span>
          </p>
          <input v-model="pass" type="text" class="theme-input">
        </div>
        <div class="section">
          <p>
            טקסט חופשי לגבי המפגש
          </p>
          <input v-model="comment" type="text" class="theme-input">
        </div>

        <!-- MULTI SELECT -->
        <div class="section">
          <p>מוזמנים לבוא כי יהיה...</p>
          <badge-selector @input="syncToSelector" v-model="processed_categories"/>
          <small class="multi-small">ניתן לבחור יותר מאפשרות אחת</small>
          <multi-selector
            v-model="selected"
            values="id_category"
            labels="name"
            :items="categories"
            @input="processCategories"
          />
        </div>
        <button class="end theme-button-outline">צור <span>FUN ZONE</span> </button>
      </form>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import EventCreated from './../components/modals/EventCreated.vue';
import BadgeSelector from './../components/BadgeSelector.vue';
import MultiSelector from './../components/MultiSelector.vue';
import VueSlider from 'vue-slider-component';

export default {
  data() {
    return {
      categories: [],
      selected: [],
      processed_categories: [],
      name: '',
      geo: '',
      geo_full: '',
      date: '',
      time: '',
      phone: '',
      pass: '',
      img: 1,
      max_users: 20,
      age: [5, 12],
      comment: '',
    };
  },
  methods: {
    processCategories() {
      this.processed_categories = this.categories.map(n => {
        if(this.selected.includes(n.id_category)) {
          return {
            value: n.id_category,
            label: n.name,
          };
        }
      }).filter(n => n);
    },
    syncToSelector() {
      this.selected = this.processed_categories.map(n => {
        return n.value;
      });
    },
    getCategories() {
      axios.get('/event/categories').then(res => {
        this.categories = res.data;
      });
    },
    create(e) {
      e.preventDefault();
      axios.post('/event', {
        comment: this.comment,
        geo_full: this.geo_full,
        name: this.name,
        geo: this.geo,
        date: this.date,
        time: this.time,
        phone: this.phone,
        pass: this.pass,
        age: this.age,
        max_users: this.max_users,
        img: this.img,
        categories: this.selected,
      }).then(res => {
        this.$router.push('/events');
        this.$modal.show(EventCreated, {}, {
          adaptive: true,
          height: 'auto',
          width: 300,
        });
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    },
    select(i) {
      this.img = i;
    },
    updateSliders() {
      // don't touch this, it's to force a slider bug
      var sliders = [
        this.$refs.firstslider,
        this.$refs.secondslider
      ];
      sliders[0].$refs.elem && (sliders[0].getStaticData(), sliders[0].computedFixedValue(), sliders[0].setPosition(0));
      sliders[1].$refs.elem && (sliders[1].getStaticData(), sliders[1].computedFixedValue(), sliders[1].setPosition(0));
    }
  },
  components: {
    SimpleHeader,
    SidebarToggler,
    BadgeSelector,
    MultiSelector,
    VueSlider,
  },
  created() {
    this.getCategories();
    app.addEventListener('route-anim-end', this.updateSliders);
  },
  mounted() {
    console.log('mounted');
  },
  destroyed() {
    app.removeEventListener('route-anim-end', this.updateSliders);
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .select-img {
    p {
      padding: 0 20px;
      font-size: 15px;
    }
    .slide {
      width: 100%;
      overflow-x: auto;
      white-space: nowrap;
      padding: 0 10px;
      padding-left: 20px;
      .item {
        display: inline-block;
        background-color: #fff;
        background-size: 60% auto;
        width: 50px;
        height: 50px;
        border-radius: 10px;
        margin: 0 10px;
        &.selected {
          border: 2px solid $clr-wet;
        }
      }
    }
  }
  .input {
    padding: 20px;
    .section {
      p {
        margin-bottom: 0;
        .small {
          font-size: 14px;
        }
      }
      input {
        width: 100%;
      }
    }
    .end {
      display: block;
      margin: 0 auto;
      font-family: 'Fugaz One';
      margin-top: 20px;
      width: 100%;
    }
    .multi-small {
      color: #fff;
    }
  }
}

</style>
