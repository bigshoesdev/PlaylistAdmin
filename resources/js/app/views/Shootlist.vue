<template lang="html">
  <v-container>
    <h1>SHOOTLIST QUESTIONS</h1>
    <v-layout>
      <v-text-field solo label="Question(Female)" class="mr-3" v-model="content_fem"></v-text-field>
      <v-text-field solo label="Question(Male)" v-model="content_man"></v-text-field>
      <v-btn @click="create" text color="success">Create new shootlist</v-btn>
    </v-layout>
    <v-layout>
      <v-text-field @input="sync" small label="Search" v-model="search.query"></v-text-field>
      <v-select
        @input="sync"
        label="Category"
        v-model="search.category"
        :items="[
          {
            value: null,
            label: 'Nope',
          },
          {
            value: 3,
            label: 'נועז',
          },
          {
            value: 4,
            label: 'וואלה?!',
          },
          {
            value: 5,
            label: 'קליל',
          },
          {
            value: 2,
            label: 'טריוויה',
          },
          {
            value: 1,
            label: 'מי השבוע',
          },
        ]"
        item-text="label"
        item-value="value"
        solo
      ></v-select>
    </v-layout>
    <v-data-table
      v-if="data.length"
      :headers="[
        { text: 'ORDER INDEX', value: 'order_index' },
        { text: 'THE QUESTION', value: 'content_man' },
        { text: 'CREDIT', value: 'date' },
        { text: 'START DAY', value: 'date' },
        { text: 'LIKED', value: 'liked' },
        { text: 'ANSWERED', value: 'played' },
        { text: 'SKIPPED', value: 'games' },
        { text: 'VISIBLE', value: 'show' },
        { text: '' },
      ]"
      :items="data"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.order_index }}</td>
        <td>{{ props.item.content_man }}</td>
        <td>SOON</td>
        <td>{{ new Date(props.item.date * 1000) | dateFormat('DD.MM.YY') }}</td>
        <td>{{ props.item.played }}</td>
        <td>{{ props.item.skipped }}</td>
        <td>{{ props.item.liked }}</td>
        <td>{{ props.item.show ? 'VISIBLE' : 'HIDDEN' }}</td>
        <td>
          <v-btn text :to="'/shootlist/' + props.item.id_shootlist" color="info">edit</v-btn>
        </td>
      </template>
    </v-data-table>
  </v-container>
</template>

<script>
export default {
  name: 'shootlist',
  data() {
    return {
      data: [],
      content_man: '',
      content_fem: '',
      search: {
        query: '',
        category: null,
      },
    };
  },
  methods: {
    sync() {
      axios.post('/shootlist/full', {
        query: this.search.query,
        category: this.search.category,
      }).then(res => {
        this.data = res.data;
      });
    },
    create() {
      axios.post('/shootlist', {
        content_man: this.content_man,
        content_fem: this.content_fem,
      }).then(res => {
        this.sync();
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
