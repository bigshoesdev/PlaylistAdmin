<template lang="html">
  <div class="modal-success">
    <i class="main-icon icon ion-md-checkmark"></i>
    <p class="main-text">השינוי נשמר בהצלחה</p>
    <button @click="$emit('close')" class="yes theme-button-outline">אישור</button>
  </div>
</template>
