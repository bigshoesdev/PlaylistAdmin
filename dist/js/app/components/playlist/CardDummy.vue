<template lang="html">
  <div class="card">
    <div class="pep">
      <div class="img"></div>
      <!-- <p>
        <span>qwertyu</span>
      </p> -->
    </div>

    <div class="block">
      <div class="questions">
        <template>
          <div class="row top">
            <div class="num"></div>
            <p>
              <span>qwertyuias dbjsa bdjk asbdkajs</span>
            </p>
          </div>
          <div class="row middle">
            <div class="num"></div>
            <p>
              <span>qwertyui bdjsk ada dbja s,dbjkasbjkdb asjkdbjs akd</span>
            </p>
          </div>
          <div class="row last">
            <div class="num"></div>
            <p>
              <span>qwertyuiads,b dksa bdkba</span>
            </p>
          </div>
        </template>
      </div>

      <footer>
        <button class="gift" dir="rtl">
          <!-- <p class="top">
            מתנות מהמשחק
          </p> -->
          <!-- <p class="bottom">
            <span>qwertyuio</span>
          </p> -->
          <img src="img/spinning-circles.svg">
        </button>
        <button class="gopro" dir="rtl">
          <!-- <p>
            <span>sqed qewe</span>
          </p> -->
        </button>
      </footer>
    </div>
  </div>
</template>

<script>
export default {
  props: {

  },
  data() {
    return {

    };
  },
  created() {

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
  z-index: -2;
  .pep {
    text-align: center;
    z-index: 1;
    border-radius: 50px 50px 0 0;
    top: 0;
    left: 10px;
    background: #fff;
    position: absolute;
    width: 90px;
    height: 100px;
    .img {
      margin-top: 20px;
      display: inline-block;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: #3FBCD4;
    }
    p {
      font-size: 16px;
      margin: 0;
      span {
        background: $clr-wet;
        color: transparent;
      }
    }
  }
  .block {
    padding-top: 20px;
    height: 350px;
    background: #fff;
    border-radius: 10px 10px 20px 20px;
    border-top-left-radius: 0;
    padding-bottom: 60px;
    position: relative;
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
        text-align: right;
        display: flex;
        align-items: center;
        justify-content: center;
        img {
          height: 40px;
        }
        // p {
        //   margin: 0;
        // }
        // .top {
        //   font-size: 15px;
        //   color: $clr-wet;
        //   margin-bottom: 5px;
        // }
        // .bottom {
        //   font-size: 14px;
        //   color: #fff;
        //   text-overflow: ellipsis;
        //   overflow: hidden;
        //   white-space: nowrap;
        // }
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
          color: transparent;
          border-radius: 3px;
          overflow: hidden;
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
        }
        &:not(:last-child) {
          border-bottom: 1px dashed #3FBCD4;
        }
        &.top {
          .num {
            background: #3FBCD4;
          }
          p {
            span {
              background: #3FBCD4;
            }
          }
        }
        &.middle {
          .num {
            background: #0091BC;
          }
          p {
            span {
              background: #0091BC;
            }
          }
        }
        &.last {
          .num {
            background: #005D7A;
          }
          p {
            span {
              background: #005D7A;
            }
          }
        }

      }
    }
  }
}

</style>
