<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Incoming Applications</v-toolbar-title>

          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn icon @click="showSearchDialog">
            <fas icon="search"></fas>
          </v-btn>
          <v-btn color="red" small class="white--text" @click="announce">Announcements</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table :headers="headers" :items="incomingApplications" :items-per-page="13" :search="search"
            class="elevation-1" hide-default-footer>
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="viewApplicationData(item)">View</v-btn>
            </template>
            <template v-slot:[`item.checker`]="{ item }">
              <v-btn small color="primary" :href="'https://app.waecgambia.org/resultchecker/resultchecker.aspx'"
                target="_blank">
                Checker
              </v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>

        <v-dialog v-model="searchDialog" max-width="400">
          <v-card>
            <v-card-title>Advanced Search</v-card-title>
            <v-card-text>
              <!-- Add your dropdown or any additional search options here -->
              <v-select v-model="selectedItem" :items="items" label="Search by Item"></v-select>
              <v-text-field v-model="advanceSearch" :label="advanceSearchLabel" append-icon="mdi-magnify" clearable
                hide-details></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" :disabled="selectedItem == null || advanceSearch === ''"
                @click="performAdvancedSearch">Search</v-btn>
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
  name: 'IncomingApplications',
  props: {},
  components: {},
  data() {
    return {
      incomingApplications: [],
      advanceSearchLabel: '',
      advanceSearch: '',
      searchDialog: false,
      selectedItem: null,
      selectedItem: null,
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
        { text: 'Result Checker', value: 'checker', sortable: false },
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
        .post('/api/view-incoming-applications?page=' + this.page)
        .then(response => {
          this.incomingApplications = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          console.log('applications')
          this.incomingApplications = []
          this.pageCount = 0
        })
    },
    showSearchDialog() {
      this.searchDialog = true
    },
    closeSearchDialog() {
      this.searchDialog = false
    },
    performAdvancedSearch() {
      console.log('Performing advanced search...');

      axios
        .get('/api/search-incoming-applicant', {
          params: {
            page: this.page,
            advanceSearch: this.advanceSearch,
            selectedItem: this.selectedItem,
          },
          headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('token') // Ensure auth token is included
          }
        })
        .then(response => {
          if (response.data.result) {
            this.incomingApplications = response.data.result.data;
            this.pageCount = response.data.result.last_page;
          } else {
            this.students = [];
            this.pageCount = 0;
          }
        })
        .catch(err => {
          console.error('Error fetching search results:', err);
          this.students = [];
          this.pageCount = 0;
        });

      this.closeSearchDialog();
    },
    announce() {
      swal
        .fire({
          title: 'Announcement',
          icon: 'info',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Send',
          input: 'textarea', // Adding textarea input field
          inputPlaceholder: 'Enter your message here...(optional)', // Placeholder for the textarea
          inputAttributes: {
            'aria-label': 'Type your message here', // Accessibility label
          },
        })
        .then(result => {
          if (result.isConfirmed) {
            this.isLoading = true
            let message = result.value // Getting the value entered in the textarea

            axios
              .post('/api/announce-applicant', {
                message: message, // Sending the message along with studentId
              })
              .then(response => {
                this.isLoading = false
                // Show success alert after successful revert
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Announcement Sent Successfully',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.$router.go(-1)
                    this.getResults()
                  })
              })
              .catch(error => {
                this.isLoading = false
                // Show error alert if revert fails
                swal.fire({
                  title: 'Error!',
                  text: 'Failed to Send.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
          }
        })
    },
    viewApplicationData(item) {
      console.log(item)
      this.$router.push({
        name: 'view-application-preview',
        params: { id: item.user_id },
        query: { param: 'incoming' },
      })
    },
  },
}
</script>
