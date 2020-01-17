<template lang="html">
  <v-container>
    <h1>GIFTS</h1>
    <v-layout row wrap class="inputs">
      <v-text-field solo label="Name" v-model="name"></v-text-field>
      <v-btn @click="create" text color="success">add new gift</v-btn>
    </v-layout>
    <v-data-table
      :pagination="{
        rowsPerPage: -1,
      }"
      v-if="data.length"
      :headers="[
        { text: 'NAME', value: 'name' },
        { text: 'START DAY', value: 'date' },
        { text: 'NUMBER VIEWS', value: 'date' },
        { text: 'PLAYED', value: 'played' },
        { text: 'number games containing it', value: 'games' },
        { text: '' },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.name }}</td>
        <td>{{ new Date(props.item.date * 1000) | dateFormat('DD.MM.YY') }}</td>
        <td>{{ props.item.views }}</td>
        <td>{{ props.item.played }}</td>
        <td>{{ props.item.count }}</td>
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
          Updating gift
        </v-card-title>

        <v-card-text>
          <v-layout justify-end>
            <v-btn small color="info" @click="$refs.file.click()">
              Upload image
            </v-btn>
          </v-layout>
          <input v-show="false" ref="file" type="file" @change="updateImage" >
          <v-text-field v-model="selected.name" label="Gift name"></v-text-field>
          <v-select
            label="Select category for this gift"
            v-model="selected.id_section"
            :items="sections"
            item-text="name"
            item-value="id_section"
          ></v-select>
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
      sections: [],
      data: [],
      name: '',
    };
  },
  methods: {
    sync() {
      axios.get('/playlist/categories').then(res => {
        this.data = res.data;
      });
    },
    syncSections() {
      axios.get('/playlist/category/sections').then(res => {
        this.sections = res.data;
      });
    },
    create() {
      axios.post('/playlist/category', {
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
      axios.put('/playlist/category/' + this.selected.id_category, this.selected).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    remove() {
      axios.delete('/playlist/category/' + this.selected.id_category).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    updateImage() {
      var data = new FormData();
      data.append('file', this.$refs.file.files[0]);
      axios.post('/playlist/category/' + this.selected.id_category + '/set-image', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {

      });
    }
  },
  created() {
    this.sync();
    this.syncSections();
  }
}
</script>

<style lang="scss" scoped>



</style>
