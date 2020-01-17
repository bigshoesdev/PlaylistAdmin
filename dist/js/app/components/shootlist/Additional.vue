<template lang="html">
  <section class="additional">
    <div class="wrapper" dir="rtl">
      <template v-if="advertisment">
        <div class="advertisment img-shootlist-share-bg">
          <p>
            <span>נהנים?</span><br>
            גם אחרים יהנו בזכותכם! <br>
            שתפו את אפליקצית פליי-ליסט...
          </p>
          <button @click="share" class="yea theme-button-outline">רוצה לשתף</button>
          <button @click="show_adv = false" class="nope">לא עכשיו</button>
        </div>
      </template>
      <template v-else>
        <div v-if="question.category == 1" class="first body img-shootlist-bg">
          <div class="category">
            מי השבוע
          </div>
          <div class="three-points">
            מי השבוע...
          </div>
          <span class="content" v-html="content"></span>
        </div>

        <div v-else-if="question.category == 2" class="second body img-shootlist-bg">
          <div class="category">
            טריווה
          </div>
          <span class="content" v-html="content"></span>
          <div class="variants">
            <div class="variant" :class="{ right: question.answers.right == 1 && indicate }">
              <span>1.</span> {{ question.answers.first }}
            </div>
            <div class="variant" :class="{ right: question.answers.right == 2 && indicate }">
              <span>2.</span> {{ question.answers.second }}
            </div>
            <div class="variant" :class="{ right: question.answers.right == 3 && indicate }">
              <span>3.</span> {{ question.answers.third }}
            </div>
          </div>
          <button
            @click="indicateRightAnswer"
            class="right-answer theme-button-outline"
            v-if="first"
          >גלה לי את התשובה</button>
        </div>

        <div v-else-if="question.category == 3" class="third body img-shootlist-bg">
          <div class="category">
            נועז
          </div>
          <span class="content" v-html="content"></span>
        </div>

        <div
          v-else-if="question.category == 4"
          class="fourth body img-shootlist-bg"
        >
          <div class="category">
            וואלה?!
          </div>
          <span class="content" v-html="content"></span>
        </div>

        <div v-else-if="question.category == 5" class="fifth body img-shootlist-bg">
          <div class="category">
            קליל
          </div>
          <span class="content" v-html="content"></span>
        </div>

      </template>
    </div>
  </section>
</template>

<script>
export default {
  props: {
    question: {},
    gender: {},
    first: {
      default: false,
      type: Boolean,
    }
  },
  data() {
    return {
      show_adv: false,
      indicate: false,
    };
  },
  methods: {
    indicateRightAnswer() {
      if(this.question.category == 2) {
        this.indicate = true;
      }
    },
    share() {
      this.$store.dispatch('shareShootlist');
    },
  },
  watch: {
    question() {
      this.indicate = false;
    }
  },
  computed: {
    content() {
      var content = '';
      if(this.gender == 'fem') {
        content = this.question.content_fem;
      }
      else if(this.gender == 'man') {
        content = this.question.content_man;
      }
      return content.replace('\n', '<br>');
    },
    advertisment() {
      return this.advCondition && this.show_adv;
    },
    advCondition() {
      return !this.$store.getters.replyCounter && (this.question.index != 1 && this.question.index % 5 == 0);
      // return true;
    },
  },
  created() {
    if(this.advCondition) {
      this.show_adv = true;
    }
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

@keyframes rightBtnPop {
  0% {
    opacity: 0;
  }
  100% {
    opacity: 1;
  }
}

section {
  width: 100%;
  height: 100%;
  position: absolute;
  left: 0;
  top: 0;
  &.swiping {
    transition: transform 1s ease;
  }
  &.swipe-left {
    transform: translateX(-150%) rotate(-60deg) !important;
  }
  &.swipe-right {
    transform: translateX(150%) rotate(60deg) !important;
  }

  &.animate-right {
    transform: translateX(150%) rotate(60deg);
  }
  &.animate-left {
    transform: translateX(-150%) rotate(-60deg);
  }
  &.animate {
    transition: transform 2s ease;
  }

  &.animate,
  &.grapped,
  &.swiping {
    .body {
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }
  }
  .wrapper {
    height: 100%;

    .body,
    .advertisment {
      width: 100%;
      height: 100%;
      padding: 20px;
      display: flex;
      justify-content: center;
      align-items: center;
      border-radius: 10px;
      border: 2px solid #fff;
      background-position: center;
      background-size: cover;
      background-repeat: no-repeat;
      position: relative;
      .content {
        font-size: 38px;
        font-weight: bold;
        line-height: 1;
      }
    }

    .first {
      background-color: #B7ABFF;
      .content {
        margin-top: 30px;
      }
    }
    .second {
      background-color: #73E1E8;
      .content {

      }
    }
    .third {
      background-color: #EF8787;
    }
    .fourth {
      background-color: #EFB987;
      padding-top: 45px;
    }
    .fifth {
      background-color: #73E878;
    }

    .second {
      display: block;
      .content {
        position: absolute;
        display: block;
        font-size: 26px;
        padding: 0 10px;
        top: 45px;
        width: 100%;
        text-align: center;
        font-weight: normal;
        left: 0;
      }
      .pre-text {
        display: block;
        position: absolute;
        right: 30px;
        top: 10px;
        font-size: 19px;
        color: #332681;
      }
      .right-answer {
        animation: rightBtnPop 0.3s ease;
        position: absolute;
        height: 30px;
        line-height: 30px;
        font-size: 12px;
        left: 0;
        top: -40px;
      }
      .variants {
        position: absolute;
        right: 10px;
        bottom: 5px;
        font-size: 20px;
        line-height: 100%;
        .variant {
          &.right {
            color: #CF1C1C;
          }
          span {
            font-weight: bold;
          }
        }
      }
    }

    .first {
      .three-points {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 19px;
      }
    }

    .advertisment {
      background-color: #CEE7EF;
      flex-direction: column;
      background-position: center bottom;
      background-size: contain;
      padding: 0 20px;
      p {
        margin: 0;
        margin-top: 5px;
        font-weight: 600;
        font-size: 16px;
        text-align: center;
        color: $clr-wet;
        font-size: 16px;
        span {
          font-size: 26px;
          line-height: 100%;
        }
      }

      .yea {
        margin-top: 10px;
        border-color: $clr-wet;
        color: $clr-wet;
        height: 30px;
        border-radius: 15px;
        line-height: 30px;
      }
      .nope {
        margin-top: 10px;
        color: $clr-wet;
        font-size: 16px;
      }
    }

    .category {
      position: absolute;
      left: 10px;
      top: 10px;
      background: #fff;
      color: $clr-wet;
      font-size: 15px;
      padding: 0 15px;
      line-height: 30px;
      height: 30px;
      border-radius: 15px;
    }
  }
}

</style>
