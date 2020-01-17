<template lang="html">
  <div class="modal" dir="rtl">
    <i class="icon ion-md-checkmark"></i>
    <p>ה <span class="fugaz">FUN ZONE</span> נוצר בהצלחה!</p>
    <p>כעת ניתן להזמין חברים</p>
    <button @click="share" class="theme-button-outline">שיתוף</button>
    <button class="nope" @click="$emit('close')">לא עכשיו</button>
  </div>
</template>

<script>
import EventFinal from './EventFinal.vue';
export default {
  data() {
    return {
    };
  },
  methods: {
    share() {
      navigator.share(
        'היי, אני רוצה להזמין אותך לאירוע FUN ZONE שגם אני אהיה בו. זה מפגש חברתי מדליק עם משחקים חדשים ומיוחדים. פרטים מתי ואיפה באפליקציה PlayList בכפתור הקהילה שלי:  https://play.google.com/store/apps/details?id=coelix.bytesbay.playlist',
        undefined,
        undefined,
        () => {
          this.$emit('close');
          this.$modal.show(EventFinal, {}, {
            adaptive: true,
            height: 'auto',
            width: 300,
          });
        }
      );
    }
  }
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
    font-size: 22px;
    margin: 0;
  }
  .theme-button-outline {
    margin-top: 20px;
    margin-bottom: 5px;
    border-color: $clr-blue;
    color: $clr-wet;
    font-size: 16px;
  }
  .nope {
    color: $clr-wet;
    font-size: 14px;
  }
}

</style>
