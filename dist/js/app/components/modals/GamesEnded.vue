<template lang="html">
  <div class="modal-default" dir="rtl">
    <i class="main-icon icon ion-md-alert"></i>
    <p class="main-text">
      הוצגו כל המשחקים המתאימים להגדרות שלך
    </p>
    <button
      @click="$emit('close')"
      class="yes theme-button-outline"
    >הצג את המשחקים שוב</button>
    <button
      @click="goToOptions"
      class="nope theme-button-outline"
    >שנה הגדרות</button>
  </div>
</template>

<script>
export default {
  props: {
    toOptions: {
      type: Function,
    }
  },
  methods: {
    goToOptions() {
      this.toOptions().then(() => {
        this.$emit('close');
      });
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
