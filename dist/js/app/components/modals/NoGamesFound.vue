<template lang="html">
  <div class="modal-success">
    <i class="main-icon icon ion-md-sad"></i>
    <p class="main-text">לא נמצאו משחקים, תקבלו משחקים אקראיים</p>
    <button @click="$emit('close')" class="yes theme-button-outline">להמשיך</button>
    <button @click="goBack" class="no theme-button-outline">
      שינוי הגדרות
    </button>
  </div>
</template>

<script>
export default {
  methods: {
    goBack() {
      this.$router.go(-1);
      this.$emit('close');
    }
  }
}
</script>

<style lang="scss" scoped>

.no {
  color: #000;
  font-size: 15px;
  i::before {
    vertical-align: middle;
  }
}
.main-icon {
  font-size: 70px;
  line-height: 100%;
}

</style>
