<template lang="html">
  <div class="modal-success" dir="rtl">
    <i class="main-icon icon ion-md-close"></i>
    <p class="main-text">
      ההרשמה ל <span class="fugaz">FUN ZONE</span> זה מלאה. ניתן לעקוב בהמשך אם מישהו מהנרשמים ביטל וכפתור ההרשמה נהפך לכחול.
    </p>
    <button @click="$emit('close')" class="yes theme-button-outline">אישור</button>
  </div>
</template>
