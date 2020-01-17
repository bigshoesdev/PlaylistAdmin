<template lang="html">
  <div class="rating">
    <div class="theme-input" dir="ltr">
      <button
        @click="$emit('input', i + 1)"
        type="button"
        v-for="(n, i) in Array(max)"
        :class="{ selected: value == i + 1 }"
      >{{ i + 1 }}</button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    max: {
      type: Number,
    },
    value: {

    }
  },
  data() {
    return {

    };
  },
  methods: {
    select() {

    }
  },
  computed: {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.rating {
  .theme-input {
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    position: relative;
    &::before {
      position: absolute;
      left: 10px;
      top: 50%;
      height: 2px;
      width: calc(100% - 20px);
      content: '';
      background: $clr-wet;
    }
  }
  button {
    padding: 0;
    line-height: 16px;
    text-align: center;
    width: 20px;
    height: 20px;
    border-radius: 10px;
    background: #fff;
    border: 2px solid $clr-blue;
    font-size: 15px;
    z-index: 1;
    &.selected {
      transform: scale(1.5);
      border-color: $clr-wet;
    }
  }
}

</style>
