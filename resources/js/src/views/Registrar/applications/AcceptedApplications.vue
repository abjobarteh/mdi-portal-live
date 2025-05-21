<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark dense>
          <v-toolbar-title>Accepted Applications</v-toolbar-title>
          <v-spacer></v-spacer>

          <v-select
            v-model="selectedSemester"
            :items="semesters"
            item-value="semester_name"
            item-text="semester_name"
            label="Select To View All Accepted Students Per Semester"
            dense
            hide-details
            solo-inverted
            flat
            class="mx-3"
            style="max-width: 500px"
            @change="onSemesterChange"
          ></v-select>

          <v-text-field
            v-model="search"
            label="Search"
            append-icon="mdi-magnify"
            clearable
            hide-details
          ></v-text-field>

          <v-btn icon @click="showSearchDialog">
            <fas icon="search"></fas>
          </v-btn>
        </v-toolbar>

        <!-- Data Table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="acceptedApplications"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="viewApplicationData(item)">View</v-btn>
            </template>
          </v-data-table>

          <v-pagination
            v-model="page"
            :length="pageCount"
            @input="getResults"
          />
        </v-card-text>

        <!-- Advanced Search Dialog -->
        <v-dialog v-model="searchDialog" max-width="400">
          <v-card>
            <v-card-title>Advanced Search</v-card-title>
            <v-card-text>
              <v-select v-model="selectedItem" :items="items" label="Search by Item"></v-select>
              <v-text-field
                v-model="advanceSearch"
                :label="advanceSearchLabel"
                append-icon="mdi-magnify"
                clearable
                hide-details
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" :disabled="!selectedItem || !advanceSearch" @click="performAdvancedSearch">
                Search
              </v-btn>
              <v-btn @click="closeSearchDialog">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
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
  name: 'AcceptedApplications',

  data() {
    return {
      acceptedApplications: [],
      semesters: [],
      selectedSemester: null,
      searchDialog: false,
      selectedItem: null,
      advanceSearch: '',
      advanceSearchLabel: '',
      items: [
        { text: 'Username', value: '1' },
        { text: 'First Name', value: '2' },
        { text: 'Middle Name', value: '3' },
        { text: 'Last Name', value: '4' },
        { text: 'Email', value: '5' },
        { text: 'Semester Applied For', value: '6' },
      ],
      headers: [
        { text: 'Firstname', value: 'firstname' },
        { text: 'Middlename', value: 'middlename' },
        { text: 'Lastname', value: 'lastname' },
        { text: 'Email', value: 'email' },
        { text: 'Program', value: 'program_name' },
        { text: 'Semester Applied For', value: 'semester_name' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      page: 1,
      pageCount: 0,
      search: '',
      v$: useVuelidate(),
    }
  },

  created() {
    this.getResults()
    this.fetchSemesters()
  },

  watch: {
    selectedSemester(newValue) {
      if (!newValue) {
        this.page = 1
        this.getResults()
      }
    }
  },

  methods: {
    fetchSemesters() {
      axios.get('/api/view-semester')
        .then(response => {
          this.semesters = response.data.result
        })
        .catch(err => {
          console.error('Error fetching semesters:', err)
        })
    },

    getResults() {
      if (this.selectedSemester) {
        this.fetchSemesterResults()
      } else {
        axios.post(`/api/view-accepted-applications?page=${this.page}`)
          .then(response => {
            this.acceptedApplications = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            console.error('Error fetching applications:', err)
            this.acceptedApplications = []
            this.pageCount = 0
          })
      }
    },

    fetchSemesterResults() {
      axios.get(`/api/view-accepted-per-semester/${this.selectedSemester}?page=${this.page}`)
        .then(response => {
          this.acceptedApplications = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(error => {
          console.error('Error fetching semester data:', error)
          this.acceptedApplications = []
          this.pageCount = 0
        })
    },

    onSemesterChange() {
      this.page = 1
      this.fetchSemesterResults()
    },

    showSearchDialog() {
      this.searchDialog = true
    },

    closeSearchDialog() {
      this.searchDialog = false
    },

    performAdvancedSearch() {
      axios
        .get('/api/search-accepted-applicant', {
          params: {
            page: this.page,
            advanceSearch: this.advanceSearch,
            selectedItem: this.selectedItem,
          },
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token')
          }
        })
        .then(response => {
          if (response.data.result) {
            this.acceptedApplications = response.data.result.data
            this.pageCount = response.data.result.last_page
          } else {
            this.acceptedApplications = []
            this.pageCount = 0
          }
        })
        .catch(err => {
          console.error('Error fetching search results:', err)
          this.acceptedApplications = []
          this.pageCount = 0
        })

      this.closeSearchDialog()
    },

    viewApplicationData(item) {
      this.$router.push({
        name: 'view-application-preview',
        params: { id: item.user_id },
        query: { param: 'accepted' },
      })
    },
  },
}
</script>
