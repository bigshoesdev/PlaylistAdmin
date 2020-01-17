<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title">המועדפים שלי</span>
      <span slot="additional">
        <sidebar-toggler inverse/>
      </span>
    </simple-header>
    <div class="buttons">
      <button
        @click="selected = 'playlist'"
        :class="{ selected: selected == 'playlist' }"
      >משחקים</button>
      <button
        @click="selected = 'shootlist'"
        :class="{ selected: selected == 'shootlist' }"
      >שאלות</button>
    </div>
    <div class="table">
      <div class="row" v-for="item in data[selected]">
        <button class="delete" @click="dislike(item)">
          <i class="icon ion-md-close-circle-outline"></i>
        </button>
        <span @click="goTo(item)">{{ getName(item) }}</span>
      </div>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import SidebarToggler from './../components/SidebarToggler.vue';
export default {
  data() {
    return {
      selected: 'playlist',
      data: {
        playlist: [],
        shootlist: [],
      }
    };
  },
  components: {
    SimpleHeader,
    SidebarToggler,
  },
  methods: {
    getName(item) {
      if(this.selected == 'shootlist') {
        if(this.$store.state.user.gender == 'fem') {
          return item.content_fem;
        } else {
          return item.content_man;
        }
      } else {
        return item.name;
      }
    },
    goTo(item) {
      if(this.selected == 'shootlist') {
        this.$router.push({ name: 'shootlist', params: {
          id_shootlist: item.id_shootlist
        }});
      } else {
        this.$router.push({ name: 'playlist', params: {
          id_playlist: item.id_playlist
        }});
      }
    },
    sync() {
      axios.get('shootlist/liked').then(res => {
        this.data.shootlist = res.data;
      });
      axios.get('playlist/liked').then(res => {
        this.data.playlist = res.data;
      });
    },
    dislike(item) {
      if(this.selected === 'playlist') {
        this.data.playlist = this.data.playlist.filter(n => n.id_playlist !== item.id_playlist);
        axios.delete('playlist/' + item.id_playlist + '/dislike');
      } else {
        this.data.shootlist = this.data.shootlist.filter(n => n.id_shootlist !== item.id_shootlist);
        axios.delete('shootlist/' + item.id_shootlist + '/dislike');
      }
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
  .buttons {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    button {
      flex-grow: 1;
      height: 40px;
      line-height: 40px;
      color: $clr-wet;
      font-size: 22px;
      padding: 0;
      display: inline-block;
      margin: 0;
      &.selected {
        border-bottom: 2px solid #fff;
      }
    }
  }
  .row {
    min-height: 60px;
    position: relative;
    padding: 10px 30px;
    direction: rtl;
    padding-left: 80px;
    &:not(:first-child) {
      border-top: 2px dashed $clr-wet;
    }
    button {
      position: absolute;
      left: 30px;
      width: 40px;
      height: 40px;
      color: #fff;
      padding: 0;
      i {
        vertical-align: middle;
        font-size: 40px;
      }
    }
  }
}

</style>
