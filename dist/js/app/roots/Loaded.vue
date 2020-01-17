<template lang="html">
  <transition
    :name="$root.animation"
    @after-leave="afterLeave"
    @after-enter="afterEnter"
    mode="in-out"
  >
    <keep-alive :include="this.$root.include">
      <router-view id="content"/>
    </keep-alive>
  </transition>
</template>

<script>
export default {
  methods: {
    afterLeave() {
      this.$root.animation = 'slide-front';

      ebus.triggerEvent('route-anim-end');
    },
    afterEnter() {
      window.scrollTo(0, 0);
    }
  },
  mounted() {
    this.$root.animation = 'slide-front';
    ebus.triggerEvent('route-anim-end');
  }
}
</script>

<style lang="scss" scoped>

.slide-front-enter-active {
  transition: transform 0.5s ease;
  position: fixed;
  z-index: 10;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
}
.slide-front-enter {
  transform: translateX(100%);
}
.slide-front-enter-to {
  transform: translateX(0);
}

.slide-front-leave-active {
  display: none;
}


.first-pop-enter-active {
  transition: transform 0.5s ease, opacity 0.5s ease;
}
.first-pop-enter {
  transform: scale(1.2);
  opacity: 0;
}
.first-pop-enter-to {
  transform: scale(1);
  opacity: 1;
}


.slide-back-leave-active {
  box-shadow: 0 0 20px rgba(0,0,0,0.5);
  transition: transform 0.5s ease;
  z-index: 10;
  position: fixed;
}
.slide-back-leave {
  transform: translateX(0);
}
.slide-back-leave-to {
  transform: translateX(100%);
}


.slide-back-enter-active {
  z-index: -1;
  position: fixed;
}

</style>
