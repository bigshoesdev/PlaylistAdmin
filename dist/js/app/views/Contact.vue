<template lang="html">
  <main>
    <simple-header :inverse="true">
      <span slot="title">
        צור קשר
      </span>
    </simple-header>
    <form class="app-inputs" @submit="send">
      <div class="app-section">
        <p>שם</p>
        <input v-model="name" type="text" required class="theme-input">
      </div>
      <div class="app-section">
        <p>הערות</p>
        <textarea v-model="comment" class="theme-input"></textarea>
      </div>
      <div class="app-section">
        <p>נייד</p>
        <input v-model="phone" v-mask="'###-#######'" type="tel" required class="theme-input">
      </div>
      <div class="app-section">
        <p>מייל</p>
        <input v-model="email" type="email" required class="theme-input">
      </div>
      <div class="end">
        <button type="submit" class="theme-button-outline">שלח</button>
      </div>
    </form>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
import ContactSent from './../components/modals/ContactSent.vue';
export default {
  data() {
    return {
      name: '',
      phone: '',
      email: '',
      comment: '',
      sent: false,
    };
  },
  methods: {
    send(e) {
      e.preventDefault();
      if(this.sent) return;
      this.sent = true;
      axios.post('/contact', {
        name: this.name,
        phone: this.phone,
        email: this.email,
        comment: this.comment,
      }).then(res => {
        this.$modal.show(ContactSent, {}, {
          adaptive: true,
          width: 300,
          height: 'auto',
        });
        this.sent = false;
        this.$router.push('/main');
      });
    }
  },
  components: {
    SimpleHeader,
    ContactSent,
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
