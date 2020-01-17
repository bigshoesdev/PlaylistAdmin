<template lang="html">
  <div id="app">
    <unloaded v-if="!$store.state.user.loaded"></unloaded>
    <loaded v-else></loaded>
    <sidebar></sidebar>

  </div>
</template>

<script>
import Loaded from './roots/Loaded.vue'
import Unloaded from './roots/Unloaded.vue'
import Sidebar from './components/Sidebar.vue';

export default {
  components: {
    Loaded,
    Unloaded,
    Sidebar,
  },
}
</script>

<style lang="scss" scoped>
</style>
