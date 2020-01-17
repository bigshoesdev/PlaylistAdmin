<template lang="html">
  <div class="modal" dir="rtl">
    <button @click="$emit('close')" class="close-btn">
      <i class="icon ion-md-close-circle-outline"></i>
    </button>
    <p>מצא/י את בית הספר שלך</p>
    <div class="body">
      <div class="search">
        <input @input="sync" class="theme-input" type="text" v-model="search">
        <i class="icon ion-md-search"></i>
      </div>
      <div class="result">
        <button v-for="item in schools" @click="selectSchool(item)" class="item">
          {{ item.city }}, {{ item.name }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    callback: {
      required: true,
    }
  },
  data() {
    return {
      schools: [],
      search: '',
    };
  },
  components: {

  },
  methods: {
    selectSchool(item) {
      this.callback(item);
      this.$emit('close');
    },
    sync() {
      axios.post('/school/search', {
        name: this.search,
      }).then(res => {
        this.schools = res.data;
      });
    },
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.modal {
  padding: 15px;
  p {
    margin-top: 0;
    font-size: 22px;
  }
  .search {
    position: relative;
    input {
      width: 100%;
    }
    i {
      position: absolute;
      left: 20px;
      font-size: 18px;
      height: 100%;
      line-height: 44px;
      color: $clr-blue;
      top: 0;
    }
  }
  .result {
    padding: 15px 0;
    .item {
      padding: 8px 0;
      border-bottom: 1px solid $clr-blue;
      display: block;
      width: 100%;
      text-align: right;
      color: $clr-wet;
      &:last-child {
        border-bottom: 0;
      }
    }
  }
  .close-btn {
    position: absolute;
    top: 20px;
    left: 15px;
    width: 30px;
    height: 30px;
    color: $clr-wet;
    padding: 0;
    i {
      vertical-align: middle;
      font-size: 30px;
    }
  }
}

</style>
