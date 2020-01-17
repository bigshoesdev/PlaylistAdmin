<template lang="html">
  <main dir="rtl">
    <simple-header inverse button="שמור" @back="save">
      <span slot="title">מתנות מהמשחק</span>
    </simple-header>
    <p class="top-text heebo">
      אתם מוזמנים כהורים לבחור אלו כישורים ומיומנויות אתם רוצים לחזק אצל ילדיכם. <span class="fugaz">playlist</span> תציג לילדים משחקים חברתיים שיפתחו אצלם את היכולות שתסמנו כאן. על מנת לדייק וליעל - מומלץ לבטל את הסימון של 'בחר הכל', ואז לבחור את הכישורים החשובים בעיניכם במיוחד עבור ילדיכם. בהמשך תוכלו לגוון ולשנות יעדי התפתחות עבור ילדיכם
    </p>
    <section>
      <div
        v-for="(section, i) in data"
        class="section"
        :class="{ opened: opened.includes(section.id_section) }"
        v-if="section.categories.length"
      >
        <div class="stripe" :style="{ backgroundColor: section.color }">
          <div class="icon">
            <img :src="'img/parent-cat-' + (i + 1) + '.svg'">
          </div>
          <span class="name">{{ section.name }}</span>
          <div class="left">
            <label>
              <span class="heebo">בחר הכל</span>
              <input
                @change="selectAll(section)"
                :value="section.id_section"
                class="theme-checkbox"
                v-model="check_indicator"
                type="checkbox"
              >
            </label>
            <button @click="open(section.id_section)" class="collapse">
              <i class="icon ion-md-arrow-dropdown"></i>
            </button>
          </div>
        </div>
        <div v-if="opened.includes(section.id_section)" class="categories">
          <button
            @click="select(category.id_category, section.id_section)"
            v-for="category in section.categories"
            class="category"
            :class="{ selected: selected.includes(category.id_category) }"
          >{{ category.name }}</button>
        </div>
      </div>
    </section>
    <p class="bottom-text">
      באפשרותכם לחסום את הגישה של הילדים
      לדף זה, ולמנוע שינוי יעדי ההתפתחות שהצבתם עבורם
      <button class="end" @click="forgot">שלח לי קוד למייל</button>
    </p>

  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      data: [],
      opened: [],
      selected: [],
      check_indicator: [],
    };
  },
  components: {
    SimpleHeader,
  },
  methods: {
    sync() {
      axios.get('/playlist/category/sections/full').then(res => {
        this.data = res.data.sections;
        this.opened.push(this.data[0].id_section);
        this.selected = res.data.selected;
        this.$nextTick(this.processCheckStates);
      });
    },
    open(id) {
      if(this.opened.includes(id)) {
        this.opened = this.opened.filter(n => n != id)
      } else {
        this.opened.push(id);
      }
    },
    processCheckStates() {

      // iterate through all sections
      this.data.forEach(section => {
        // iterate through all categories in sections and if it didnt find any
        // of category - removes checks
        var fulled = true;
        section.categories.forEach(n => {
          if(!this.selected.includes(n.id_category)) {
            fulled = false;
          }
        });
        this.check_indicator = this.check_indicator.filter(n => section.id_section != n);

        if(fulled) {
          this.opened.push(section.id_section);
          this.check_indicator.push(section.id_section);
        }
      });
    },
    select(id_category, id_section) {
      if(this.selected.includes(id_category)) {
        this.selected = this.selected.filter(n => n != id_category);
      } else {
        this.selected.push(id_category);
      }

      this.processCheckStates();
    },
    selectAll(section) {
      if(this.check_indicator.includes(section.id_section)) {
        section.categories.forEach(n => {
          if(!this.selected.includes(n.id_category)) {
            this.selected.push(n.id_category);
          }
        });
      } else {
        section.categories.forEach(n => {
          this.selected = this.selected.filter(z => z != n.id_category);
        });
      }
    },
    save() {
      axios.post('/playlist/user/category', {
        categories: this.selected,
      }).then(res => {
        this.$router.push('/parent');
        this.$store.dispatch('updateUserData');
      }).catch(res => {
        this.$store.commit('alert', res.data.error);
      });
    },
    forgot() {
      this.$router.push({ name: 'restore-parent-code', params: { to: this.to } });
      axios.put('/parent/code/first');
    }
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .top-text {
    color: #fff;
    padding: 30px;
    font-size: 15px;
    line-height: 200%;
  }
  .section {
    padding-bottom: 10px;
    .stripe {
      .icon {
        img {
          width: 25px;
        }
      }
      background: $clr-wet;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      padding: 0 20px;
      height: 40px;
      .name {
        font-size: 20px;
      }
      .left {
        font-size: 16px;
      }
      input {
        vertical-align: middle;
        margin-top: -4px;
        margin-right: 4px;
      }
      label {
        margin-left: 20px;
      }
      button {
        color: #fff;
        height: 30px;
        width: 30px;
        line-height: 30px;
        padding: 0;
        font-size: 20px;
        transform: rotate(90deg);
        i::before {
          vertical-align: middle;
        }
      }
    }
    &.opened {
      .stripe {
        button {
          transform: rotate(0deg);
        }
      }
    }
    .categories {

      .category {
        background: #fff;
        border: 1px solid #BCB9B9;
        border-radius: 15px;
        height: 30px;
        width: auto;
        padding: 0 20px;
        color: #545454;
        margin: 10px;
        font-size: 15px;
        &.selected {
          background: #FF7384;
          color: #fff;
          border-color: transparent;
        }
      }
    }
  }
  .bottom-text {
    padding: 30px;
    text-align: center;
    button {
      display: block;
      margin: 0 auto;
      margin-top: 20px;
      border: 2px solid #fff;
      color: #fff;
      height: 40px;
      border-radius: 20px;
      padding: 0 20px;
    }
  }
}

</style>
