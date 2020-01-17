<template lang="html">
  <button type="button" @click="selectSchool" class="theme-input">
    <template v-if="!value">
      חיפוש
    </template>
    <template v-else>{{ value.name }}, {{ value.city }}</template>
  </button>
</template>

<script>
import SelectSchool from './modals/SelectSchool.vue';

export default {
  props: {
    value: {
      required: true,
    }
  },
  methods: {
    selectSchool() {
      this.$modal.show(SelectSchool, {
        callback: (data) => {
          this.$emit('input', data);
        },
      }, {
        adaptive: true,
        height: 'auto',
        width: 300,
      });
    },
  }
}
</script>

<style lang="scss" scoped>



</style>
