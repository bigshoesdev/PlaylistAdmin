<template lang="html">
  <main>
    <simple-header route="/main">
      <span slot="title">
        בחר עבור מי המשחק
      </span>
    </simple-header>
    <div class="sm-cont">
      <div class="links">
        <router-link class="child" to="/playlist/mode">
          משחקים לילדים
          <img src="img/playlist-section-2.svg" alt="">
        </router-link>
        <router-link class="group" to="/playlist/options/family">
          משחקים למשפחה
          <img src="img/playlist-section-3.svg" alt="">
        </router-link>
        <router-link class="parents" to="/playlist/options/adult">
          משחקים למבוגרים
          <img src="img/playlist-section-1.svg" alt="">
        </router-link>
      </div>
    </div>

  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  components: {
    SimpleHeader
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  color: #fff;
  .links {
    display: flex;
    align-items: stretch;
    justify-content: flex-start;
    flex-direction: column;
    padding: 0 3px;
    a {
      margin: 3px;
      padding: 20px;
      border-radius: 10px;
      color: #fff;
      text-align: center;
      min-height: 200px;
      img {
        display: block;
        margin: 0 auto;
      }
      &.child {
        background: #0BEF59;
      }
      &.group {
        background: #FBE15D;
      }
      &.parents {
        background: #29AAD2;
      }
    }
  }
}

</style>
