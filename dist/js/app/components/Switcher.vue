<template lang="html">
  <div class="switch theme-input" dir="rtl">
    <button
      type="button"
      @click="$emit('input', item.value)"
      v-for="(item, i) in values"
      :class="{ selected: item.value == value }"
    >{{ item.name }}</button>
  </div>
</template>

<script>
export default {
  props: {
    values: {
      type: Array,
    },
    value: {},

  },
  data() {
    return {

    };
  },
  methods: {

  },

}
</script>

<style lang="scss" scoped>

.switch {
  display: flex;
  flex-direction: row;
  align-items: stretch;
  justify-content: space-between;
  padding: 5px;
  button {
    flex-grow: 1;
    width: 0;
    color: #000;
    border-radius: 6px;
    &.selected {
      background: #FF7384;
      color: #fff;
    }
  }
}

</style>
