<template lang="html">
  <div class="modal" dir="rtl">
    <div class="header">
      <img src="img/parent-colors.svg">
      מקרא צבעי התכונות
    </div>
    <div class="content">
      <div class="cnt">
        <div class="item" v-for="item in data">
          <div class="circle" :style="{ backgroundColor: item.color }"></div>
          <span>{{ item.name }}</span>
        </div>
      </div>
    </div>
    <button class="theme-button-outline close" @click="$emit('close')">סגור</button>
  </div>
</template>

<script>

export default {
  props: {
    data: {
      required: true
    }
  },
  components: {

  },
  methods: {

  },
  created() {

  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  padding-bottom: 30px;
  .header {
    border-bottom: 1px solid rgba(1, 1, 1, 0.25);
    padding: 20px 0;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
    img {
      position: absolute;
      right: 20px;
      top: 20px;
    }
  }
  .content {
    padding: 10px 0;
    padding-bottom: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    .cnt {
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      justify-content: flex-start;
      .item {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        font-size: 15px;
        .circle {
          width: 15px;
          height: 15px;
          border-radius: 50%;
          margin: 10px;
        }
      }
    }

  }
  .close {
    display: block;
    margin: 0 auto;
    width: 200px;
    border-color: $clr-blue;
    color: $clr-wet;
  }
}

</style>
