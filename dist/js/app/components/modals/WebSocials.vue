<template lang="html">
  <div class="modal" dir="rtl">
    <div class="socials">
      <button @click="shareFb" class="facebook">
        <img src="img/facebook-letter-logo.svg">
      </button>
      <button @click="shareTwitter" class="twitter">
        <img src="img/twitter-logo-silhouette.svg">
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    text: String,
    title: String,
    onSuccess: Function,
  },
  data() {
    return {

    };
  },
  methods: {
    shareTwitter() {
      window.open(`https://twitter.com/share?url=${vars.MARKETING_URL}&text=${this.text}`, 'Twitter share', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');
      this.onSuccess();
      this.$emit('close');
    },
    shareFb() {
      FB.ui({
        method: 'share',
        href: vars.MARKETING_URL,
      }, function(res) {
        if(!res.error_message) {
          this.onSuccess();
          this.$emit('close');
        }
      });
    }
  },
  computed: {
    twitterURL() {
      return ``;
    },
    facebookURL() {
      return `https://twitter.com/share?url=${vars.MARKETING_URL}&text=${this.text}`;
    },
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  padding: 20px;
  .socials {
    width: 100%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-around;
    button {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;

      img {
        max-width: 70%;
        max-height: 70%;
      }
      &.facebook {
        background-color: #3B5998;
      }
      &.twitter {
        background-color: #76A9EA;
      }
    }
  }
}

</style>
