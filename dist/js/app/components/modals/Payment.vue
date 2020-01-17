<template lang="html">
  <div class="modal">
    <iframe v-if="url" :src="url" ref="frame"></iframe>
  </div>
</template>

<script>
export default {
  props: {
    url: {
      required: true,
      type: String,
    },
    id_transaction: {
      required: true,
      type: String,
    },
    ended: {
      type: Function,
    }
  },
  data() {
    return {
      interval: null,
    };
  },
  created() {
    this.interval = setInterval(() => {
      axios.get('/transaction/' + this.id_transaction).then(res => {
        if(res.data.ready) {
          this.$emit('close');
          this.ended();
        }
      });
    }, 2000);
  },
  destroyed() {
    clearInterval(this.interval);
  }
}
</script>

<style lang="scss" scoped>

.modal {
  width: 100%;
  height: 100%;
  // padding: 10px;
  iframe {
    width: 100%;
    height: 100%;
    border: 0;
  }
}

</style>
