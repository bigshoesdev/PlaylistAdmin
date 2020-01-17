<template lang="html">
  <main>
    <simple-header inverse>
      <span slot="title">בתי הספר המובילים </span>
    </simple-header>
    <table dir="rtl">
      <tr class="head">
        <td>שם </td>
        <td>גיל</td>
        <td>אלופים</td>
      </tr>
      <tr v-for="item in data">
        <td>{{ item.name }}</td>
        <td>{{ item.city }}</td>
        <td>{{ item.points }}</td>
      </tr>
    </table>
  </main>
</template>

<script>
import SimpleHeader from './../components/SimpleHeader.vue';
export default {
  data() {
    return {
      data: [],
    };
  },
  components: {
    SimpleHeader,
  },
  created() {
    axios.get('/school/prizers').then(res => {
      this.data = res.data;
    });
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
  background-color: $clr-blue;
  table {
    width: 100%;
    text-align: center;
    border-spacing: 0;
    tr {
      td {
        border-bottom: 2px dashed $clr-wet;
        padding: 10px 0;
        img {
          vertical-align: middle;
        }
      }
      &:last-child {
        td {
          border-bottom: 0;
        }
      }
    }
    .head {
      td {
        border-bottom: 2px solid $clr-wet;
      }
    }
  }
}

</style>
