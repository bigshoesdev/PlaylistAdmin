<template lang="html">
  <div class="multi-selector">
    <button
      type="button"
      @click="select(item)"
      v-for="item in items"
      :class="{ selected: isArray ? value.includes(item[values]) : value == item[values] }"
    >{{ item[labels] }}</button>
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
    items: {
      type: Array,
      required: false,
    },
    value: {
      required: true,
    }
  },
  methods: {
    select(item) {
      if(this.isArray) {
        var tmp = [ ...this.value ];
        if(tmp.includes(item[this.values])) {
          this.$emit('input', tmp.filter(n => item[this.values] != n));
        } else {
          tmp.push(item[this.values]);
          this.$emit('input', tmp);
        }
      } else {
        if(this.value == item[this.values]) {
          this.$emit('input', null);
        } else {
          this.$emit('input', item[this.values]);
        }


      }
    },
  },
  computed: {
    isArray() {
      return this.value instanceof Array;
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.multi-selector {
  button {
    border: 1px solid $clr-wet;
    color: $clr-wet;
    background: #fff;
    height: 26px;
    padding: 0 10px;
    line-height: 26px;
    font-size: 15px;
    margin: 5px;
    border-radius: 13px;
    &.selected {
      color: #fff;
      background: #FF7384;
    }
  }
}

</style>
