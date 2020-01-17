<template lang="html">
  <div class="modal-default" dir="rtl">
    <i class="main-icon icon ion-md-close"></i>
    <p class="main-text">
      כדי לקבל משחקים עליך לבחור מתנות
    </p>
    <button
      @click="toParents"
      class="yes theme-button-outline"
    >בחר מתנות</button>
    <button
      @click="$emit('close')"
      class="nope theme-button-outline"
    >לא עכשיו</button>
  </div>
</template>

<script>
export default {
  methods: {
    toParents() {
      this.$router.push({ name: 'parent-categories' });
      this.$emit('close');
    }
  }
}
</script>
