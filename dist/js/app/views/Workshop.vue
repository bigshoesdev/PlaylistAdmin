<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        סדנאות משחקים
      </span>
    </simple-header>
    <section dir="rtl">
      <div class="item" v-for="item in items">
        <p class="title">{{ item.name }}</p>
        <div class="bg-img" :style="{ backgroundImage: `url(${item.img})` }"></div>
        <p class="content" v-html="item.text"></p>
        <div class="btn-container">
          <router-link :to="{ path: '/workshop/' + item.id_workshop }">
            <button class="theme-button-outline enter">המשך</button>
          </router-link>
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
      items: [],
    };
  },
  methods: {
    sync() {
      axios.get('/workshop/select').then(res => {
        res.data = res.data.map(n => {
          n.text = n.text.replace(/\n/g, '<br>');
          return n;
        });
        this.items = res.data;
      });
    }
  },
  components: {
    SimpleHeader
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
  .item {
    padding: 20px;
    .title {
      font-size: 22px;
      font-weight: bold;
    }
    .bg-img {
      max-width: 350px;
      width: 100%;
      height: 160px;
      margin: 0 auto;
      border-radius: 10px;
    }
    .btn-container {
      text-align: left;
    }
    .enter {
      background: #E36574;
    }
  }
}

</style>
