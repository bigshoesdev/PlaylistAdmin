<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title">הגדרת קוד למתנות מהמשחקים</span>
    </simple-header>
    <section dir="rtl">
      <i class="icon ion-md-lock"></i>
      <p>
        קוד זה יאפשר לכם כהורים לקבוע
        את המתנות מהמשחקים
        באופן נקי משיקולי לחצים של הילדים.
        הם יקבלו בפליי-ליסט את התחושה
        שהמשחקים רנדומלים, ולא ידעו על
        מעורבותכם למענם מאחורי הקלעים.
      </p>
      <button class="theme-button-outline" @click="setNewCode">הגדר קוד להורים</button>
      <button class="theme-button-outline" @click="skip">המשך ללא קוד</button>
    </section>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import ParentCodeModal from './../components/ParentCodeModal.vue';
export default {
  methods: {
    setNewCode() {
      this.$modal.show(ParentCodeModal, {
        to: '/parent',
        setting: true,
      }, {
        adaptive: true,
        height: 'auto',
        width: 300,
      });
    },
    skip() {
      this.$store.state.user.no_pin = true;
      this.$router.replace(this.$route.params.to.name);
    }
  },
  components: {
    SimpleHeader,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  section {
    text-align: center;
  }
  p {
    margin: 30px auto;
    color: #fff;
    width: 290px;
  }
  i {
    font-size: 120px;
    color: #fff;
    line-height: 0;
  }
  button {
    width: 250px;
    display: block;
    margin: 15px auto;
  }
}

</style>
