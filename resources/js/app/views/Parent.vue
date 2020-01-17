<template lang="html">
  <v-container>
    <h1>PARENT</h1>
    <v-layout>
      <v-text-field solo label="Search" v-model="search"></v-text-field>
    </v-layout>
    <v-data-table
      :pagination="{
        rowsPerPage: -1,
      }"
      v-if="data.length"
      :headers="[
        { text: 'ID', value: 'id_user' },
        { text: 'NAME', value: 'name' },
        { text: 'SURNAME', value: 'surname' },
        { text: 'PIN', value: 'code' },
        { text: '', sortable: false },
      ]"
      :items="data.filter(n => !search.length || ((n.surname && n.surname.includes(search)) || (n.name && n.name.includes(search))))"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id_user }}</td>
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.surname }}</td>
        <td>{{ props.item.code }}</td>
        <td>
          <v-btn text @click="sendCode(props.item)" color="info">send pin-code</v-btn>
        </td>
      </template>
    </v-data-table>

    <v-snackbar
      v-model="alert.pin"
      right
      top
      :timeout="2000"
    >
      Pin code sent.
      <v-btn
        color="pink"
        flat
        @click="alert.pin = false"
      >
        Close
      </v-btn>
    </v-snackbar>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      data: [],
      alert: {
        pin: false,
      },
      search: '',
    };
  },
  methods: {
    sync() {
      axios.get('/admin/parent').then(res => {
        this.data = res.data;
      });
    },
    sendCode(item) {
      axios.put('/admin/parent/' + item.id_user + '/code').then(res => {
        this.alert.pin = true;
      });
    }
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

.avatar {
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
  background-color: #ccc;
  width: 80px;
  height: 80px;
  border-radius: 50%;
}

</style>
