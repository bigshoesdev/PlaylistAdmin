<template lang="html">
  <main class="img-about-bg">
    <simple-header inverse>
      <span slot="title">אודות</span>
    </simple-header>
    <section dir="rtl">
      <span class="fugaz">Playlist</span> מכניס אתכם לעולם של משחקים יצירתיים ופשוטים, משחקים ללא ציוד וללא מפסידים!
      <br> <br>
      מאות רעיונות למשחקים חברתיים מחוץ למסך הם האלטרנטיבה למסכים.
      <br> <br>
      המשחקים מחברים את הילדים לילדות חברתית בריאה, ואת המבוגרים לילד שבהם, למשפחה, ולכיף הפשוט של הביחד.
      <br> <br>
      ב <span class="fugaz">Shootlist</span> אתם מקבלים מגוון של מאות שאלות לשיחות זורמות ומחברות.
      <br> <br>
      <span class="fugaz">Playlist</span> מאפשרת לכם למצוא חברים חדשים לשחק איתם, וליצור או להצטרף לקהילה משחקת בשכונותכם.
      <br> <br>
      כהורים אתם מקבלים אפשרות לקבוע אלו תכונות אופי יחוזקו אצל ילדיכם באמצעות המשחקים ב <span class="fugaz">Playlist</span>
      <br> <br>
      <span class="version">PlayList v. {{ version }}</span>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      version: '',
    };
  },
  components: {
    SimpleHeader,
  },
  created() {
    if(cordova) {
      cordova.getAppVersion.getVersionNumber().then(version => {
        this.version = version;
      });
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background-color: $clr-blue;
  background-position: bottom -40px right -40px;
  background-repeat: no-repeat;
  background-size: auto;
  line-height: 110%;
  section {
    padding: 20px;
  }
  .version {
    font-size: 14px;
  }
}

</style>
