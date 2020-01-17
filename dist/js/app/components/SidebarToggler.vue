<template lang="html">
  <button class="toggler" @click="$store.state.sidebar.opened = true" :class="{ inverse: inverse }">
    <i class="icon ion-md-menu"></i>
  </button>
</template>

<script>
export default {
  props: {
    inverse: {
      default: false,
      type: Boolean,
    }
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.toggler {
  color: #fff;
  top: 15px;
  height: 30px;
  right: 10px;
  width: 30px;
  position: absolute;
  font-size: 26px;
  padding: 0;
  &.inverse {
    color: $clr-wet;
  }
}

</style>
