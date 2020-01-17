<template lang="html">
  <div class="modal-default" dir="rtl">
    <i class="main-icon icon ion-md-close"></i>
    <p class="main-text">
      כדי לקבל משחקים עליך לבחור מיקום
    </p>
    <button
      @click="$emit('close')"
      class="yes theme-button-outline"
    >הבנתי</button>
  </div>
</template>
