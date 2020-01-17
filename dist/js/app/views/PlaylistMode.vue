<template lang="html">
  <main dir="rtl">
    <simple-header>
      <span slot="title">
        מתנות לילדים
      </span>
    </simple-header>
    <p class="upper-text heebo">
      הצג לי משחקים שבאמצעותם <br>
      אני אהיה יותר:
    </p>
    <div class="sm-cont">
      <div class="links" dir="ltr">
        <button
          @click="selectCheck(1)"
          class="one"
          :class="{ selected: checks.includes(1) }"
        >
          <img height="100" src="img/playlist-mode-1.svg" alt="">
          <div class="right">
            <span>
              מגניב/ה
            </span>
            <div class="circle"></div>
          </div>
        </button>
        <button
          @click="selectCheck(2)"
          class="two"
          :class="{ selected: checks.includes(2) }"
        >
          <img height="100" src="img/playlist-mode-2.svg" alt="">
          <div class="right">
            <span>
              מקובל/ת
            </span>
            <div class="circle"></div>
          </div>
        </button>
        <button
          @click="selectCheck(3)"
          class="three"
          :class="{ selected: checks.includes(3) }"
        >
          <img height="100" src="img/playlist-mode-3.svg" alt="">
          <div class="right">
            <span>
               חזק/ה
            </span>
            <div class="circle"></div>
          </div>
        </button>
      </div>
    </div>
    <button @click="$router.push('/parent')" class="next">
      כניסה להגדרות הורים
    </button>
    <button @click="goNext" class="next">
      המשך
    </button>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  name: 'playlist-mode',
  data() {
    return {
      checks: [],
    };
  },
  methods: {
    selectCheck(i) {
      if(this.checks.includes(i)) {
        this.checks = this.checks.filter(n => n !== i);
      } else {
        this.checks.push(i);
      }
    },
    goNext() {
      this.$router.push({ name: 'playlist-options', params: { section: 'child' } });
    }
  },
  components: {
    SimpleHeader
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background: $clr-wet;
  color: #fff;
  padding-bottom: 20px;
  .upper-text {
    font-size: 24px;
    text-align: center;
    margin: 0;
  }
  .links {
    display: flex;
    align-items: stretch;
    justify-content: flex-start;
    flex-direction: column;
    padding: 0 3px;
    button {
      margin: 3px;
      border-radius: 10px;
      color: #fff;
      text-align: center;
      padding: 15px;
      display: flex;
      flex-direction: row;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      .right {
        span {
          line-height: 40px;
          font-size: 26px;
        }
        .circle {
          width: 40px;
          height: 40px;
          border-radius: 20px;
          border: 2px solid;
          background: #fff;
          float: right;
          margin-left: 10px;
          line-height: 40px;
          font-size: 30px;
          text-align: center;
          background-position: center;
          background-repeat: no-repeat;
        }
      }

      &.one {
        background: #0BEF59;
        .circle {
          border-color: #36C065;
          color: #36C065;
        }
        &.selected .circle {
          background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxOCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTYgMkw2LjQwNzQxIDEyTDIgNy40MDU0MSIgc3Ryb2tlPSIjMzZDMDY1IiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==);
        }
      }
      &.two {
        background: #FBE15D;
        .circle {
          border-color: #FFB72D;
          color: #FFB72D;
        }
        &.selected .circle {
          background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxOCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTYgMkw2LjQwNzQxIDEyTDIgNy40MDU0MSIgc3Ryb2tlPSIjRkZCNzJEIiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==);
        }
      }
      &.three {
        background: #FF7384;
        .circle {
          border-color: #F26161;
          color: #F26161;
        }
        &.selected .circle {
          background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxOCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTYgMkw2LjQwNzQxIDEyTDIgNy40MDU0MSIgc3Ryb2tlPSIjRjI2MTYxIiBzdHJva2Utd2lkdGg9IjQiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPjwvc3ZnPg==);
        }
      }
    }
  }
  .bottom-text {
    text-align: center;
    font-size: 15px;
  }
  .next {
    display: block;
    margin: 10px auto;
    width: 260px;
    height: 40px;
    border-radius: 20px;
    color: #fff;
    border: 2px solid #fff;
    font-size: 18px;
  }
}

</style>
