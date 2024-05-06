<template>
  <div>
    <v-container fluid>
      <v-card>
        <div class="text-center">
          <v-card-text>
            <h2 class="mb-4 p-2">My Department Lecturers</h2>
            <v-data-table
              :headers="headers"
              :items="lecturers"
              :items-per-page="13"
              :search="search"
              class="elevation-1"
              hide-default-footer
            >
            </v-data-table>
            <v-pagination v-model="page" :length="pageCount" @input="getResults" />
          </v-card-text>
        </div>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vue2Filters)

export default {
  name: 'HodLecturers',
  props: {},
  components: {},
  data() {
    return {
      lecturers: [],

      headers: [
        { text: 'Firstname', value: 'firstname' },
        { text: 'Lastname', value: 'lastname' },
        { text: 'Email', value: 'email' },
        { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Lecturer Type', value: 'lecturer_type' },
      ],
      page: 1,
      pageCount: 0,
      search: '',

      v$: null,
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-hod-lecturers?page=' + this.page)
        .then(response => {
          this.lecturers = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.lecturers = []
          this.pageCount = 0
        })
    },
  },
}
</script>











