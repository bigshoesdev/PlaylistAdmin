<template lang="html">
  <v-container>
    <h1>PLAYLIST GAMES</h1>
    <v-layout>
      <v-text-field solo label="Name" v-model="name"></v-text-field>
      <v-btn @click="create" text color="success">add new game</v-btn>
    </v-layout>
    <v-layout>
      <v-text-field @input="sync" small label="Search" v-model="search.str"></v-text-field>
      <v-select
        @input="sync"
        label="Locations"
        v-model="search.locations"
        chips
        :items="locations"
        item-text="name"
        item-value="id_setting"
        solo
        multiple
      ></v-select>
      <v-select
        @input="sync"
        label="Categories"
        v-model="search.categories"
        chips
        :items="categories"
        item-text="name"
        item-value="id_category"
        solo
        multiple
      ></v-select>
      <v-select
        @input="sync"
        label="Level"
        v-model="search.level"
        chips
        :items="[
          {
            name: 'easy',
            value: 1,
          },
          {
            name: 'Hard',
            value: 2,
          },
          {
            name: 'Hardest',
            value: 3,
          },
        ]"
        item-text="name"
        item-value="value"
        solo
      ></v-select>
    </v-layout>
    <v-data-table
      v-if="data.length"
      :headers="[
        /* { text: 'NUMBER', value: 'id_playlist' }, */
        { text: 'ORDER INDEX', value: 'order_index' },
        { text: 'NAME GAME', value: 'name' },
        { text: 'CREDIT' },
        { text: 'START DAY', value: 'date' },
        { text: 'ANSWERED', value: 'played' },
        { text: 'SKIPPED', value: 'skipped' },
        { text: 'LIKED', value: 'liked' },
        { text: 'EXTENTION VIEWS', value: 'opened_ext' },
        { text: 'VIDEO VIEWS', value: 'opened_video' },
        { text: 'STATUS', value: 'show' },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <!-- <td>{{ props.item.id_playlist }}</td> -->
        <td>{{ props.item.order_index }}</td>
        <td>{{ props.item.name }}</td>
        <td>SOON</td>
        <td>{{ new Date(props.item.date * 1000) | dateFormat('DD.MM.YY') }}</td>
        <td>{{ props.item.played }}</td>
        <td>{{ props.item.skipped }}</td>
        <td>{{ props.item.liked }}</td>
        <td>{{ props.item.opened_ext }}</td>
        <td>{{ props.item.opened_video }}</td>
        <td>{{ props.item.show ? 'VISIBLE' : 'HIDDEN' }}</td>
        <td>
          <v-btn text :to="'/playlist/' + props.item.id_playlist" color="info">edit</v-btn>
        </td>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  name: 'playlist',
  data() {
    return {
      data: [],
      name: '',
      search: {
        str: '',
        categories: [],
        locations: [],
        level: null,
      },
      categories: '',
      locations: '',
    };
  },
  methods: {
    sync() {
      axios.post('/playlist/full', {
        query: this.search,
      }).then(res => {
        this.data = res.data;
      })
    },
    syncCategories() {
      axios.get('/playlist/categories').then(res => {
        this.categories = res.data;
      });
    },
    syncLocations() {
      axios.get('/playlist/settings').then(res => {
        this.locations = res.data;
      });
    },
    create() {
      axios.post('/playlist', {
        name: this.name
      }).then(res => {
        this.sync();
      })
    }
  },
  created() {
    this.sync();
    this.syncLocations();
    this.syncCategories();
  }
}
</script>

<style lang="scss" scoped>


</style>
