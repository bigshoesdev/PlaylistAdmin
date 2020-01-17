<template lang="html">
  <v-container>
    <h1>WORKSHOP</h1>
    <v-layout row wrap class="inputs">
      <v-text-field solo label="Name" v-model="name"></v-text-field>
      <v-btn @click="create" text color="success">add new workshop</v-btn>
    </v-layout>
    <v-data-table
      v-if="data.length"
      :pagination="{
        rowsPerPage: -1,
      }"
      :headers="[
        { text: 'INDEX', value: 'order_index' },
        { text: 'NAME', value: 'name' },
        { text: 'START DAY', value: 'date' },
        { text: 'STATUS', value: 'show' },
        { text: 'REQUESTS', value: 'requests' },
        { text: '', sortable: false },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.order_index }}</td>
        <td>{{ props.item.name }}</td>
        <td>{{ new Date(props.item.date * 1000) | dateFormat('DD.MM.YY') }}</td>
        <td>{{ props.item.show ? 'VISIBLE' : 'HIDDEN' }}</td>
        <td>{{ props.item.requests }}</td>
        <td>
          <v-btn text @click="edit(props.item)" color="info">edit</v-btn>
        </td>
      </template>
    </v-data-table>
    <v-dialog
      v-model="dialog.gift"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Updating workshop
        </v-card-title>

        <v-card-text>
          <v-layout justify-space-between align-center>
            <div>
              <v-switch v-model="selected.show" :label="!selected.show ? 'Hidden' : 'Visible'"/>
            </div>
            <div>
              <v-btn small color="info" @click="$refs.file.click()">
                Upload image
              </v-btn>
            </div>
          </v-layout>
          <input v-show="false" ref="file" type="file" @change="updateImage" >
          <v-text-field v-model="selected.name" label="Workshop name"></v-text-field>
          <v-text-field v-model="selected.order_index" label="Order index"></v-text-field>
          <v-textarea
            outline
            name="input-7-4"
            label="Workshop descrliption"
            v-model="selected.text"
          ></v-textarea>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-btn
            color="error"
            icon
            small
            @click="remove"
          >
            <v-icon>delete</v-icon>
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="success"
            flat
            @click="update"
          >
            Update
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      selected: {},
      dialog: {
        gift: false,
      },
      data: [],
      name: '',
      text: '',
    };
  },
  methods: {
    sync() {
      axios.get('/workshop').then(res => {
        this.data = res.data;
      });
    },
    create() {
      axios.post('/workshop', {
        name: this.name,
      }).then(res => {
        this.sync();
      });
    },
    edit(item) {
      this.selected = item;
      this.dialog.gift = true;
    },
    update() {
      axios.put('/workshop/' + this.selected.id_workshop, this.selected).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    remove() {
      axios.delete('/workshop/' + this.selected.id_workshop).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    updateImage() {
      var data = new FormData();
      data.append('file', this.$refs.file.files[0]);
      axios.post('/workshop/' + this.selected.id_workshop + '/image', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {

      });
    }
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>



</style>
