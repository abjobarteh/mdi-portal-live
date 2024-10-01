<template>
  <div>
    <v-container fluid>
      <v-tabs v-model="activeTab" background-color="primary" fixed-tabs dark>
        <v-tab :key="1">Non Sponsored students</v-tab>
        <v-tab :key="2">Sponsored students</v-tab>

        <!-- Tab 1 Content (Students) -->
        <v-tab-item :key="1">
          <v-card>
            <v-toolbar color="primary" dark>
              <v-spacer></v-spacer>
              <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable
                hide-details></v-text-field>
              <v-btn icon @click="showSearchDialog">
                <fas icon="search"></fas>
              </v-btn>
            </v-toolbar>

            <v-card-text>
              <v-data-table :headers="headers" :items="students" :items-per-page="13" :search="search"
                class="elevation-1" hide-default-footer>
                <template v-slot:[`item.action`]="{ item }">
                  <v-btn icon @click="showAddSponsorDialog(item)">
                    <fas icon="graduation-cap" style="color: blue"></fas>
                  </v-btn>

                  <v-btn icon @click="viewStudentInfo(item)">
                    <fas icon=" fa-eye" style="color: blue; font-size: 16px"></fas>
                  </v-btn>
                </template>
                <template v-slot:[`item.fullname`]="{ item }"> {{ item.firstname + ' ' + item.lastname }} </template>
                <template v-slot:[`item.program`]="{ item }">
                  <span style="font-size: smaller">{{ item.program.name }}</span>
                </template>
                <template v-slot:[`item.admission_year`]="{ item }">
                  {{ item.mat_number.toString().substring(0, 4) }}
                </template>
              </v-data-table>
              <v-pagination v-model="page" :length="pageCount" @input="getResults" />
            </v-card-text>
          </v-card>
        </v-tab-item>

        <!-- Tab 2 Content (Another Table Sample) -->
        <v-tab-item :key="2">
          <v-card>
            <v-toolbar color="primary" dark>
              <v-spacer></v-spacer>
              <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable
                hide-details></v-text-field>
              <v-btn icon @click="showSearchDialog">
                <fas icon="search"></fas>
              </v-btn>
            </v-toolbar>

            <v-card-text>
              <v-data-table :headers="headers" :items="students" :items-per-page="13" :search="search"
                class="elevation-1" hide-default-footer>
                <template v-slot:[`item.action`]="{ item }">
                  <v-btn @click="sponsorship(item)">Sponsorship</v-btn>
                  <v-btn small color="error" @click="deleteLecturer(item)">Delete</v-btn>
                  <v-btn small @click="viewStudentInfo(item)">View</v-btn>
                </template>
                <template v-slot:[`item.fullname`]="{ item }"> {{ item.firstname + ' ' + item.lastname }} </template>
              </v-data-table>
              <v-pagination v-model="page" :length="pageCount" @input="getResults" />
            </v-card-text>
          </v-card>
        </v-tab-item>
      </v-tabs>
      <v-dialog v-model="searchDialog" max-width="400">
        <v-card>
          <v-card-title>Advanced Search</v-card-title>
          <v-card-text>
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
    </v-container>

    <!-- Another Dialog with Two Tabs -->
    <v-dialog v-model="paymentDialog" max-width="600">
      <v-tabs v-model="paymentTab">
        <v-tab :key="1">Payments</v-tab>
        <v-tab :key="2">Add Payment</v-tab>

        <!-- Tab 1 Content (Payments) -->
        <v-tab-item :key="1">
          <v-card>
            <div style="text-align: center; font-family: Arial, sans-serif; margin: 20px">
              <p style="font-size: 1.2em; margin: 10px 0">
                <span style="font-weight: bold">{{ studentFullName }}'s</span> Payments
              </p>

              <p style="font-size: 1.2em; margin: 10px 0">
                <span style="font-weight: bold">Program Balance:</span> D{{ program_remaining_balance }}
              </p>
            </div>
            <v-card-text>
              <v-data-table :headers="studentPaymentsHeaders" :items="studentPayments">
                <!-- <template v-slot:item.admission_code="{ item }">
              {{ item.admission_code }}
            </template> -->
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-tab-item>

        <!-- Tab 2 Content (Add Payment) -->
        <v-tab-item :key="2">
          <v-card>
            <v-card-title>
              <p>
                <span style="font-weight: bold">Add Payment For {{ studentFullName }}</span>
              </p>
            </v-card-title>
            <v-card-text>
              <v-form ref="addDepartmentForm">
                <v-select v-if="semestersMissing" outlined v-model="addPaymentFormData.semester_id" :items="[
                  {
                    id: semestersMissing.id,
                    name: `${semestersMissing.semester_name} (${session_start} - ${session_end})`,
                  },
                ]" item-value="id" item-text="name" label="Semester Name"></v-select>
                <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
                  v-for="error in v$.value.semester_id.$errors" :key="error.$uid">{{ error.$message }}</span>
                <v-text-field outlined v-model="addPaymentFormData.amount_paid" label="Fee"></v-text-field>
              </v-form>
            </v-card-text>
            <v-card-actions>
              <v-btn color="primary" @click="submitAddPaymentForm">Add</v-btn>
              <!-- <v-btn color="secondary" @click="addProgramDialog = false">Cancel</v-btn> -->
            </v-card-actions>
          </v-card>
        </v-tab-item>
      </v-tabs>
    </v-dialog>
    <v-dialog v-model="addSponsorDialog" max-width="500">
      <v-card>
        <v-card-title>Scholarship Information</v-card-title>
        <v-card-text>
          <v-text-field outlined v-model="scholarshipFormData.scholarshipProvider"
            label="Scholarship Provider"></v-text-field>
          <v-text-field outlined v-model="scholarshipFormData.scholarshipName" label="Scholarship Name"></v-text-field>
          <v-text-field outlined v-model="scholarshipFormData.scholarship_amount"
            label="Scholarship Amount"></v-text-field>
          <v-row>
            <v-col cols="6">
              <v-text-field type="month" v-model="scholarshipFormData.startDate" label="Start Date"></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field type="month" v-model="scholarshipFormData.endDate" label="End Date"></v-text-field>
            </v-col>
          </v-row>

          <v-file-input v-model="scholarshipFormData.uploadedFile" label="Upload File (Optional)" show-size
            @change="null"></v-file-input>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="saveScholarship">Save</v-btn>
          <v-btn @click="closeScholarshipDialog">Close</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showSponsorshipDetails" max-width="1000">
      <v-card>
        <v-card-text>
          <v-data-table :headers="sponsorshipDetailsHeader" :items="sponsorshipDetails" :items-per-page="13"
            :search="search" class="elevation-1" hide-default-footer>
            <template v-slot:[`item.award`]="{ item }">
              <v-btn @click="scholarshipAward(item.scholarship_file)">View</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import * as XLSX from 'xlsx'
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'
import useVuelidate from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'
import html2pdf from 'html2pdf.js'

