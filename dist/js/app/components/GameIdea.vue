<template lang="html">
  <section dir="rtl" :class="{ 'hidden-header': hidden }">
    <header v-if="!hidden" class="img-idea-lamp" :class="{ 'no-credit': !user }">
      <router-link class="button" :to="route">
        כן
      </router-link>
      <p>
        יש לך רעיון לשיפור המשחק?
      </p>
      <p>
        יש לך רעיון למשחק חדש?
      </p>
    </header>
    <template v-if="user">
      <p class="title">
        תודה על הרעיון למשחק ל:
      </p>
      <div dir="rtl" class="plate img-idea-bg">
        <div class="circles">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
        <div class="author">
          <span v-for="n in credit" v-html="n"></span>
        </div>
      </div>
    </template>
  </section>
</template>

<script>
export default {
  props: {
    route: {
      required: true,
    },
    hidden: {
      default: false,
      type: Boolean,
    },
    city: {},
    school: {},
    user: {},
  },
  methods: {
    test() {

    }
  },
  computed: {
    credit() {
      var strings = [];

      if(this.user.age) {
        strings.push(this.user.name + ' ' + this.user.surname + ' ' + this.user.age);
      } else {
        strings.push(this.user.name + ' ' + this.user.surname);
      }

      if(this.school) {
        strings.push('בית ספר: ' + this.school.name + ' ' + this.school.city);
      }

      else if(this.city) {
        strings.push(this.city);
      }

      var len = strings.length - 1;
      strings = strings.map((n, i) => {
        if(i != len) {
          n += ',<br>';
        }
        return n;
      });

      return strings;
    }
  },
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

section {
  padding: 10px 20px;
  background: #01465B;
  &.hidden-header {
    padding: 0;
  }
  header {
    position: relative;
    color: #fff;
    height: 40px;
    margin-bottom: 15px;
    padding-right: 50px;
    background-repeat: no-repeat;
    background-position: center right 10px;
    background-size: contain;
    &.no-credit {
      margin-bottom: 0;
    }
    .button {
      display: inline-block;
      position: absolute;
      left: 0;
      top: 0;
      height: 40px;
      border-radius: 20px;
      border: 2px solid #fff;
      color: #fff;
      padding: 0 30px;
      line-height: 36px;
      vertical-align: middle;
    }
    p {
      margin: 0;
      line-height: 20px;
      vertical-align: middle;
      @media (max-width: 375px) {
        font-size: 13px;
      }
    }
  }
  .title {
    text-align: center;
    font-size: 27px;
    color: #fff;
    margin: 0;
  }
  .plate {
    max-width: 310px;
    position: relative;
    background-color: #fff;
    border-radius: 10px;
    height: 230px;
    background-repeat: no-repeat;
    background-size: contain;
    background-position: top;
    display: flex;
    align-items: center;
    justify-content: center;
    color: $clr-wet;
    margin: 0 auto;
    .circles {
      div {
        position: absolute;
        width: 12px;
        height: 12px;
        border-radius: 6px;
        background: $clr-wet;
        &:nth-child(1) {
          left: 5px;
          top: 5px;
        }
        &:nth-child(2) {
          right: 5px;
          top: 5px;
        }
        &:nth-child(3) {
          right: 5px;
          bottom: 5px;
        }
        &:nth-child(4) {
          left: 5px;
          bottom: 5px;
        }
      }
    }
    .author {
      width: 190px;
      font-size: 26px;
      text-align: center;
      line-height: 120%;
    }
  }
}

</style>
