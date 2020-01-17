<template lang="html">
  <v-container>
    <v-layout class="py-3" row wrap>
      <v-flex xs6>
        <h1>{{ data.name }}</h1>
        <img :src="data.img" alt="">
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

    <v-layout row class="py-3">
      <v-flex xs9>
        <v-layout align-start column justify-center fill-height>
          <h2>Playlist game</h2>
          <v-switch
            v-model="data.show"
            label="VISIBLE"
          />
        </v-layout>
        <input v-show="false" ref="file" type="file" @change="updateImage">
      </v-flex>
      <v-flex xs3>
        <v-layout align-center justify-end>
          <v-btn color="error" @click="dialog.delete = true" text>DELETE</v-btn>
          <v-btn color="success" @click="save">SAVE</v-btn>
        </v-layout>
      </v-flex>
    </v-layout>

    <v-layout justify-end>
      <v-btn small color="info" @click="$refs.file.click()">
        Upload image
      </v-btn>
    </v-layout>

    <v-divider></v-divider>

    <v-layout row class="py-3">
      <v-flex xs9>
        <v-layout align-start column justify-center fill-height>
          <h2>Audio file</h2>
          <v-switch
                  v-model="data.show"
                  label="VISIBLE"
          />
        </v-layout>
        <input v-show="false" ref="audio" type="file" @change="updateAudio">
      </v-flex>
      <v-flex xs3>
        <v-layout align-center justify-end>
          <v-btn color="error" @click="dialog.delete = true" text>DELETE</v-btn>
          <v-btn color="success" @click="save">SAVE</v-btn>
        </v-layout>
      </v-flex>
    </v-layout>

    <v-layout justify-end>
      <v-btn small color="info" @click="$refs.audio.click()">
        Upload audio
      </v-btn>
    </v-layout>

    <v-divider></v-divider>

    <v-form class="pt-4">
      <v-text-field label="Order index" type="number" outline v-model="data.order_index"/>

      <v-text-field label="Game name" v-model="data.name" outline></v-text-field>

      <div class="display-1 mb-2">Strips</div>
      <v-divider/>
      <div class="pt-4">
        <v-textarea label="Strip 1" v-model="data.question_1" box></v-textarea>
        <v-textarea label="Strip 2" v-model="data.question_2" box></v-textarea>
        <v-textarea label="Strip 3" v-model="data.question_3" box></v-textarea>
      </div>

      <v-text-field label="Video embed frame" v-model="data.video" outline></v-text-field>

      <div class="display-1 mb-2">
        Additional questions
        <v-switch
          v-model="data.additional"
          label="Enable"
        />
      </div>
      <v-divider/>
      <div class="pt-4">
        <v-textarea label="Question 1" v-model="data.question_4" box></v-textarea>
        <v-textarea label="Question 2" v-model="data.question_5" box></v-textarea>
      </div>

      <div class="display-1 mb-2">Ingame gifts</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-btn to="/gifts" color="success">
          <v-icon>add</v-icon>
          add new gift
        </v-btn>
        <v-select
          label="In card gifts"
          v-model="data.categories"
          chips
          :items="categories"
          item-text="name"
          item-value="id_category"
          solo
          multiple
        ></v-select>
      </v-layout>

      <div class="display-1 mb-2">Locations</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-btn to="/playlist/locations" color="success">
          <v-icon>add</v-icon>
          add new location
        </v-btn>
        <v-select
          label="Locations"
          v-model="data.locations"
          chips
          :items="locations"
          item-text="name"
          item-value="id_setting"
          solo
          multiple
        ></v-select>
      </v-layout>

      <div class="display-1 mb-2">Number players</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-text-field label="Players minimum" v-model="data.min_players" type="number" outline></v-text-field>
        <v-spacer/>
        <v-text-field label="Players maximum" v-model="data.max_players" type="number" outline></v-text-field>
      </v-layout>

      <div class="display-1 mb-2">Age</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-text-field label="Age minimum" v-model="data.min_age" type="number" outline></v-text-field>
        <v-spacer/>
        <v-text-field label="Age maximum" v-model="data.max_age" type="number" outline></v-text-field>
      </v-layout>

      <div class="display-1 mb-2">Section</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-select
          label="Section"
          v-model="data.section"
          :items="[
            {
              text: 'ילדים',
              value: 'chl'
            },
            {
              text: 'מבוגר',
              value: 'adl'
            },
            {
              text: 'מעורב',
              value: 'mix'
            },
          ]"
          solo
        ></v-select>
      </v-layout>

      <div class="display-1 mb-2">Gender</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-radio-group v-model="data.gender">
          <v-radio
            label="MALE"
            value="man"
          ></v-radio>
          <v-radio
            label="FEMALE"
            value="fml"
          ></v-radio>
          <v-radio
            label="BOTH"
            value="two"
          ></v-radio>
        </v-radio-group>
      </v-layout>

      <div class="display-1 mb-2">Level</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-radio-group v-model="data.level">
          <v-radio
            label="hard"
            value="1"
          ></v-radio>
          <v-radio
            label="harder"
            value="2"
          ></v-radio>
          <v-radio
            label="the hardest"
            value="3"
          ></v-radio>
        </v-radio-group>
      </v-layout>

      <div class="display-1 mb-2">Credit</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-text-field @input="searchUser" label="User name" v-model="user" type="text" solo></v-text-field>
        <v-select
          label="User"
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

      <div class="display-1 mb-2">Teacher guide </div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-textarea label="Teacher guide" v-model="data.teachers_guide" box></v-textarea>
      </v-layout>

      <div class="display-1 mb-2">Is Recommended</div>
      <v-divider/>
      <v-layout row align-start class="pt-4">
        <v-checkbox label="Is recommended" v-model="data.is_recommended" box></v-checkbox>
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
      data: [],
      test: null,
      categories: [],
      sections: [],
      school: '',
      category: {
        name: '',
        id_section: '',
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
      schools: [],
      locations: [],
    };
  },
  methods: {
    updateImage() {
      var data = new FormData();
      data.append('file', this.$refs.file.files[0]);
      axios.post('/playlist/' + this.$route.params.id_playlist + '/image', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        this.data.img = res.data.url;
      });
    },
    updateAudio() {
      var data = new FormData();
      data.append('audio', this.$refs.audio[0]);
      axios.post('/playlist/' + this.$route.params.id_playlist + '/audio', data, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      }).then(res => {
        this.data.audio = res.data.url;
      });
    },
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
      axios.get('/playlist/' + this.$route.params.id_playlist + '/full').then(res => {
        this.data = res.data;
        if(res.data.author_info) {
          this.users.push({
            name: 'Empty',
            id_school: null,
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
    syncCategories() {
      axios.get('/playlist/categories').then(res => {
        this.categories = res.data;
      });
    },
    syncSections() {
      axios.get('/playlist/category/sections').then(res => {
        this.sections = res.data;
      });
    },
    syncLocations() {
      axios.get('/playlist/settings').then(res => {
        this.locations = res.data;
      });
    },
    save() {
      axios.put('/playlist/' + this.$route.params.id_playlist, this.data).then(res => {
        this.alert.update = true;
      });
    },
    addCategory() {
      axios.post('/playlist/category', {
        name: this.category.name,
        id_section: this.category.id_section,
      }).then(res => {
        this.syncCategories();
        this.dialog.gift = false;
      });
    },
    remove() {
      axios.delete('/playlist/' + this.$route.params.id_playlist).then(res => {
        this.dialog.delete = false;
        this.alert.delete = true;
        this.$router.push('/playlist');
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
    this.syncSections();
    this.syncCategories();
    this.syncLocations();
  }
}
</script>

<style lang="scss" scoped>


.header {
  padding: 10px;
}
.main {
  padding-top: 20px;
}
.title {
  text-transform: uppercase;
}
.col {
  padding: 10px 20px;
}
.desc {
  text-transform: uppercase;
  font-weight: 500;
  color: silver;
}

</style>
