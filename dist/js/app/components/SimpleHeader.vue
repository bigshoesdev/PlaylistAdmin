<template lang="html">
  <header dir="ltr" :class="{ inverse: inverse }">
    <button v-if="!button" class="back" @click="back">
      <i class="icon ion-md-arrow-back"></i>
    </button>
    <button class="custom-back" dir="rtl" v-else @click="$emit('back')">
      {{ button }}
    </button>
    <slot dir="rtl" name="title"></slot>
    <slot name="additional"></slot>
  </header>
</template>

<script>
export default {
  props: {
    route: {},
    inverse: {
      default: false,
      type: Boolean,
    },
    button: {},
  },
  methods: {
    back() {
      this.$root.animation = 'slide-back';
      if(this.route) {
        this.$router.push(this.route);
      } else {
        this.$router.go(-1);
      }
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

header {
  padding: 15px 10px;
  text-align: center;
  color: #fff;
  height: 80px;
  position: relative;
  &.inverse {
    color: $clr-wet;
    .back, .custom-back {
      color: $clr-wet;
    }
  }
  .back, .custom-back {
    width: 30px;
    height: 30px;
    position: absolute;
    font-size: 20px;
    left: 10px;
    top: 15px;
    padding: 0;
    line-height: 30px;
    color: #fff;
  }
  .custom-back {
    font-size: 16px;
    width: auto;
  }
}

</style>
