<template lang="html">
  <div class="theme-input">
    <button type="button" v-for="item in value">
      <div class="badge">
        {{ item[labels] }}
      </div>
      <i @click="remove(item)" class="icon ion-md-close"></i>
    </button>
  </div>
</template>

<script>
export default {
  props: {
    labels: {
      default: 'label',
    },
    values: {
      default: 'value',
    },
    value: {
      required: true,
      type: Array,
    }
  },
  methods: {
    remove(item) {
      this.$emit('input', this.value.filter(n => item[this.values] != n[this.values]));
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.theme-input {
  padding: 4px 10px;
  display: flex;
  flex-direction: row;
  flex-wrap: wrap;
  button {
    display: flex;
    padding: 0 5px;
    line-height: 30px;
    margin: 5px 0;
    &:hover {
      color: inherit;
    }
    i {
      vertical-align: middle;
      &::before {
        vertical-align: middle;
      }
    }
    .badge {
      display: inline-block;
      font-size: 15px;
      font-weight: bold;
      height: 30px;
      border-radius: 15px;
      background: #FF7384;
      margin-left: 5px;
      border: 1px solid $clr-wet;
      line-height: 30px;
      padding: 0 10px;
      color: #fff;
    }
  }
}

</style>
