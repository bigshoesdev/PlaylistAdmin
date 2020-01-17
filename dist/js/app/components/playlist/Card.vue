<template lang="html">
  <div class="card">
    <div class="pep">
      <img v-if="advertisment" src="img/playlist-share.svg" alt="">
      <img v-else-if="video" src="img/playlist-video.svg" alt="">
      <template v-else>
        <img v-if="data.img.length" class="custom-img" :src="data.img">
        <img v-else src="img/playlist-fuck.svg">
      </template>

      <p v-if="advertisment">שיתוף</p>
      <p v-else-if="video">הדגמה</p>
      <p v-else>
        <template v-if="data.level == 3">
          + מאתגר
        </template>
        <template v-else-if="data.level == 1">
          בכיף
        </template>
        <template v-else>
          מאתגר
        </template>
      </p>
    </div>
    <div :class="{ hide: !first }" class="title" dir="rtl" v-show="!advertisment">
      <span>{{ data.name }}</span>
    </div>
    <div class="block" :class="{
      advertisment: advertisment
    }">
      <div class="back-placeholder">
        לא שחקתי
      </div>
      <div class="next-placeholder">
        שחקתי
      </div>
      <template v-if="advertisment">
        <div class="adv-head">
          <button @click="show_adv = false">
            <i class="icon ion-md-close-circle-outline"></i>
          </button>
        </div>
        <div class="adv-content">
          <img src="img/playlist-shere-face.svg" alt="">
          <p dir="rtl">
            <span>נהנים? </span> <br>
            גם אחרים יהנו בזכותכם! <br>
            שתפו את <span class="fugaz">Playlist</span>...
          </p>
          <button @click="share" class="yea theme-button-outline">רוצה לשתף</button>
          <button @click="show_adv = false" class="nope">לא עכשיו</button>
        </div>
      </template>
      <div v-else class="questions">
        <template v-if="pro && !video">
          <div class="pro-head">
            <button @click="pro = false">
              <i class="icon ion-md-close-circle-outline"></i>
            </button>
            תוספת למשחק
          </div>
          <div class="row pro">
            <div class="num">
              +
            </div>
            <p>{{ data.question_4 }}</p>
          </div>
          <div class="row verypro">
            <div class="num">
              +
              +
            </div>
            <p>{{ data.question_5 }}</p>
          </div>
          <div class="row video" @click="openVideo">
            <div class="num">
              <i class="icon ion-md-play"></i>
            </div>
            <p>צפייה בהדגמה</p>
          </div>
        </template>
        <template v-else-if="video">
          <div class="video-head">
            <button @click="video = false">
              <i class="icon ion-md-close-circle-outline"></i>
            </button>
          </div>
          <div class="video-body" v-html="data.video"></div>
        </template>
        <template v-else>
          <div class="row top">
            <div class="num">
              1
            </div>
            <p>{{ data.question_1 }}</p>
          </div>
          <div class="row middle">
            <div class="num">
              2
            </div>
            <p>{{ data.question_2 }}</p>
          </div>
          <div class="row last">
            <div class="num">
              3
            </div>
            <p>{{ data.question_3 }}</p>
          </div>
        </template>
      </div>
      <footer v-if="!advertisment">
        <button class="gift" dir="rtl">
          <p class="top">
            מתנות מהמשחק
          </p>
          <p class="bottom">
            <template v-for="category in data.categories">
              <i class="icon ion-md-radio-button-on"></i>
              {{ category.name }}
            </template>
          </p>
        </button>
        <button @click="openPro" :class="{ on: pro || !data.additional }" class="gopro" dir="rtl">
          <p>
            תוספת
            למשחק
          </p>
        </button>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    data: {},
    first: {
      default: false,
      type: Boolean,
    },
    index: {
      type: Number,
    }
  },
  data() {
    return {
      pro: false,
      video: false,
      show_adv: false,
      activity: {
        video: false,
        pro: false,
      },
    };
  },
  computed: {
    advertisment() {
      return this.advCondition && this.show_adv;
    },
    advCondition() {
      return !this.$store.getters.replyCounter && (this.data.index != 1 && this.data.index % 5 == 0);
    },
    nameFontSize() {
      var value = this.data.name.length;
      if(value >= 11) {
        return 30;
      }
      else if(value >= 9) {
        return 40;
      }
      else {
        return 50;
      }
    }
  },
  methods: {
    share() {
      this.$store.dispatch('sharePlaylist');
    },
    openPro() {
      if(!this.data.additional) return;

      this.pro = true;
      this.video = false;
      if(!this.activity.pro) {
        this.activity.pro = true;
        axios.put('/playlist/' + this.data.id_playlist + '/activity/ext');
      }
    },
    openVideo() {
      this.video = true;
      if(!this.activity.video) {
        this.activity.video = true;
        axios.put('/playlist/' + this.data.id_playlist + '/activity/vid');
      }
    }
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

.card {
  padding: 0 10px;
  padding-top: 100px;
  position: relative;
  position: absolute;
  width: 100%;
  box-shadow: 0 0 0 transparent;
  &.swiping {
    transition: transform 1s ease;
  }
  &.swipe-left {
    transform: translateX(-150%) rotate(-60deg) !important;
  }
  &.swipe-right {
    transform: translateX(150%) rotate(60deg) !important;
  }

  &.animate,
  &.grapped,
  &.swiping {
    .block {
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    }
    .pep {
      box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
      z-index: 1;
      &:before {
        position: absolute;
        top: 100%;
        left: 0;
        width: 200%;
        height: 20px;
        background: #fff;
        z-index: 2;
        content: '';
      }
    }
  }

  &.animate {
    transition: transform 2s ease;
  }
  &.animate-left {
    transform: translateX(-150%) rotate(-60deg) !important;
    .next-placeholder {
      display: block;
    }
  }
  &.animate-right {
    transform: translateX(150%) rotate(60deg) !important;
    .back-placeholder {
      display: block;
    }
  }
  .title {
    position: absolute;
    right: 0;
    top: 0;
    color: #fff;
    font-size: 50px;
    height: 100px;
    line-height: 100px;
    vertical-align: middle;
    width: 100%;
    padding-left: 110px;
    padding-right: 10px;
    line-height: 90%;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: flex-start;
    &.hide {
      opacity: 0;
    }
    &.show {
      opacity: 1;
      transition: opacity 1s ease;
    }
  }
  .pep {
    text-align: center;
    border-radius: 50px 50px 0 0;
    top: 0;
    left: 10px;
    background: #fff;
    position: absolute;
    width: 90px;
    height: 100px;
    img {
      margin-top: 20px;
      &.custom-img {
        max-width: 50px;
        max-height: 80px;
      }
    }
    p {
      font-size: 16px;
      margin: 0;
    }
  }
  .back-placeholder,
  .next-placeholder {
    white-space: nowrap;
    direction: rtl;
    height: 100px;
    border: 2px solid #fff;
    font-size: 40px;
    line-height: 100px;
    position: absolute;
    padding: 0 10px;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    display: none;
    color: #fff;
    border-radius: 50px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
  }
  .next-placeholder {
    background: transparentize(#73CD49, 0.2);
  }
  .back-placeholder {
    background: transparentize(#FD7384, 0.2);
  }
  .block {
    height: 350px;
    background: #fff;
    border-radius: 10px 10px 20px 20px;
    border-top-left-radius: 0;
    padding-bottom: 60px;
    position: relative;
    &.advertisment {
      border-radius: 10px;
      border-top-left-radius: 0;
      background: #CEE7EF;
      padding-bottom: 0;
      .adv-head {
        padding-top: 10px;
        height: 40px;
        button {
          position: absolute;
          left: 30px;
          color: $clr-wet;
          font-size: 30px;
          width: 30px;
          height: 30px;
          padding: 0;
        }
      }
      .adv-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-grow: 1;
        p {
          text-align: center;
          font-weight: 600;
          margin: 0;
          span {
            font-size: 32px;
            line-height: 100%;
          }
        }
        .yea {
          margin-top: 10px;
          border-color: $clr-wet;
          color: $clr-wet;
        }
        .nope {
          margin-top: 10px;
          color: $clr-wet;
          font-size: 16px;
        }
      }
    }
    footer {
      width: 100%;
      height: 60px;
      bottom: 0;
      position: absolute;
      left: 0;
      .gift {
        width: calc(100% - 100px);
        height: 100%;
        background: #87D4EC;
        border-radius: 0 0 0 10px;
        padding-right: 60px;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzciIGhlaWdodD0iMzciIHZpZXdCb3g9IjAgMCAzNyAzNyIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayI+PHJlY3Qgd2lkdGg9IjM3IiBoZWlnaHQ9IjM3IiBmaWxsPSJ1cmwoI3BhdHRlcm4wKSIvPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuMCIgcGF0dGVybkNvbnRlbnRVbml0cz0ib2JqZWN0Qm91bmRpbmdCb3giIHdpZHRoPSIxIiBoZWlnaHQ9IjEiPjx1c2UgeGxpbms6aHJlZj0iI2ltYWdlMCIgdHJhbnNmb3JtPSJzY2FsZSgwLjAyKSIvPjwvcGF0dGVybj48aW1hZ2UgaWQ9ImltYWdlMCIgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiB4bGluazpocmVmPSJkYXRhOmltYWdlL3BuZztiYXNlNjQsaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQURJQUFBQXlDQVlBQUFBZVA0aXhBQUFBQm1KTFIwUUEvd0QvQVArZ3ZhZVRBQUFDdkVsRVFWUllDZTJac1dzVVFSU0hiNU1VV3BsQTFJaGF4RVk3UmZBdkVEV0tLSWJZUkFJaWFVVlVCTVV1b2lBaVdKcEcxRUlNYWl1SW5RRkJKQ0RZS0JiMmlTWXFHSTE2V2IrZk9yQzM3T3pON2VhR1pKM2pmVE83ODM3dnZaMTV4d1l1dGRyLzhvbmp1Qk5HWVJKbS8vR0crU1pzWDZweklOY09HSWUzWU9vODQvb0VkSlNxUTRKT2VBUTJxK01ZZzZob0ljWENaVkF1cGt5N3oycnh6UkI4RW1UVERNT3dIbnBnRzF5RG55QzdWR0lqVjVRQWZzQlZVRzdWNk9QNkdNeUFiTFJvalJyUkwwQjJKQ3NKam4yd0FJc3drcVhKV3lQbU9NaStNK3pKMHJJK0JMTG5XWDZuTmFMblFOWnRDOEE1QXJKNWhuNmJMcjJPZGl0b0F6cUVvMm0vdVVlajdqREZIODFheXpQUm4wQzJKaThZd1FUSUh1YnBrajdFajBGMkw3bWV2a1pnTmpLYjlqbmZrMlFLWkx2emdoQnNoQzhnRzhqVHlvZG9FR1NmR1Rab3pRYit2U0I3YWRNMFhTZjZJc2llTUVSNUFmalBnT3dkd3lxYkZ0OXFlQi8vL1p5eTZiU09KSUtuSUR1dnRVSVF2UlkrZ0d3c0x3bUNMbmdOc3VzMkxjNGJJSHZGMEdYVGFSMi9Yc3RNc2Q1Y3ZWb3JERmtPUVIxa3R4alcyWkxoMndWNmpVcC9JSzNEZHhBV1lRRjJwdjNtSGw4ZjNBYlpMNGI5eGxkcUp0RWdmQVdaNWp0Y2FHMFRjOE9wY244QlpQTU1wNkViZXVBc2ZBUFp1ZVFEc2FCdWJtWld6cnZNaW1XS1ZldHdVbHY2bXF4YjRBSFVvZDJtR2hNVTZYZDk4RWhDQW1MTks1V0lUOGRLZmZqMGN6ZDh2OW5Zbnc2bFJhNzNwclBOOHJqcW10VTFlYVNyVEVmQ1J0VE81VVRveUhMcWhwNmxNaDFwZVAwbVgyZmFaYnRaeW5yVjZvajVBMlpPeU55MzJoRVQ3eHBYdGs0eXZqSWRDUnR4L2ZyNDBvV08rRHBwMXpxaEk2NG41VXNYT3VMcnBGM3JoSTY0bnBRdlhlaUlyNU4yclJNNjRucFN2blNoSTc1TzJyVk82SWpyU2ZuU2hZNzRPbW5YT3BYcFNNUC9RMXI5T2NmMXROcWxxK1RQUWUwNkxPOTVmd01mNmtyOHN6TU9mZ0FBQUFCSlJVNUVya0pnZ2c9PSIvPjwvZGVmcz48L3N2Zz4=');
        background-repeat: no-repeat;
        background-position: center right 10px;
        background-size: 40px;
        text-align: right;
        p {
          margin: 0;
        }
        .top {
          font-size: 15px;
          color: $clr-wet;
          margin-bottom: 5px;
        }
        .bottom {
          font-size: 14px;
          color: #fff;
          text-overflow: ellipsis;
          overflow: hidden;
          white-space: nowrap;
        }
      }
      .gopro {
        position: absolute;
        right: 0;
        bottom: 0;
        width: 100px;
        height: 100%;
        background: #2999BC;
        border-radius: 0 0 10px 0;
        padding-right: 35px;
        background-image: url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTUiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNSAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTQuNzY1NiA5Ljk3MjY2SDkuNTY1NDNWMTUuODYxM0g1LjM5MDYyVjkuOTcyNjZIMC4xNDY0ODRWNS45ODgyOEg1LjM5MDYyVjAuMzMzOTg0SDkuNTY1NDNWNS45ODgyOEgxNC43NjU2VjkuOTcyNjZaIiBmaWxsPSJ3aGl0ZSIvPjwvc3ZnPg==');
        background-position: center right 15px;
        background-repeat: no-repeat;
        background-size: 15px;
        p {
          color: #fff;
          font-size: 15px;
          margin: 10px 0;
        }
        &.on {
          background-color: #A7A7A7;
        }
      }
    }
    .questions {
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      justify-content: space-between;
      padding: 0 20px;
      max-height: 290px;
      overflow: auto;
      .row {
        flex-grow: 1;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: flex-start;
        direction: rtl;
        padding: 15px 0;
        p {
          font-size: 18px;
          margin: 0;
          line-height: 120%;
          margin-right: 10px;
          flex-grow: 1;
        }
        .num {
          min-width: 30px;
          width: 30px;
          height: 30px;
          border-radius: 15px;
          background: #000;
          color: #fff;
          text-align: center;
          line-height: 30px;
          font-size: 16px;
          font-family: sans-serif;
          vertical-align: middle;
          i::before {
            vertical-align: middle;
          }
          i {
            vertical-align: baseline;
          }
        }
        &:not(:last-child) {
          border-bottom: 1px dashed #3FBCD4;
        }
        &.pro {
          margin-top: 40px;
        }
        &.top, &.pro {
          .num {
            background: #3FBCD4;
          }
          p {
            color: #3FBCD4;
          }
        }
        &.middle, &.verypro {
          .num {
            background: #0091BC;
          }
          p {
            color: #0091BC;
          }
        }
        &.last, &.video {
          .num {
            background: #005D7A;
          }
          p {
            color: #005D7A;
          }
        }
        &.video {
          p {
            font-size: 26px;
          }
          .num {
            line-height: 28px;
            i::before {
              transform: translateX(1px);
            }
          }
        }

      }
      .pro-head, .video-head {
        width: 100%;
        left: 0;
        position: absolute;
        text-align: center;
        box-sizing: content-box;
        height: 40px;
        text-align: center;
        line-height: 30px;
        padding-top: 10px;
        button {
          position: absolute;
          left: 30px;
          color: $clr-wet;
          font-size: 30px;
          line-height: 30px;
          width: 30px;
          height: 30px;
          padding: 0;
          z-index: 10;
        }
      }
      .video-body {
        flex-grow: 1;
        padding: 25px 0;
        padding-top: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }
  }
}

</style>
