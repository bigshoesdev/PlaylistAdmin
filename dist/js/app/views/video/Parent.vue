<template lang="html">
  <main class="video-education">
    <simple-header inverse>
      <span slot="title">ברוכים הבאים הורים יקרים</span>
    </simple-header>
    <p class="video-education-text">
      <span class="fugaz">Playlist</span> נולדה ממציאות מאתגרת: הילדים נמצאים מול המסכים בממוצע 7 שעות בכל יום! איך מתמודדים עם ההדרדרות ביכולות החברתיות והרגשיות?
    </p>
    <div class="video-education-frame">
      <iframe src="https://www.youtube.com/embed/tCpW_J9s_ig" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
    <p class="video-education-text">
      מכניסים אותם לעולם החברתי של פליי-ליסט.מכניסים אותם לעולם החברתי של <span class="fugaz">Playlist</span>. כאן הם מוצאים המון רעיונות למשחקי יצירתיים ופשוטים שאינם דורשים ציוד.
      <br> <br>
      <span class="fugaz">Playlist</span> אפילו תמצא לילדיכם חברה מתאימה מסביבתכם למשחקים חברתיים משותפים.
      <br> <br>
      יש ב <span class="fugaz">Playlist</span> גם רעיונות למשחקים למשפחה: לנסיעה, לארוחה משפחתית, לחופשה, לפיקניק, או סתם לשתי דקות שאנחנו ההורים פנויים פתאום למשחק קצר.
      <br> <br>
      <span class="fugaz">Playlist</span> ממשחקת גם את החיים הבוגרים: עם חברים, גיבוש עובדים, ישיבה בעבודה, היכרות, חיזוק ההורות ודייט.
    </p>
    <p class="video-education-text white">
      כהורים אתם יכולים להגדיר תכונות, כגון: כח רצון, חברותיות, קשב וכו‘ שאותם חשוב לכם לחזק אצל ילדיכם. <span class="fugaz">Playlist</span> תציג להם רק משחקים שמפתחים תכונות אלוגיבוש עובדים, ישיבה בעבודה, היכרות, חיזוק ההורות ודייט.
    </p>
    <div class="video-education-spacer"></div>
    <div class="video-education-button">
      <button @click="$router.push('/parent')" class="theme-button-outline">התחל</button>
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
  p {
    font-size: 15px;
    &.white {
      color: #fff;
    }
  }
  iframe {
    width: 100%;
  }
}

</style>
