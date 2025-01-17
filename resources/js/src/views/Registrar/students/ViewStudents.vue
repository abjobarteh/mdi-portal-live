<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Students</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn icon @click="showSearchDialog">
            <fas icon="search"></fas>
          </v-btn>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="red" small class="white--text" @click="announce">Announcements</v-btn>
        </v-toolbar>

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

        <v-card-text>
          <v-data-table :headers="headers" :items="students" :items-per-page="500" :search="search" class="elevation-1"
            hide-default-footer>
            <template v-slot:[`item.action`]="{ item }">
              <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                <v-btn medium style="flex: 1 1 auto;" color="primary" @click="showStudent(item)">View</v-btn>
                <v-btn medium style="flex: 1 1 auto;" color="green" @click="showprogram(item)">Edit Student</v-btn>
                <v-btn large style="flex: 1 1 auto;" color="error" @click="newprogram(item)">New Program</v-btn>
              </div>
            </template>
            <template v-slot:[`item.fullname`]="{ item }">
              <span style="font-size: small">{{ item.firstname + ' ' + item.lastname }} </span></template>
            <template v-slot:[`item.program`]="{ item }">
              <span style="font-size: smaller">{{ item.program ? item.program.name : 'N/A' }}</span>
            </template>

            <template v-slot:[`item.admission_year`]="{ item }">
              {{ item.mat_number.toString().substring(0, 4) }}
            </template>
            <template v-slot:[`item.acceptance_status`]="{ item }">
              {{ getAcceptanceStatusLabel(item.acceptance_status) }}
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
      <v-dialog v-model="editstudentprog" max-width="650px">
        <v-card>
          <v-card-title> Edit Student Program</v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-select outlined v-model="progs.program_id"
                :items="programs.map(program => ({ id: program.id, name: program.name }))" item-value="id"
                item-text="name" label="Program" :rules="[v => !!v || 'A Program is required']"></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="green" @click="editprogram">Edit Program</v-btn>
            <v-btn color="secondary" @click="closebtn">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
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
      advanceSearchLabel: '',
      advanceSearch: '',
      searchDialog: false,
      selectedItem: null,
      editstudentprog: false,
      searchDialog: false,
      selectedItem: null,
      deptcourses: [],
      depts: [],
      studentid: '',
      programId: '',
      studentId: '',
      progs: [],
      studentId: null,                // Student ID for operations
      programId: null,
      studentid: null,
      user_id: null,
      editstudentprog: false,
      programs: [],
      progcourses: [],
      programs: [],
      courses: [],
      items: [
        // Your dropdown items here
        { text: 'Student Number', value: '1' },
        { text: 'First Name', value: '2' },
        { text: 'Middle Name', value: '3' },
        { text: 'Last Name', value: '4' },
        { text: 'Email', value: '5' },
        { text: 'Program', value: '6' },
        { text: 'Gender', value: '7' },
        { text: 'Nationality', value: '8' },
        { text: 'Department', value: '9' },
      ],
      students: [],
      semesters: [],
      headers: [
        { text: 'Action', value: 'action', sortable: false },
        { text: 'Fullname', value: 'fullname' },
        { text: 'Student Number', value: 'mat_number' },
        { text: 'Program', value: 'program' },
        { text: 'Department', value: 'department.name' },
        { text: 'Admission Year', value: 'admission_year' },
        { text: 'Email', value: 'email' },
        { text: 'Nationality', value: 'nationality' },
        { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Acceptance Status', value: 'acceptance_status' },
        { text: 'Emergency Contact Name', value: 'eme_name' },
        { text: 'Emergency Contact Number', value: 'eme_numbr' },
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

  watch: {
    selectedItem(newValue) {
      // Find the corresponding text based on the selected value
      const selectedItemObject = this.items.find(item => item.value === newValue)

      // Update the label with the text associated with the selected value
      this.advanceSearchLabel = selectedItemObject ? 'search by ' + selectedItemObject.text.toLowerCase() : ''
    },
  },

  created() {
    this.setupValidation()
    this.getResults()
  },
  computed: {
    // Add a computed property to map the acceptance status
    getAcceptanceStatusLabel() {
      return (status) => {
        if (status == '1') {
          return 'Full';
        } else if (status == '0') {
          return 'Conditional';
        } else {
          return 'Unknown';
        }
      }
    }
  },
  methods: {
    showSearchDialog() {
      this.searchDialog = true
    },
    closeSearchDialog() {
      this.searchDialog = false
    },
    performAdvancedSearch() {
      // Implement your advanced search logic here
      console.log('Performing advanced search...')
      // Close the dialog after searching
      axios
        .get('/api/search-student', {
          params: {
            page: this.page,
            advanceSearch: this.advanceSearch,
            selectedItem: this.selectedItem, // Add the selectedItem here
          },
        })
        .then(response => {
          this.students = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.courses = []
          this.pageCount = 0
        })
      this.closeSearchDialog()
    },
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addPaymentFormData)
    },
    closebtn() {
      this.editstudentprog = false;
      this.programs = []
    },
    showprogram(item) {
      this.editstudentprog = true
      this.studentid = item.id
      axios
        .get('/api/get-programs')
        .then(response => {
          this.programs = response.data.result.data
        })
        .catch(err => {
          this.programs = []
        })
    },
    newprogram(item) {
      console.log(item.id);
      console.log(item.user_id)
      this.studentid = item.id;
      this.user_id = item.user_id;
      this.isLoading = true; // Add a loading indicator
      axios
        .post('/api/new-program', {
          id: this.studentid,
          user_id: this.user_id
        })
        .then(response => {
          swal.fire({
            title: 'Success!',
            text: response.data.result || 'Student Can Now Apply For A New Program',
            icon: 'success',
            confirmButtonText: 'OK',
          }).then(() => {
            this.getResults(); // Refresh or fetch updated data
          });
        })
        .catch(error => {
          swal.fire({
            title: 'Error!',
            text: error.response?.data?.errorMessage || 'An unexpected error occurred',
            icon: 'error',
            confirmButtonText: 'OK',
          });
        })
        .finally(() => {
          this.isLoading = false; // Reset the loading indicator
        });
    }
    ,

    editprogram() {
      // Validate the form
      this.$refs.form.validate();

      // Log values
      console.log('Program ID', this.progs.program_id);
      console.log('Student ID', this.studentid);

      // Ensure axios is properly used to make a POST request
      axios.post('/api/change-program', {
        programId: this.progs.program_id,
        studentId: this.studentid
      })
        .then(result => {
          // Check if the update was successful
          if (result.data.success) {
            this.editstudentprog = false;
            this.programs = [];

            swal.fire({
              title: 'Success!',
              text: 'Program Edited Successfully',
              icon: 'success',
              confirmButtonText: 'OK',
            }).then(() => {
              this.getResults(); // Refresh or fetch updated data
            });
          } else if (result.data.error) {
            // Handle specific error cases
            swal.fire({
              title: 'Error!',
              text: result.data.errorMessage || 'Failed to Edit Program',
              icon: 'error',
              confirmButtonText: 'OK',
            });
          }
        })
        .catch(error => {
          // Handle network or server errors
          swal.fire({
            title: 'Error!',
            text: 'Failed to Edit Program',
            icon: 'error',
            confirmButtonText: 'OK',
          });
        });
    },
    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.students)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Students')
      XLSX.writeFile(workbook, 'students.xlsx')
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
              .post('/api/announce-student', {
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
