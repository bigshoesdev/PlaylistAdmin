<template>
  <v-container>
      <h1>Teacher lessons</h1>

      <v-divider/>

      <v-layout>
          <v-btn @click="createLesson" text color="success">Add new lesson</v-btn>
      </v-layout>

      <v-divider/>

      <v-dialog
              v-model="editMode"
              width="500"
      >
          <v-card>
              <v-card-title
                      class="headline grey lighten-2"
                      primary-title
              >
                  Lesson
              </v-card-title>

              <input v-show="false" ref="file" type="file" @change="updateIcon" >
              <v-layout v-if="lesson.id > 0" justify-end>
                  <v-btn small color="info" @click="$refs.file.click()">
                      Upload icon
                  </v-btn>
              </v-layout>
              <v-card-text>
                  <v-text-field
                          label="Name"
                          outline
                          v-model="lesson.name"
                  ></v-text-field>
              </v-card-text>
              <v-card-text>
                  <v-text-field
                          label="Title"
                          outline
                          v-model="lesson.title"
                  ></v-text-field>
              </v-card-text>
              <v-card-text>
                  <v-text-field
                          label="Sub Title"
                          outline
                          v-model="lesson.sub_title"
                  ></v-text-field>
              </v-card-text>
              <v-card-text>
                  <v-select
                      label="Categories"
                      v-model="lesson.category"
                      :items="categories"
                      solo
                      item-text="name"
                      item-value="id"
                 ></v-select>
              </v-card-text>
              <v-card-text>
                  <v-select
                      v-model="lesson.games"
                      :items="games"
                      label="Select"
                      multiple
                      chips
                      hint="Games"
                      persistent-hint
                      item-text="name"
                      item-value="id_category"
                  ></v-select>
                  <!--v-select
                          label="Games"
                          v-model="games"
                          :items="categories"
                          solo
                          item-text="name"
                          item-value="id_category"
                  ></v-select-->
              </v-card-text>
              <v-card-text>
                  <v-checkbox
                          label="Is Recommended"
                          v-model="lesson.is_recommended"
                  ></v-checkbox>
              </v-card-text>

              <v-card-actions>
                  <v-btn
                          color="error"
                          icon
                          small
                          @click="removeLesson"
                  >
                      <v-icon>delete</v-icon>
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-btn
                          color="success"
                          flat
                          @click="updateLesson"
                  >
                      Update
                  </v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>

      <v-data-table
              v-if="data.length"
              :headers="[
                { text: 'ICON', value: 'icon' },
                { text: 'TITLE', value: 'title' },
                { text: 'NAME', value: 'name' },
                { text: 'CATEGORY', value: 'category' },
                { text: 'ACTIONS', value: '' }
              ]"
              :items="data"
      >
          <template slot="items" slot-scope="props">
              <td><img :src="props.item.icon" style="height:2rem;"/></td>
              <td>{{ props.item.title }}</td>
              <td>{{ props.item.name }}</td>
              <td>{{ props.item.category }}</td>
              <td>
                  <v-btn text @click="editLesson(props.item)" color="info">edit</v-btn>
                  <v-btn text @click="removeLesson(props.item)" color="error">delete</v-btn>
              </td>
          </template>
      </v-data-table>

      <v-divider/>

      <v-layout>
          <v-btn @click="createCategory" text color="success">Add new category</v-btn>
      </v-layout>

      <v-divider/>

      <v-dialog
              v-model="editCategoryMode"
              width="500"
      >
          <v-card>
              <v-card-title
                      class="headline grey lighten-2"
                      primary-title
              >
                  Category
              </v-card-title>
              <v-card-text>
                  <v-text-field
                          label="Name"
                          outline
                          v-model="category.name"
                  ></v-text-field>
              </v-card-text>

              <v-card-actions>
                  <v-btn
                          color="error"
                          icon
                          small
                          @click="removeCategory"
                  >
                      <v-icon>delete</v-icon>
                  </v-btn>
                  <v-spacer></v-spacer>
                  <v-btn
                          color="success"
                          flat
                          @click="updateCategory"
                  >
                      Update
                  </v-btn>
              </v-card-actions>
          </v-card>
      </v-dialog>

      <v-data-table
              v-if="categories.length"
              :headers="[
                { text: 'NAME', value: 'name' },
                { text: 'ACTIONS', value: '' }
              ]"
              :items="categories"
      >
          <template slot="items" slot-scope="props">
              <td>{{ props.item.name }}</td>
              <td>
                  <v-btn text @click="editCategory(props.item)" color="info">edit</v-btn>
                  <v-btn text @click="removeCategory(props.item)" color="error">delete</v-btn>
              </td>
          </template>
      </v-data-table>
  </v-container>
</template>

<script>
    export default {
        name: "Teachers",
        data() {
            return {
                editMode: false,
                editCategoryMode: false,
                data: [],
                search: {
                    //
                },
                categories: [],
                games: [],
                category: {
                    id: 0,
                    name: ''
                },
                lesson: {
                    id: 0,
                    title: '',
                    sub_title: '',
                    icon: '',
                    name: '',
                    category: '',
                    games: [],
                    is_recommended: false,
                }
            }
        },
        methods: {
            sync() {
                axios.get('/teachers').then(res => {
                    this.lesson = {
                        id: 0,
                        title: '',
                        sub_title: '',
                        icon: '',
                        name: '',
                        category: '',
                        games: [],
                        is_recommended: false,
                    };
                    this.category = {
                        id: 0,
                        name: ''
                    };
                    this.data = res.data.lessons || [];
                    this.categories = res.data.meta.categories;
                });
            },
            createLesson() {
                this.editMode = true;
            },
            editLesson(item) {
                axios.get('/teachers/lesson-games/' + item.id).then(res => {
                    this.lesson = item;
                    this.lesson.games = res.data;
                    this.editMode = true;
                });
            },
            updateLesson() {
                if (this.lesson.id === 0) {
                    axios.post('/teachers', this.lesson).then(res => {
                        this.data = res.data;
                    });
                } else {
                    axios.put('/teachers/' + this.lesson.id, this.lesson).then(res => {
                        this.data = res.data;
                    });
                }
                this.sync();
                this.editMode = false;
            },
            removeLesson(item) {
                axios.delete('/teachers/' + item.id).then(res => {
                    this.editMode = false;
                    this.sync();
                });
            },
            updateIcon() {
                var data = new FormData();
                data.append('file', this.$refs.file.files[0]);
                axios.post('/teachers/' + this.lesson.id + '/set-icon', data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(res => {
                    this.sync();
                });
                this.editMode = false;
            },
            createCategory() {
                this.editCategoryMode = true;
            },
            editCategory(cat) {
                this.category = cat;
                this.editCategoryMode = true;
            },
            removeCategory(cat) {
                axios.delete('/lessons/category/' + cat.id).then(res => {
                    this.categories = res.data;
                });
                this.editCategoryMode = false;
                this.sync();
            },
            updateCategory() {
                if (this.category.id === 0) {
                    axios.post('/lessons/category', this.category).then(res => {
                        this.categories = res.data;
                    });
                } else {
                    axios.put('/lessons/category/' + this.category.id, this.category).then(res => {
                        this.categories = res.data;
                    });
                }
                this.editCategoryMode = false;
                this.sync();
            }
        },
        created() {
            axios.get('/playlist/categories').then(res => {
                this.games = res.data;
            })
            this.sync();
        }
    }
</script>

<style lang="scss" scoped>

</style>
