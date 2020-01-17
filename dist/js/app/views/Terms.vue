<template lang="html">
  <main class="img-about-bg">
    <simple-header inverse>
      <span slot="title">תנאי שימוש</span>
    </simple-header>
    <section dir="rtl">
      <h4 class="heebo">מדיניות תנאי התקשרות</h4>
      היישום של רעיונות המשחקים המופיעים ב <span class="fugaz">Playlist</span> הוא באחריות בלעדית של מי שמשחקים בהם.
      <br> <br>
      אין ל <span class="fugaz">Playlist</span> כל אחריות לכל פגיעה אפשרית במהלך משחק שמופיע באתר או באפליקצית <span class="fugaz">Playlist</span>.
      <br> <br>
      <span class="fugaz">Playlist</span> לא מתחייבת לפרסם רעיונות למשחקים ושאלות שנשלחו אליה. <span class="fugaz">Playlist</span> שומרת לעצמה את הזכות לכל שימוש שהוא ברעיונות למשחקים שילשחו אליה ולשולח הרעיון למשחק אין שום בלעדיות עליו.
      <br> <br>
      <span class="fugaz">Playlist</span> אינה מתחייבת לפרסם קרדיט למי שהצעה שלו פורסמה. הקרדיט על רעיון למשחק או שאלה הוא למספר חודשים ומעלה
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
  background-position: bottom -40px right -40px;
  background-repeat: no-repeat;
  background-size: auto;
  section {
    padding: 20px;
    padding-bottom: 150px;
    h4 {
      font-weight: 400;
    }
  }
}

</style>
