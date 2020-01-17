<template lang="html">
  <div class="modal-tutorial" dir="rtl" v-html="frame">

  </div>
</template>

<script>

export default {
  data() {
    return {
      frame: '',
    };
  },
  created() {
    axios.get('/variable/tutorial').then(res => {
      this.frame = res.data.value;
    });
  }
};

</script>

<style lang="scss" scoped>

.modal {
  padding: 0;
}

</style>
