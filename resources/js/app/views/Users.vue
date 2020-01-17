<template lang="html">
  <v-container>
    <h1>USERS</h1>
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
        { text: '', sortable: false },
        { text: 'NAME', value: 'name' },
        { text: 'SURNAME', value: 'surname' },
        { text: 'START DAY', value: 'date' },
        { text: 'PHONE', value: 'phone', sortable: false },
        { text: 'GENDER', value: 'gender' },
        { text: 'EMAIL', value: 'email' },
        { text: 'AGE', value: 'age' },
        { text: 'SUBSCRIPTION', sortable: false },

        { text: 'PLAYED(PLAYLIST)', value: 'playlist_played' },
        { text: 'SKIPPED(PLAYLIST)', value: 'playlist_skipped' },
        { text: 'LIKED(PLAYLIST)', value: 'playlist_liked' },
        { text: 'PLAYED(SHOOTLIST)', value: 'shootlist_played' },
        { text: 'SKIPPED(SHOOTLIST)', value: 'shootlist_skipped' },
        { text: 'LIKED(SHOOTLIST)', value: 'shootlist_liked' },

        { text: 'CHILD ACTIVITY(SMS)', sortable: false },
        { text: 'FUNZONE(SMS)', sortable: false },

        { text: 'CHILD ACTIVITY(EMAIL)', sortable: false },
        { text: 'FUNZONE(EMAIL)', sortable: false },
      ]"
      :items="data.filter(n => !search.length || ((n.surname && n.surname.includes(search)) || (n.name && n.name.includes(search))))"
    >
      <template slot="items" slot-scope="props">
        <td>{{ props.item.id_user }}</td>
        <td>
          <v-btn text @click="edit(props.item)" color="info">edit</v-btn>
        </td>
        <td>{{ props.item.name }}</td>
        <td>{{ props.item.surname }}</td>
        <td>{{ new Date(props.item.date * 1000) | dateFormat('DD.MM.YY') }}</td>
        <td>{{ props.item.phone }}</td>
        <td>{{ props.item.gender }}</td>
        <td>{{ props.item.email }}</td>
        <td>{{ props.item.age }}</td>
        <td>
          <template v-if="(props.item.subscribe + props.item.subscribe_interval) * 1000 > Date.now()">
            YES
            ({{ new Date(props.item.subscribe * 1000) | dateFormat('DD.MM.YY HH:mm') }}) -
            ({{ new Date((props.item.subscribe + props.item.subscribe_interval) * 1000) | dateFormat('DD.MM.YY') }})
          </template>
          <template v-else>
            NO
          </template>
        </td>

        <td>{{ props.item.playlist_played }}</td>
        <td>{{ props.item.playlist_skipped }}</td>
        <td>{{ props.item.playlist_liked }}</td>

        <td>{{ props.item.shootlist_played }}</td>
        <td>{{ props.item.shootlist_skipped }}</td>
        <td>{{ props.item.shootlist_liked }}</td>

        <td @click="updateNotifications(props.item, 'phone_dev')" class="notification">
          {{ props.item.notification && props.item.notification.phone_dev ? 'YES' : 'NO' }}
        </td>
        <td @click="updateNotifications(props.item, 'phone_funzone')" class="notification">
          {{ props.item.notification && props.item.notification.phone_funzone ? 'YES' : 'NO' }}
        </td>

        <td @click="updateNotifications(props.item, 'email_dev')" class="notification">
          {{ props.item.notification && props.item.notification.email_dev ? 'YES' : 'NO' }}
        </td>
        <td @click="updateNotifications(props.item, 'email_funzone')" class="notification">
          {{ props.item.notification && props.item.notification.email_funzone ? 'YES' : 'NO' }}
        </td>
      </template>
    </v-data-table>
    <v-dialog
      v-model="dialog.edit"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Edit user
        </v-card-title>

        <v-card-text>
          <div class="avatar" :style="{ backgroundImage: `url(${selected.full_img})` }"></div>
          <v-text-field v-model="selected.name" label="Name"></v-text-field>
          <v-text-field v-model="selected.surname" label="Surame"></v-text-field>
          <v-text-field v-model="selected.email" label="Email"></v-text-field>
          <v-select
            label="Select gender"
            v-model="selected.gender"
            :items="[
              {
                text: 'Man',
                value: 'man',
              },
              {
                text: 'Female',
                value: 'fem',
              },
            ]"
          ></v-select>

          <v-checkbox
            v-model="selected.new_subscribe_flag"
            label="Update subscribe (from now)"
          ></v-checkbox>
          <v-select
            label="Type of subscription"
            v-model="selected.new_subscribe"
            :items="[
              {
                text: '1 month',
                value: 2620000,
              },
              {
                text: '6 months',
                value: 15700000,
              },
            ]"
          ></v-select>

          <v-checkbox
            v-model="selected.new_subscribe_games_flag"
            label="Give free games"
          ></v-checkbox>
          <v-text-field
            type="number"
            v-model="selected.new_subscribe_games"
            label="Number of games"
          ></v-text-field>
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
        edit: false,
      },
      data: [],
      new_subscribe: false,
      new_subscribe_games: false,
      search: '',
    };
  },
  methods: {
    updateNotifications(item, type) {
      item.notification[type] = !item.notification[type];
      axios.put('/admin/user/' + item.id_user + '/notification', item.notification);
    },
    sync() {
      axios.get('/admin/user').then(res => {
        this.data = res.data.map(n => {
          n.subscribe_interval = parseInt(n.subscribe_interval);
          n.subscribe = parseInt(n.subscribe);
          return n;
        });
      });
    },
    edit(item) {
      axios.get('/admin/user/' + item.id_user).then(res => {
        this.selected = res.data;
        this.selected.new_subscribe = null;
        this.selected.new_subscribe_games = null;
        this.selected.new_subscribe_flag = false;
        this.selected.new_subscribe_games_flag = false;
        this.dialog.edit = true;
      });

    },
    update() {
      axios.put('/admin/user/' + this.selected.id_user, this.selected).then(res => {
        this.sync();
        this.dialog.edit = false;
      });
    },
    remove() {
      axios.delete('/admin/user/' + this.selected.id_user).then(res => {
        this.sync();
        this.dialog.edit = false;
      });
    },
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
.notification {
  text-decoration: underline;
  font-weight: bold;
  color: blue;
  cursor: pointer;
}

</style>
