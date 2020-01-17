<template lang="html">
  <div class="modal" dir="rtl">
    <p><span class="fugaz">Playlist</span> יוצרת קהילות של משחקים חברתיים משותפים. <br> רוצה להצטרף?</p>
    <div class="buttons">
      <button @click="yes" class="theme-button-outline">כן</button>
      <button @click="$emit('close')" class="theme-button-outline">לא</button>
    </div>
    <button class="block" @click="block">אל תשאל אותי שוב</button>
  </div>
</template>

<script>

export default {
  components: {

  },
  methods: {
    block() {
      localStorage.setItem('subs_block', true);
      this.$emit('close');
    },
    yes() {
      this.$emit('close');
      this.$router.push('/event/slider');
      // this.$modal.show(Subscribe, {}, {
      //   height: 'auto',
      //   adaptive: true,
      //   scrollable: true,
      // });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  text-align: center;
  padding: 20px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  p {
    font-size: 25px;
    margin: 0;
  }
  .buttons {
    padding: 30px 0;
    width: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: stretch;
    .theme-button-outline {
      border-color: $clr-blue;
      color: $clr-wet;
      width: 100px;
    }
  }
  .block {
    color: $clr-wet;
    text-decoration: underline;
  }
}

</style>
