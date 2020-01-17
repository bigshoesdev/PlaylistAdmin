<template lang="html">
  <v-container>
    <h1>PLAYLIST SETTINGS</h1>
    <v-layout row wrap class="inputs">
      <v-text-field solo label="Name" v-model="name"></v-text-field>
      <v-select
        label="Section"
        v-model="type"
        :items="[
          'family',
          'child',
          'adult'
        ]"
        solo
      ></v-select>
      <v-btn @click="create" text color="success">add new setting</v-btn>
    </v-layout>
    <v-data-table
      :pagination="{
        rowsPerPage: -1,
      }"
      v-if="data.length"
      :headers="[
        { text: 'IMG', value: 'img' },
        { text: 'NAME', value: 'name' },
        { text: 'SECTION', value: 'type' },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <td v-if="props.item.img"><img :src="props.item.img"/></td>
        <td v-else>Not set</td>
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.type }}</td>
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
          Updating event category
        </v-card-title>

        <v-card-text>
          <v-layout justify-end>
            <v-btn small color="info" @click="$refs.file.click()">
              Upload image
            </v-btn>
          </v-layout>
          <input v-show="false" ref="file" type="file" @change="updateImage" >
          <v-text-field v-model="selected.name" label="Setting name"></v-text-field>
          <v-select
            label="Section"
            v-model="selected.type"
            :items="[
              'family',
              'child',
              'adult'
            ]"
            solo
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
      data: [],
      name: '',
      type: '',
      text: '',
    };
  },
  methods: {
    sync() {
      axios.get('/playlist/settings').then(res => {
        this.data = res.data;
      });
    },
    create() {
      axios.post('/playlist/settings', {
        name: this.name,
        type: this.type,
      }).then(res => {
        this.sync();
      });
    },
    edit(item) {
      this.selected = item;
      this.dialog.gift = true;
    },
    update() {
      axios.put('/playlist/settings/' + this.selected.id_setting, this.selected).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    remove() {
      axios.delete('/playlist/settings/' + this.selected.id_setting).then(res => {
        this.sync();
        this.dialog.gift = false;
      });
    },
    updateImage() {
      var data = new FormData();
      data.append('file', this.$refs.file.files[0]);
      axios.post('/playlist/settings/' + this.selected.id_setting + '/set-image', data, {
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