import apiBaseURL from '../../../../api-config'

Vue.use(Vue2Filters)

export default {
  name: 'lecturerSystem',
  props: {},
  components: {},
  data() {
    return {
      session_start: '',
      session_end: '',
      semester_balance: '',
      userInfo: '',
      program_remaining_balance: '',
      showSponsorshipDetails: false,
      addSponsorDialog: false,
      student_id: '',
      scholarshipFormData: {
        scholarshipProvider: '',
        scholarshipName: '',
        startDate: '',
        endDate: '',
        scholarship_amount: '',
      },
      // student info dialog
      paymentDialog: false,
      paymentTab: 0,
      program: '',
      paymentHeaders: [], // Add headers for your Payments table
      payments: [], // Add data for your Payments table
      paymentAmount: '', // Add form field for payment amount
      // student info dialog

      activeTab: 0, // Initially set to the first tab
      advanceSearchLabel: '',
      advanceSearch: '',
      searchDialog: false,
      selectedItem: null,
      items: [
        // Your dropdown items here
        { text: 'Student Number', value: '1' },
        { text: 'Firstname', value: '2' },
        { text: 'Middlename', value: '3' },
        { text: 'Lastname', value: '4' },
      ],
      selectedSemesterName: '',
      studentPayments: [],
      studentPaymentsHeaders: [],
      students: [],
      mat_number: '',
      semesters: [],
      semestersMissing: [],
      sponsorshipDetails: [],
      studentFullName: '',
      studentDetails: '',
      department: '',
      headers: [
        { text: 'FullName', value: 'fullname' },
        { text: 'Student Number', value: 'mat_number' },
        { text: 'Program', value: 'program.name' },
        { text: 'Admission Year', value: 'admission_year' },
        { text: 'Email', value: 'email' },
        // { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Action', value: 'action', sortable: false },
      ],

      sponsorshipDetailsHeader: [
        { text: 'Scholarship Name', value: 'scholarship_name' },
        { text: 'Scholarship Provider', value: 'scholarship_provider' },
        { text: 'Start Date', value: 'start_date' },
        { text: 'End Date', value: 'end_date' },
        { text: 'Scholarship', value: 'award' },
        // http://127.0.0.1:8000/storage/scholarships/J0P9NnR166YSt2jo7iQDCJbOGESYU50COhvVr7jP.pdf
      ],

      addPaymentFormData: {
        amount_paid: '',
        semester_id: '',
        student_id: '',
        balance: '',
      },
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
    // if (this.activeTab == 1) {
    //   console.log('SECOND TAB')
    // }
  },

  computed: {
    currentDate() {
      const today = new Date()
      const options = { year: 'numeric', month: 'long', day: 'numeric' }
      return today.toLocaleDateString(undefined, options)
    },
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },

  methods: {
    generateRandomReceiptNumber() {
      const randomPart = Math.floor(Math.random() * 1000000)
        .toString()
        .padStart(6, '0')
      return `REC-${randomPart}`
    },
    scholarshipAward(award) {
      if (award == null) {
        swal.fire({
          title: 'Error!',
          text: 'No scholarship File provided by this student',
          icon: 'error',
          confirmButtonText: 'OK',
        })
        return
      }
      const fileUrl = apiBaseURL + `storage/${award}` // Adjust the path based on your storage configuration

      // Open a new window or tab with the file
      window.open(fileUrl, '_blank')
    },
    sponsorship(item) {
      this.showSponsorshipDetails = true
      axios
        .get('/api/view-scholarship-details/' + item.id)
        .then(response => {
          this.sponsorshipDetails = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.sponsorshipDetails = []
          this.pageCount = 0
        })
    },
    saveScholarship(student) {
      const formData = new FormData()
      formData.append('scholarshipProvider', this.scholarshipFormData.scholarshipProvider)
      formData.append('scholarshipName', this.scholarshipFormData.scholarshipName)
      formData.append('startDate', this.scholarshipFormData.startDate)
      formData.append('endDate', this.scholarshipFormData.endDate)
      formData.append('student_id', this.student_id)
      formData.append('scholarship_amount', this.scholarshipFormData.scholarship_amount)

      // Append file data if available
      if (this.scholarshipFormData.uploadedFile) {
        formData.append('uploadedFile', this.scholarshipFormData.uploadedFile)
      }
      axios
        .post('/api/add-student-sponsorship', formData)
        .then(result => {
          // this.addPaymentDialog = false
          // show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'scholarship added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.addSponsorDialog = false
              this.getResults()
            })
        })
        .catch(error => {
          // show error alert
          swal.fire({
            title: 'Error!',
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },
    // view-missing-semester
    viewStudentInfo(student) {
      axios
        .post('/api/view-missing-semester', { student_id: student.id })
        .then(response => {
          this.semestersMissing = response.data.result
          this.session_start = response.data.result.session.session_start
          this.session_end = response.data.result.session.session_end

          console.log('missing students ', response.data.result)
          // this.addPaymentDialog = false
        })
        .catch(error => {
          // show error alert
        }),
        axios
          .post('/api/get-prog-dept', { student_id: student.id })
          .then(responses => {
            this.department = responses.data.result.department_name
            this.program = responses.data.result.program_name

            /* this.session_start = responses.data.result.session.session_start
            this.session_end = responses.data.result.session.session_end */

            console.log('department', responses.data.result.department_name)
            console.log('program', responses.data.result.program_name)
            // this.addPaymentDialog = false
          })
          .catch(error => {
            // show error alert
          }),
        (this.addPaymentFormData.student_id = student.id)
      this.paymentDialog = true
      this.studentFullName = student.firstname + ' ' + student.lastname
      //  = student.program.name
      this.mat_number = student.mat_number
      if (student.remaining_balance !== undefined && student.remaining_balance !== null) {
        console.log('Remaining Balance:', student.remaining_balance);
        this.program_remaining_balance = student.remaining_balance;
      } else {
        // If no remaining balance, use program fee
        this.program_remaining_balance = student.program.fee;
        console.log('No remaining balance, using program fee:', this.program_remaining_balance);
      }
      // this.semester_balance = student.payments[0].semester_fee_balance
      this.semester_balance =
        student.payments.length > 0 && this.semestersMissing.is_current_semester != 0
          ? student.payments[0].semester_fee_balance
          : student.program.per_semester_fee
      console.log('student', student)
        ; (this.studentPaymentsHeaders = [
          { text: 'Semester Name', value: 'semester.semester_name' },
          { text: 'Amount Paid', value: 'amount_paid' },
          { text: 'Payment Type', value: 'payment_type' },
        ]),
          (this.studentPayments = student.payments)
    }
    ,
    showAddSponsorDialog(item) {
      this.student_id = item.id
      this.addSponsorDialog = true
    },

    closeScholarshipDialog() {
      this.addSponsorDialog = false
    },
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
          // this.addPaymentFormData.semester_id = this.semesters[this.semesters.length - 1].id
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.semesters = []
          this.pageCount = 0
        })
    },

    deleteLecturer(item) {
      // perform delete action on item
      console.log(`Deleting lecturer ${item.id}`)
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.delete(`/api/delete-lecturer/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'lecturer deleted successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.getResults()
                })
            })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },

    async submitAddPaymentForm() {
      // const result = await this.v$.value.$validate()
      if (true) {
        this.addPaymentFormData.balance = this.program_remaining_balance
        axios
          .post('/api/add-student-fee', this.addPaymentFormData)
          .then(result => {
            this.paymentDialog = false
              // show success alert
              ; (this.addPaymentFormData.semester_id = ''),
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Payment added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.generatePDF()
                    this.getResults()
                    this.addPaymentFormData.amount_paid = ''
                  })
          })
          .catch(error => {
            // show error alert
            swal.fire({
              title: 'Error!',
              text: error.response.data.message,
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to allocate courses.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },
    generatePDF() {
      const options = {
        filename: 'receipt.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
      }

      const tableContent = this.generateReceiptContent()

      const htmlContent = `
        <html>
          <head>
            <style scoped>
            .title {
              font-size: 24px;
              font-weight: bold;
              text-align: center;
              margin-bottom: 20px;
            }

            .subtitle {
              font-size: 18px;
              text-align: center;
              margin-bottom: 10px;
            }

            .receipt-card {
              max-width: 500px;
              margin: 0 auto;
              padding: 20px;
            }

            .header {
              text-align: center;
              margin-bottom: 20px;
            }

            .receipt-info {
              display: flex;
              flex-direction: column;
            }

            .info-row {
              display: flex;
              justify-content: space-between;
              margin-bottom: 10px;
            }

            .info-label {
              font-weight: bold;
            }

            .info-value {
              text-align: right;
            }

            .signature-row {
              margin-top: 10px;
              display: flex;
              align-items: center;
              justify-content: flex-start;
            }

            .signature-line {
              margin-top: 10px;
              border-bottom: 1px solid black;
              width: 200px;
            }

            </style>
          </head>
          <body>
            ${tableContent}
          </body>
        </html>
      `

      html2pdf().set(options).from(htmlContent).save()
    },

    generateReceiptContent() {
      const receiptNumber = this.generateRandomReceiptNumber()

      let content = `
    <div class="receipt-card">

      <div style="display: flex; justify-content: center;">
  <img src="images/logos/mdi_logo.png" style="width: 170px; margin-bottom: 20px;" />
</div>

      <div class="title">TUITION FEES RECEIPT</div>
      <div class="header">
        <p class="subtitle">Management Development Institute</p>
        <p>No 1. Kanifing GRTS Street</p>
      </div>

      <div class="receipt-info">

        <div class="info-row">
            <span class="info-label">Receipt Number:</span>
            <span class="info-value">${receiptNumber}</span>
            <span class="info-label" style="margin-left: 20px;">Date:</span>
            <span class="info-value">${this.currentDate}</span>
          </div>

        <div class="info-row">
          <span class="info-label">Received From:</span>
          <span class="info-value">${this.studentFullName}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Student Number:</span>
          <span class="info-value">${this.mat_number}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Department:</span>
          <span class="info-value">${this.department}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Program:</span>
          <span class="info-value">${this.program}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Year of Enrollment:</span>
          <span class="info-value">${this.userInfo.created_at.split('-')[0]}</span>
        </div>

        <div class="info-row">
          <span class="info-label">The sum of:</span>
          <span class="info-value">${'D' + this.addPaymentFormData.amount_paid}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Being payment of:</span>
          <span class="info-value">${this.selectedSemesterName}</span>
        </div>

        <div class="info-row">
          <span class="info-label">Received by:</span>
          <span class="info-value">${this.userInfo.firstname + ' ' + this.userInfo.lastname}</span>
        </div>

        <div class="signature-row">
          <span class="info-label">Signature:</span>
          <span class="signature-line"></span>
        </div>
      </div>
    </div>
  `

      return content
    },
  },

  watch: {
    activeTab: function (newVal) {
      if (newVal == 1) {
        axios
          .get('/api/view-students?page=' + this.page + '&sponsored=1')
          .then(response => {
            this.students = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            this.students = []
            this.pageCount = 0
          })
      } else {
        axios
          .get('/api/view-students?page=' + this.page + '&notsponsored=1')
          .then(response => {
            this.students = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            this.students = []
            this.pageCount = 0
          })
      }
    },
    'addPaymentFormData.semester_id': function (newVal) {
      const selectedSemester = this.semesters.find(semester => semester.id === newVal)
      if (selectedSemester) {
        this.selectedSemesterName =
          selectedSemester.semester_name +
          '(' +
          selectedSemester.session.session_start.split(' ')[0] +
          '-' +
          selectedSemester.session.session_end +
          ')'
      }
    },
    getUserProfile: function () {
      this.userInfo = this.getUserProfile
    },
  },

  // watch: {
  //   getUserProfile: function () {
  //     this.userInfo = this.getUserProfile
  //   },
  // },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  // computed: {
  //   getUserProfile() {
  //     //final output from here
  //     return this.$store.getters.getUserProfile
  //   },
  // },
}
</script>
