<template lang="html">
  <main>
    <simple-header route="/main">
      <span slot="title">חיפוש משחק</span>
      <span slot="additional">
        <sidebar-toggler/>
      </span>
    </simple-header>
    <div class="search">
      <input type="text" v-model="query" @input="onSearch" placeholder="שם המשחק">
      <i class="icon ion-md-search"></i>
    </div>
    <div class="found">
      <router-link
        v-for="item in data"
        :to="{ name: 'playlist', params: { id_playlist: item.id_playlist } }"
        class="item"
        :key="item.id_playlist"
      >
        {{ item.name }}
      </router-link>
    </div>
    <div class="request-wrapper">
      <router-link class="request" to="/requests/playlist">
        ?יש לך משחק חדש לספר לנו עליו
      </router-link>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
export default {
  data() {
    return {
      data: [],
      query: '',
      loading: false,
      ended: false,
      page: 0,
    };
  },
  components: {
    SimpleHeader,
    SidebarToggler,
  },
  methods: {
    sync(update = false) {
      this.loading = true;
      axios.post('/playlist/search', {
        str: this.query,
        page: this.page,
      }).then(res => {
        this.loading = false;
        if(update) {
          this.data = res.data.result;
        } else {
          this.data.push(...res.data.result);
        }
        this.ended = res.data.ended;
      });
    },
    onSearch() {
      this.page = 0;
      this.sync(true);
    },
    onScroll() {
      var doc = document.documentElement;
      var screen = doc.clientHeight;
      var top = ((window.pageYOffset || doc.scrollTop) - (doc.clientTop || 0));
      if(top >= (doc.scrollHeight - (screen * 1.5)) && !this.loading && !this.ended) {
        this.page++;
        this.sync();
      }
    }
  },
  created() {
    this.sync(true);
  },
  mounted() {
    document.addEventListener('scroll', this.onScroll);
  },
  destroyed() {
    document.removeEventListener('scroll', this.onScroll);
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .search {
    width: 300px;
    height: 40px;
    border-radius: 20px;
    overflow: hidden;
    margin: 0 auto;
    background: #fff;
    position: relative;
    input {
      width: 100%;
      height: 100%;
      border: 0;
      background: transparent;
      padding: 0 20px;
      padding-left: 40px;
      &::placeholder {
        text-align: center;
        color: $clr-blue;
        opacity: 1;
      }
    }
    i {
      position: absolute;
      left: 20px;
      font-size: 18px;
      height: 100%;
      line-height: 44px;
      color: $clr-blue;
      top: 0;
    }
  }
  .found {
    border-top: 2px solid #4F4949;
    border-bottom: 2px solid #4F4949;
    margin: 60px 0;

    .item {
      width: 100%;
      display: block;
      background: #fff;
      color: $clr-wet;
      direction: rtl;
      padding: 15px 30px;
      &:not(:first-child) {
        border-top: 1px solid #4F4949;
      }
    }
  }
  .request-wrapper {
    position: fixed;
    bottom: 0;
    left: 0;
    text-align: center;
    background: $clr-blue;
    border-top: 2px solid $clr-wet;
    width: 100%;
    padding: 20px 0;
  }
  .request {
    direction: rtl;
    color: $clr-wet;
    text-decoration: underline;
  }
}

</style>
