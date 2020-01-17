<template lang="html">
  <main>
    <simple-header :route="{ name: 'shootlist', params: $route.params.back }"></simple-header>
    <div class="slider" dir="rtl">
      <p>התורמים המובילים:</p>
      <div class="wrapper">
        <div v-for="item in top" class="item">
          <div class="avatar bg-img" :style="{ backgroundImage: `url(${item.img})` }"></div>
          <div class="info">
            <p class="first">{{ item.name }}</p>
            <p class="second">
              <span>{{ item.requests_count }}</span>
              משחקים נתרמו
            </p>
            <p class="third">
              <span>{{ item.saw }}</span>
              פעמים נצפו
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="rating" dir="rtl">
      <p>המשחקים שלי:</p>
      <div class="wrapper">
        <div class="first circle">
          {{ stats.sent }}
          <p>נשלחו</p>
        </div>
        <div class="second circle">
          {{ stats.verified }}
          <p>אושרו</p>
        </div>
        <div class="third circle">
          {{ stats.saw }}
          <p>שוחקו</p>
        </div>
      </div>
    </div>
    <router-link dir="rtl" class="end theme-button-outline" :to="{ name: 'requests-shootlist-add' }">
      הוסף שאלה!
    </router-link>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      top: [],
      stats: {},
    };
  },
  methods: {
    sync() {
      axios.get('/shootlist/requests/top').then(res => {
        this.top = res.data.users;
        this.stats = res.data.personal;
      });
    },
  },
  components: {
    SimpleHeader,
  },
  created() {
    console.log(this.$route.params);
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  color: #fff;
  background: $clr-wet;
  .slider {
    > p {
      padding: 0 20px;
    }
    .wrapper {
      width: 100%;
      overflow-x: auto;
      white-space: nowrap;
      padding: 0 10px;
      padding-left: 20px;
      .item {
        position: relative;
        width: 300px;
        height: 120px;
        background: #107C9E;
        border-radius: 5px;
        display: inline-block;
        margin: 0 10px;
        .avatar {
          width: 60px;
          height: 60px;
          border-radius: 50%;
          top: 10px;
          right: 10px;
          position: absolute;
        }
        .info {
          padding-right: 80px;
          width: 100%;
          p {
            margin: 0;
            font-size: 17px;
            &.first {
              margin-bottom: 10px;
              margin-top: 5px;
            }
            span {
              font-weight: bold;
              font-size: 20px;
            }
          }
        }
      }
    }
  }
  .rating {
    padding-top: 30px;
    p {
      padding: 0 20px;
    }
    .wrapper {
      display: flex;
      flex-direction: row;
      align-items: stretch;
      justify-content: center;
      padding-bottom: 50px;
      .circle {
        margin: 0 10px;
        background: #107C9E;
        line-height: 100px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        text-align: center;
        font-size: 40px;
        position: relative;
        color: #fff;
        p {
          margin: 0;
          margin-top: 10px;
          line-height: 100%;
          top: 100%;
          left: 50%;
          transform: translateX(-50%);
          position: absolute;
          font-size: 20px;
        }
        @media(max-width: 360px) {
          line-height: 80px;
          width: 80px;
          height: 80px;
        }
      }
    }
  }
  .end {
    display: block;
    margin: 0 20px;
    background: #E36574;
    margin-top: 50px;
  }
}

</style>
