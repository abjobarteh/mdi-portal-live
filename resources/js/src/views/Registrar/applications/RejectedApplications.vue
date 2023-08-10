<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Rejected Applications</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="rejectedApplications"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="viewApplicationData(item)">View</v-btn>
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

Vue.use(Vue2Filters)

export default {
  name: 'IncomingApplications',
  props: {},
  components: {},
  data() {
    return {
      rejectedApplications: [],
      headers: [
        { text: 'Firstname', value: 'firstname' },
        { text: 'Lastname', value: 'lastname' },
        { text: 'Email', value: 'email' },
        { text: 'Program', value: 'program_name' },

        { text: 'Action', value: 'action', sortable: false },
      ],
      dialog: false,
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      search: '',

      v$: useVuelidate(this.rules, this.addSessionFormData),
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addSessionFormData)
    },
    getResults() {
      axios
        .post('/api/view-rejected-applications?page=' + this.page)
        .then(response => {
          this.rejectedApplications = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          console.log('applications')
          this.rejectedApplications = []
          this.pageCount = 0
        })
    },

    viewApplicationData(item) {
      console.log(item)
      this.$router.push({
        name: 'view-application-preview',
        params: { id: item.user_id },
        query: { param: 'rejected' },
      })
    },
  },
}
</script>











