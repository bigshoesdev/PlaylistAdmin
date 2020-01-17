<template lang="html">
  <div class="modal" dir="rtl">
    <i class="icon ion-md-checkmark"></i>
    <p>תודה על תרומה לקהילה <br> של רעיון לשאלה</p>
    <button @click="$emit('close')" class="theme-button-outline">חזרה - <span class="fugaz">SHOOT</span></button>
  </div>
</template>

<script>
export default {

}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  i {
    font-size: 50px;
  }
  p {
    text-align: center;
    font-size: 22px;
    margin: 0;
  }
  .theme-button-outline {
    margin-top: 20px;
    margin-bottom: 5px;
    border-color: $clr-blue;
    color: $clr-wet;
    font-size: 14px;
  }
  .nope {
    color: $clr-wet;
    font-size: 16px;
  }
}

</style>
