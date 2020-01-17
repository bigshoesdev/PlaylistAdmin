<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        שולח בקשה
      </span>
    </simple-header>
    <form class="app-inputs" @submit="send">
      <div class="app-section">
        <p>שם</p>
        <input v-model="loc" type="text" required class="theme-input">
      </div>
      <div class="app-section">
        <p>עיר</p>
        <input v-model="city" type="text" required class="theme-input">
      </div>
      <div class="app-section">
        <p>נייד</p>
        <input v-model="phone" type="tel" required class="theme-input">
      </div>
      <div class="app-section">
        <p>מייל</p>
        <input v-model="email" type="email" required class="theme-input">
      </div>
      <div class="app-section">
        <p>כמות משתתפים משוערת</p>
        <div class="app-input-wrapper">
          <span>לא חובה</span>
          <input v-model="num" type="text" class="theme-input">
        </div>
      </div>
      <div class="app-section">
        <p>הערות</p>
        <div class="app-input-wrapper">
          <span>לא חובה</span>
          <textarea v-model="comment" class="theme-input"></textarea>
        </div>
      </div>
      <div class="end">
        <button type="submit" class="theme-button-outline">הבא</button>
      </div>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import WorkshopSent from './../components/modals/WorkshopSent.vue';
export default {
  data() {
    return {
      loc: '',
      city: '',
      phone: '',
      email: '',
      num: '',
      comment: '',
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      axios.post('/workshop/' + this.$route.params.id_workshop + '/form', {
        loc: this.loc,
        city: this.city,
        phone: this.phone,
        email: this.email,
        num: this.num,
        comment: this.comment,
      }).then(res => {
        this.$modal.show(WorkshopSent, {}, {
          adaptive: true,
          width: 300,
          height: 'auto',
        });
        this.$router.push('/main');
      });
    }
  },
  components: {
    SimpleHeader,
    WorkshopSent,
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-blue;
  .end {
    padding: 20px 0;
    button {
      width: 100%;
    }
  }
}

</style>
