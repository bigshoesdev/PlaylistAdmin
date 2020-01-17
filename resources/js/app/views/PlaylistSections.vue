<template lang="html">
  <v-container>
    <h1>Playlist gifts sections</h1>
    <v-layout row wrap class="inputs">
      <v-text-field solo label="Name" v-model="name"></v-text-field>
      <v-btn @click="create" text color="success">add new section</v-btn>
    </v-layout>
    <v-data-table
      :pagination="{
        rowsPerPage: -1,
      }"
      v-if="data.length"
      :headers="[
        { text: 'NAME', value: 'name' },
        { text: 'COLOR', value: 'color' },
        { text: '' },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.color }}</td>
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
          Updating section
        </v-card-title>

        <v-card-text>
          <v-text-field v-model="selected.name" label="Gift name"></v-text-field>
          <sketch-picker v-if="selected.color" v-model="selected.color"/>
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
import { Sketch } from 'vue-color'

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
      axios.get('/playlist/section').then(res => {
        this.data = res.data;
      });
    },
    create() {
      axios.post('/playlist/section', {
        name: this.name,
      }).then(res => {
        this.sync();
      });
    },
    edit(item) {
      this.selected = {...item};
      this.dialog.gift = true;
    },
    update() {
      this.selected.color = this.selected.color.hex;
      axios.put('/playlist/section/' + this.selected.id_section, this.selected).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    remove() {
      axios.delete('/playlist/section/' + this.selected.id_section).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
  },
  created() {
    this.sync();
  },
  components: {
    'sketch-picker': Sketch,
  }
}
</script>

<style lang="scss" scoped>



</style>
