<template lang="html">
  <main>
    <div class="logo-container">
      <img src="img/white-logo.svg" alt="">
      <div class="bottom">
        משחקים מחוץ למסך
      </div>
    </div>
    <router-link to="/slider">
      <button class="theme-button">מתחילים לשחק</button>
    </router-link>
  </main>
</template>

<script>
export default {
  created() {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background-image: url($funny-bg);
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  .logo-container {
    position: relative;
    background: #067091;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 3px solid #fff;
    width: 300px;
    height: 120px;
    margin-bottom: 30px;
    img {
      width: 220px;
      margin-top: -20px;
    }
    .bottom {
      text-align: center;
      min-width: 180px;
      height: 10px;
      position: absolute;
      bottom: -3px;
      left: 50%;
      padding: 0 5px;
      transform: translateX(-50%);
      background: #067091;
      color: #fff;
      line-height: 90%;
    }
  }
  button {
    width: 280px;
    background: #fff;
    border-color: #118188;
  }
}

</style>
