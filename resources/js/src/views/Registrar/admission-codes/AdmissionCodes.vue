
<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Admission Codes</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="admissionCodes"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="error" @click="soldAdmissionCode(item)">Delete</v-btn>
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
  name: 'AdmissionCodes',
  components: {},

  data() {
    return {
      admissionCodes: [],
      headers: [
        { text: 'Issued To', value: 'location_name' },
        { text: 'Semester', value: 'semester' },
        { text: 'Total Admission Codes', value: 'total_number' },
        { text: 'Total Sold', value: 'total_sold' },
        { text: 'Total Remains', value: 'total_remains' },

        { text: 'Action', value: 'action', sortable: false },
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
    soldAdmissionCode() {
      console.log(this.$route.params.admissionCodes)
    },
    getResults() {
      axios
        .get('/api/view-admission_codes_locations?page=' + this.page)
        .then(response => {
          this.admissionCodes = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.admissionCodes = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.admissionCodes)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'admissionCodes')
      XLSX.writeFile(workbook, 'admissionCodes.xlsx')
    },
  },

  // computed: {
  //   filteredAdmissionCodesLocations() {
  //     return this.admissionCodes.filter(admissionCodes => {
  //       return admissionCodes.name.toLowerCase().includes(this.search.toLowerCase())
  //     })
  //   },
  // },
}
</script>











