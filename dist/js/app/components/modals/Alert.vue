<template lang="html">
  <div class="modal-success" dir="rtl">
    <p class="main-text">{{ text }}</p>
    <button @click="close" class="yes theme-button-outline">אישור</button>
  </div>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
    }
  },
  methods: {
    close() {
      this.$emit('close');
    }
  }
}
</script>

<style lang="scss" scoped>

.modal-success {
  padding-top: 30px;
  padding-bottom: 30px;
}

</style>
