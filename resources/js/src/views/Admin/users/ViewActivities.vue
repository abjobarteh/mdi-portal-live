<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Activities</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        </v-toolbar>
        <!-- properties.attributes.role_id -->
        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="activities"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.usertype`]="{ item }">
              {{
                item.properties.attributes.role_id == 2
                  ? 'Registered'
                  : item.properties.attributes.role_id == 1
                  ? 'Admin'
                  : item.properties.attributes.role_id == 4
                  ? 'Student'
                  : item.properties.attributes.role_id == 3
                  ? 'Lecturer'
                  : item.properties.attributes.role_id == 5
                  ? 'Finance'
                  : ''
              }}
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

Vue.use(Vue2Filters)

export default {
  name: 'User',
  props: {},
  components: {},
  data() {
    return {
      activities: [],
      headers: [
        { text: 'Firstname', value: 'properties.attributes.firstname' },
        { text: 'Lastname', value: 'properties.attributes.lastname' },
        { text: 'Description', value: 'description' },
        { text: 'Usertype', value: 'usertype' },
        { text: 'DateTime', value: 'date' },
      ],
      page: 1,
      pageCount: 0,
      search: '',
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-activities?page=' + this.page)
        .then(response => {
          this.activities = response.data.result.data
          console.log('activites ', this.activities)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.users = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.users)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'users')
      XLSX.writeFile(workbook, 'users.xlsx')
    },
  },
}
</script>











