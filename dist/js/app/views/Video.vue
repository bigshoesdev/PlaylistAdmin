<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title">כוחו של המשחק</span>
    </simple-header>
    <section>
      <iframe src="https://www.youtube.com/embed/8W1lXB5HViQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  components: {
    SimpleHeader,
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background-color: $clr-blue;
  section {
    height: 100%;
    padding: 20px;
    text-align: center;
  }
}

</style>
