<template lang="html">
  <div class="modal">
    <p>?רוצה להתנתק</p>
    <div class="buttons">
      <button @click="$emit('close')" class="theme-button-outline">לא</button>
      <button @click="logout" class="theme-button-outline">כן</button>
    </div>
  </div>
</template>

<script>
export default {
  methods: {
    logout() {
      this.$store.commit('logout');
      this.$emit('close');
      this.$router.push('/login');
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 30px 10px;
  p {
    margin-top: 0;
  }
  button {
    margin: 0 10px;
    border-color: $clr-blue;
    color: $clr-wet;
  }
}

</style>
