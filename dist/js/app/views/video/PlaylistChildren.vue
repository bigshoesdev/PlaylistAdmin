<template lang="html">
  <main class="video-education">
    <simple-header inverse></simple-header>
    <div class="video-education-frame">
      <iframe src="https://www.youtube.com/embed/DvTERqJ0cX8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <p class="video-education-text">
      רוצה שלא ישעמם לך
      בבית ובהפסקות בבית-הספר?
      מעולה!
      <br> <br>
      <span class="fugaz">Playlist</span> נותנת רעיונות למשחקים
      מגניבים ללא ציוד, כך שאפשר
      לשחק בכל רגע שמתחשק.
      <br> <br>
      המשחקים הם ללא מפסידים או נפסלים. משחקים
      לא נגד אלא ביחד - לא לנצח, אלא  ליהנות):
      <br> <br>
      אז יאללה לשחחחחחק...
    </p>
    <div class="video-education-spacer"></div>
    <div class="video-education-button">
      <button @click="$router.push('/playlist/mode')" class="theme-button-outline">התחל</button>
    </div>
  </main>
</template>

<script>
import SimpleHeader from './../../components/SimpleHeader.vue';
export default {
  components: {
    SimpleHeader,
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {

}

</style>
