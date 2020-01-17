<template lang="html">
  <v-container>
    <h1>This is admin panel</h1>
    <v-divider/>
    <v-layout row align-start class="py-4">
      <v-text-field label="Promo client" v-model="promo.client" type="text"></v-text-field>
      <v-text-field label="Promo code" v-model="promo.value" type="text"></v-text-field>
      <v-select
        label="Subs type"
        v-model="promo.type"
        :items="[
          {
            value: 'month',
            label: 'Month subscription',
          },
          {
            value: 'six',
            label: 'Half year',
          },
          {
            value: 'games',
            label: 'X Games',
          },
        ]"
        item-text="label"
        item-value="value"
      ></v-select>
      <v-text-field
        v-if="promo.type == 'games'"
        label="Number of games"
        v-model="promo.games"
        type="number"
      ></v-text-field>
      <v-btn @click="newPromoCode" color="success">
        create
      </v-btn>
    </v-layout>
    <v-layout row align-start class="py-2">
      <v-data-table
        v-if="promo.promos"
        :headers="[
          { text: 'CLIENT', value: 'order_index' },
          { text: 'CODE', value: 'content_man' },
          { text: 'TYPE', value: 'type' },
          { text: 'USED', value: 'used' },
          { text: 'DELETE', sortable: false },
        ]"
        :items="promo.promos"
        class="pb-4"
      >
        <template slot="items" slot-scope="props">
          <td>{{ props.item.client }}</td>
          <td>{{ props.item.value }}</td>
          <td>{{ props.item.type }}</td>
          <td>{{ props.item.used }}</td>
          <td>
            <v-btn text @click="deletePromoCode(props.item)" color="error">delete</v-btn>
          </td>
        </template>
      </v-data-table>
    </v-layout>
    <v-divider/>
    <v-layout row align-start class="py-4">
      <v-text-field
        :label="`Price of ${prices.games_num} games`"
        v-model="prices.games"
        type="number"
        class="pr-3"
      ></v-text-field>
      <v-text-field
        label="Number of games"
        v-model="prices.games_num"
        type="number"
        class="pr-3"
      ></v-text-field>
      <v-text-field
        label="Price of one month"
        v-model="prices.month"
        type="number"
        class="pr-3"
      ></v-text-field>
      <v-text-field
        label="Price of half year"
        v-model="prices.six"
        type="number"
      ></v-text-field>
      <v-btn @click="updatePrices" color="success">
        save
      </v-btn>
    </v-layout>
    <v-layout row align-start class="py-4">
      <v-layout row align-start class="pb-3" v-for="variable in variables" :key="variable.id_variable">
        <v-text-field
          label="Start games"
          v-model="variable.value"
          type="number"
          class="pr-3"
        ></v-text-field>
        <v-btn @click="updateVariable(variable)" color="success">
          save
        </v-btn>
      </v-layout>
    </v-layout>
  </v-container>
</template>

<script>
export default {
  data() {
    return {
      promo: {
        promos: [],
        client: '',
        value: '',
        type: '',
        games: 40,
      },
      prices: {},
      variables: [],
    };
  },
  methods: {
    newPromoCode() {
      axios.post('/admin/promo', {
        client: this.promo.client,
        value: this.promo.value,
        type: this.promo.type,
        games: this.promo.games,
      }).then(res => {
        this.getPromoCodes();
      });
    },
    getPromoCodes() {
      axios.get('/admin/promo').then(res => {
        this.promo.promos = res.data;
      });
    },
    updatePromoCode(item) {
      axios.put('/admin/promo/' + item.id_promo, {
        value: item.value,
      });
    },
    deletePromoCode(item) {
      axios.delete('/admin/promo/' + item.id_promo).then(res => {
        this.getPromoCodes();
      });
    },

    updatePrices() {
      axios.put('/admin/prices', {
        prices: this.prices
      });
    },
    getPrices() {
      axios.get('/admin/prices').then(res => {
        this.prices = res.data.value;
      });
    },

    getVariables() {
      axios.get('/admin/variable').then(res => {
        this.variables = res.data;
      });
    },
    updateVariable(variable) {
      axios.put('/admin/variable/' + variable.id_variable, {
        value: variable.value,
      });
    },
  },
  created() {
    this.getPromoCodes();
    this.getPrices();
    this.getVariables();
  }
}
</script>

<style lang="scss" scoped>



</style>
