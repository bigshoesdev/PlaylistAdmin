<template lang="html">
  <main dir="rtl">
    <simple-header route="/main">
      <span slot="title">מעקב התקדמות הילד/ה</span>
      <span slot="additional">
        <sidebar-toggler/>
      </span>
    </simple-header>
    <div class="nav">
      <ul>
        <li>
          <router-link to="/events">
            <i class="icon ion-md-radio-button-on"></i>
            קהילה משחקת
          </router-link>
        </li>
        <li>
          <router-link to="/notifications">
            <i class="icon ion-md-radio-button-on"></i>
            מעקב התקדמות חודשי
          </router-link>
        </li>
        <li>
          <router-link to="/parent-categories">
            <i class="icon ion-md-radio-button-on"></i>
            הגדרת המתנות
          </router-link>
        </li>
        <li>
          <router-link to="/parent-likes">
            <i class="icon ion-md-radio-button-on"></i>
            דו”ח מעקב
          </router-link>
        </li>
      </ul>
    </div>
    <section>
      <p>
        סה"כ מתנות שיפור <br>
        כישורי חיים שנצברו:
        {{ count }}
      </p>
      <button @click="openColors" class="colors">
        <div class="prefix">
          <img src="img/parent-colors.svg">
        </div>
        <span>מקרא</span>
      </button>
      <div class="categories">
        <div v-for="item in categories" class="item">
          <img :src="item.img_url" alt="">
          <div class="point" :style="{ backgroundColor: getSectionById(item.id_section).color }"></div>
          <div class="name">
            {{ item.name }}
          </div>
          <div v-if="item.count > 0" class="count">
            {{ item.count }}
          </div>
        </div>
      </div>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
import Colors from './../components/modals/Colors.vue';

export default {
  data() {
    return {
      sections: [],
      categories: [],
    };
  },
  methods: {
    openColors() {
      this.$modal.show(Colors, {
        data: this.sections
      }, {
        adaptive: true,
        height: 'auto',
        width: 300,
      });
    },
    sync() {
      axios.get('/playlist/category/sections').then(res => {
        this.sections = res.data;

        axios.get('/parent/get-stats').then(res => {
          this.categories = res.data.sort((a, b) => {
            if(a.count > b.count) {
              return -1;
            } else {
              return 1;
            }
          });
        });
      });
    },
    getSectionById(id) {
      var item = this.sections.find(n => n.id_section == id);

      if(!item) {
        return { color: 'transparent' }
      } else {
        return item
      }
    }
  },
  components: {
    SimpleHeader,
    SidebarToggler
  },
  computed: {
    count() {
      var count = 0;
      this.categories.forEach(n => {
        count += n.count;
      });
      return count.toLocaleString('en-IN');
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
  background: $clr-wet;
  color: #fff;
  .colors {
    position: absolute;
    left: 20px;
    top: 10px;
    height: 30px;
    border-radius: 5px;
    background: transparent;
    border: 1px solid #fff;
    color: #fff;
    text-align: right;
    padding-left: 40px;
    background: rgba(255, 255, 255, 0.2);
    .prefix {
      position: absolute;
      left: 0;
      top: 0;
      height: 28px;
      width: 30px;
      background: #fff;
      border-radius: 4px 0 0 4px;
      text-align: center;
      line-height: 28px;
    }
  }
  .nav {
    padding: 20px;
    border-bottom: 1px solid #fff;
    padding-top: 0;
    a {
      color: #fff;
      font-size: 20px;
    }
    ul {
      margin: 0;
    }
    i {
      margin-left: 10px;
    }
  }
  section {
    position: relative;
    p {
      margin: 0;
      font-size: 19px;
      padding: 0 20px;
      padding-top: 10px;
    }
  }
  .categories {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
    padding: 20px;
    padding-top: 40px;
    .item {
      width: 100px;
      border-radius: 10px;
      background: #fff;
      color: #000;
      height: 120px;
      text-align: center;
      padding-top: 20px;
      margin-bottom: 30px;
      position: relative;
      .point {
        position: absolute;
        right: 5px;
        top: 5px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
      }
      img {
        display: block;
        margin: 0 auto;
        height: 40px;
        margin-bottom: 10px;
      }
      .name {
        line-height: 100%;
      }
      .count {
        margin: 0;
        line-height: 100%;
        font-size: 14px;
      }
    }
  }
}

</style>
