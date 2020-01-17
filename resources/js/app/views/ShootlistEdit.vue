<template lang="html">
  <v-container>

    <v-layout class="py-3" row wrap>
      <v-flex xs6>

      </v-flex>
      <v-flex>
        <small>PLAYED</small>
        <p>{{ data.played }}</p>
      </v-flex>
      <v-flex>
        <small>SKIPPED</small>
        <p>{{ data.skipped }}</p>
      </v-flex>
      <v-flex>
        <small>LIKED</small>
        <p>{{ data.liked }}</p>
      </v-flex>
      <v-flex>
        <small>CREATED</small>
        <p>{{ new Date(data.date * 1000) | dateFormat('DD.MM.YY') }}</p>
      </v-flex>
    </v-layout>

    <v-layout row class="pb-1 pt-3">
      <v-flex xs9>
        <v-layout align-start column justify-center fill-height>
          <h2>Shootlist game</h2>
          <v-switch
            v-model="data.show"
            label="VISIBLE"
          />
        </v-layout>
      </v-flex>
      <v-flex xs3>
        <v-layout align-center justify-end>
          <v-btn color="error" @click="dialog.delete = true" text>DELETE</v-btn>
          <v-btn color="success" @click="save">SAVE</v-btn>
        </v-layout>
      </v-flex>
    </v-layout>

    <v-divider/>

    <v-form class="pt-4">
      <v-text-field
        label="Order index"
        outline
        v-model="data.order_index"
        type="number"
      ></v-text-field>
      <v-textarea
        label="Question(Female)"
        outline
        v-model="data.content_fem"
      ></v-textarea>
      <v-textarea
        label="Question(Male)"
        outline
        v-model="data.content_man"
      ></v-textarea>
      <div class="display-1 mb-2">Category</div>
      <v-divider/>
      <v-radio-group required v-model="data.category">
        <v-radio
          label="מי השבוע"
          :value="1"
        ></v-radio>
        <v-radio
          label="טריווה"
          :value="2"
        ></v-radio>
        <v-radio
          label="נועז"
          :value="3"
        ></v-radio>
        <v-radio
          label="!?וואלה"
          :value="4"
        ></v-radio>
        <v-radio
          label="קליל"
          :value="5"
        ></v-radio>
      </v-radio-group>
      <template v-if="data.category == 2">
        <div class="display-1 mb-2">Answers</div>
        <v-divider/>
        <v-text-field
          label="First answer"
          outline
          v-model="data.answers.first"
        ></v-text-field>
        <v-text-field
          label="Second answer"
          outline
          v-model="data.answers.second"
        ></v-text-field>
        <v-text-field
          label="Third answer"
          outline
          v-model="data.answers.third"
        ></v-text-field>
        <h4>The right answer is:</h4>
        <v-select
          v-model="data.answers.right"
          :items="[
            {
              text: 'First',
              value: 1,
            },
            {
              text: 'Second',
              value: 2,
            },
            {
              text: 'Third',
              value: 3,
            }
          ]"
          label="Right answer"
          solo
          small
        ></v-select>
      </template>

      <div class="display-1 mb-2">For:</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-radio-group v-model="data.for_type">
          <v-radio
            label="מעורב"
            value="1"
          ></v-radio>
          <v-radio
            label="ילדים"
            value="2"
          ></v-radio>
          <v-radio
            label="בוגרים"
            value="3"
          ></v-radio>
        </v-radio-group>
      </v-layout>

      <v-layout row align-start class="pt-4">
        <v-text-field @input="searchUser" label="User name" v-model="user" type="text" solo></v-text-field>
        <v-select
          label="user"
          v-model="data.id_author"
          :items="users"
          item-text="name"
          item-value="id_user"
          solo
        ></v-select>
      </v-layout>
      <v-layout row align-start class="pt-4">
        <v-text-field @input="searchSchool" label="School" v-model="school" type="text" solo></v-text-field>
        <v-select
          label="School"
          v-model="data.id_school"
          :items="schools"
          item-text="name"
          item-value="id_school"
          solo
        ></v-select>
      </v-layout>
      <v-layout row align-start class="pt-4">
        <v-text-field label="City" v-model="data.city" type="text" solo></v-text-field>
      </v-layout>
    </v-form>


    <v-snackbar
      v-model="alert.update"
      right
      top
      :timeout="2000"
    >
      Updated.
      <v-btn
        color="pink"
        flat
        @click="alert.update = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <v-snackbar
      v-model="alert.delete"
      right
      top
      :timeout="2000"
    >
      Deleted.
      <v-btn
        color="pink"
        flat
        @click="alert.delete = false"
      >
        Close
      </v-btn>
    </v-snackbar>

    <v-dialog
      v-model="dialog.delete"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Delete ensurance
        </v-card-title>

        <v-card-text>
          Do you realy want to delete it?
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="default"
            flat
            @click="dialog.delete = false"
          >
            Cancel
          </v-btn>
          <v-btn
            color="error"
            flat
            @click="remove"
          >
            Yes, delete it
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
      data: {
        answers: {
          right: 1,
          first: '',
          second: '',
          third: '',
        }
      },
      alert: {
        update: false,
        delete: false,
      },
      dialog: {
        delete: false,
      },
      user: '',
      users: [],
      school: '',
      schools: [],
    };
  },
  methods: {
    searchUser() {
      if(!this.user.length) return;
      axios.post('/user/search', {
        name: this.user
      }).then(res => {
        this.users = res.data.map(n => {
          n.name = n.name + ' ' + n.surname;
          return n;
        });
        this.users.unshift({
          name: 'Empty',
          id_user: null,
        });
      });
    },
    sync() {
      axios.get('/shootlist/' + this.$route.params.id_shootlist + '/full').then(res => {
        this.data = res.data;
        if(res.data.author_info) {
          this.users.push({
            name: 'Empty',
            id_user: null,
          });
          this.users.push(res.data.author_info);
        }
        if(res.data.school_info) {
          this.schools.push({
            name: 'Empty',
            id_school: null,
          });
          this.schools.push(res.data.school_info);
        }
      });
    },
    save() {
      axios.put('/shootlist/' + this.$route.params.id_shootlist, this.data).then(res => {
        this.alert.update = true;
      });
    },
    remove() {
      axios.delete('/shootlist/' + this.$route.params.id_shootlist).then(res => {
        this.dialog.delete = false;
        this.alert.delete = true;
        this.$router.push('/shootlist');
      })
    },
    searchSchool() {
      if(!this.school.length) return;
      axios.post('/school/search', {
        name: this.school,
      }).then(res => {
        this.schools = res.data;
        this.schools.unshift({
          name: 'Empty',
          id_school: null,
        });
      });
    },
  },
  created() {
    this.sync();
  }
}
</script>

<style lang="scss" scoped>

</style>
