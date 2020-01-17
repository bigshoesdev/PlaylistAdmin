<template lang="html">
  <main>
    <img src="img/white-logo.svg" alt="">
    <div class="title fugaz">
      LOADING
      <span v-for="dot in dots">. </span>
    </div>
  </main>
</template>

<script>
export default {
  data() {
    return {
      interval: null,
      dots: [],
    };
  },
  mounted() {
    this.interval = setInterval(() => {
      if(this.dots.length >= 3) {
        this.dots = [];
      } else {
        this.dots.push(null);
      }
    }, 100);
  },
  destroyed() {
    clearInterval(this.interval);
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
  width: 100%;
  height: 100%;
  img {
    margin-bottom: 20px;
  }
  .title {
    font-size: 20px;
    color: #fff;
  }
}

</style>
