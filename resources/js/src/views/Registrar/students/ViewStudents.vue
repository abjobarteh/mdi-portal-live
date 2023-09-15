<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Students</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="students"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="showStudent(item)">View</v-btn>
              <v-btn small color="error" @click="deleteLecturer(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>
<script>
import * as XLSX from 'xlsx'
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'
import useVuelidate from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'

Vue.use(Vue2Filters)

export default {
  name: 'Students',
  props: {},
  components: {},
  data() {
    return {
      students: [],
      semesters: [],
      headers: [
        { text: 'Firstname', value: 'firstname' },
        { text: 'Lastname', value: 'lastname' },
        { text: 'username', value: 'username' },
        { text: 'Mat Number', value: 'mat_number' },
        { text: 'Email', value: 'email' },
        { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      page: 1,
      pageCount: 0,
      search: '',

      rules: {
        lecturer_id: { required },
        semester_id: { required },
      },

      v$: null,
    }
  },

  created() {
    this.setupValidation()
    this.getResults()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addPaymentFormData)
    },
    getResults() {
      axios
        .get('/api/view-students?page=' + this.page)
        .then(response => {
          this.students = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.students = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-semesters?page=' + this.page)
        .then(response => {
          this.semesters = response.data.result.data
          this.addPaymentFormData.semester_id = this.semesters[this.semesters.length - 1].id
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.semesters = []
          this.pageCount = 0
        })
    },

    showStudent(item) {
      console.log('id ', item)
      this.$router.push('/student-detail/' + item.user_id)
    },
  },
}
</script>











