<template lang="html">
  <div class="wrapper">
    <div class="sidebar">
      <v-btn text to="/">Home</v-btn>
      <v-btn text to="/playlist">Playlist</v-btn>
      <v-btn text to="/teachers">Teacher lessons</v-btn>
      <v-btn text to="/gifts">Gifts</v-btn>
      <v-btn text to="/shootlist">Shootlist</v-btn>
      <v-btn text to="/workshop">Workshop</v-btn>
      <v-btn text to="/funkit">Funkit</v-btn>
      <v-btn text to="/users">Users</v-btn>
      <v-btn text to="/parent">Parent</v-btn>
      <v-btn text to="/event/categories">Event Category</v-btn>
      <v-btn text to="/playlist/locations">Playlist locations</v-btn>
      <v-btn text to="/school">Schools</v-btn>
      <v-btn text to="/playlist/sections">Playlist sections</v-btn>
      <v-btn text to="/funzone">Funzone</v-btn>
      <v-spacer/>
      <v-btn text color="error" @click="exit">
        Exit
        <v-icon>exit_to_app</v-icon>
      </v-btn>
    </div>
    <keep-alive :include="[ 'playlist', 'shootlist' ]">
      <router-view id="content"/>
    </keep-alive>
  </div>

</template>

<script>
export default {
  methods: {
    exit() {
      delete axios.defaults.headers.common['Authorization'];
      this.$root.logged = false;
      this.$cookie.delete('auth');
    }
  }
}
</script>

<style lang="scss" scoped>

#content {
  padding-left: 200px;
}
.sidebar {
  background: #fff;
  overflow-y: scroll;
  position: fixed;
  left: 0;
  top: 0;
  height: 100%;
  width: 200px;
  display: flex;
  flex-direction: column;
  align-items: stretch;
  justify-content: flex-start;
}

</style>
