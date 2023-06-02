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
              <v-btn @click="openStudentPaymentsPopup(item)">View Payments</v-btn>
              <v-btn small color="primary" @click="showAddStudentPaymentDialog(item)">Add Payment</v-btn>
              <v-btn small color="error" @click="deleteLecturer(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- show lecturers courses dialog -->
    <v-dialog v-model="showStudentPaymentPopup" persistent max-width="600px">
      <!-- <template v-slot:activator="{ on }"></template> -->
      <v-card>
        <v-card-title>
          <p>
            <span style="font-weight: bold">{{ studentFullName }}'s</span> Payments
          </p>
          <v-spacer></v-spacer>
          <fas
            style="
              font-size: 24px;
              cursor: pointer;
              display: inline-block;
              background-color: #3498db;
              color: #fff;
              border-radius: 50%;
              text-align: center;
              line-height: 1.5;
              width: 36px;
              height: 36px;
            "
            icon="times"
            @click="
              canCloseStudentPaymentsPopup = true
              closeStudentPaymentPopup()
            "
          ></fas>
        </v-card-title>
        <v-card-text>
          <v-data-table :headers="studentPaymentsHeaders" :items="studentPayments">
            <!-- <template v-slot:item.admission_code="{ item }">
              {{ item.admission_code }}
            </template> -->
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- // add payments //  -->
    <v-dialog v-model="addPaymentDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Payment</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-select
              outlined
              v-model="addPaymentFormData.semester_id"
              :items="
                semesters.map(semester => ({
                  id: semester.id,
                  name: semester.semester_name + '(' + semester.session.session_name + ')',
                }))
              "
              item-value="id"
              item-text="name"
              label="Semester Name"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.semester_id.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-text-field outlined v-model="addPaymentFormData.amount_paid" label="Fee"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitAddPaymentForm">Add</v-btn>
          <!-- <v-btn color="secondary" @click="addProgramDialog = false">Cancel</v-btn> -->
        </v-card-actions>
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

Vue.use(Vue2Filters)

export default {
  name: 'lecturerSystem',
  props: {},
  components: {},
  data() {
    return {
      showStudentPaymentPopup: false,
      studentPayments: [],
      canCloseStudentPaymentsPopup: false,
      addPaymentDialog: false,
      studentPaymentsHeaders: [],
      students: [],
      semesters: [],
      studentFullName: '',
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

      addPaymentFormData: {
        amount_paid: '',
        semester_id: '',
        student_id: '',
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

    openStudentPaymentsPopup(student) {
      this.studentFullName = student.firstname + ' ' + student.lastname
      console.log('student', student)
      ;(this.studentPaymentsHeaders = [
        { text: 'Semester Name', value: 'semester.semester_name' },
        { text: 'Amount Paid', value: 'amount_paid' },
      ]),
        (this.studentPayments = student.payments),
        (this.showStudentPaymentPopup = true)
      this.canCloseStudentPaymentsPopup = false
    },

    closeStudentPaymentPopup() {
      if (this.canCloseStudentPaymentsPopup) {
        this.showStudentPaymentPopup = false
      }
    },

    //////////////  Allocate courses ///////////////////
    showAddStudentPaymentDialog(item) {
      console.log(item)
      this.addPaymentFormData.student_id = item.id
      this.addPaymentDialog = true
    },

    async submitAddPaymentForm() {
      // const result = await this.v$.value.$validate()
      if (true) {
        axios
          .post('/api/add-student-fee', this.addPaymentFormData)
          .then(result => {
            this.addPaymentDialog = false
            // show success alert
            ;(this.addPaymentFormData.semester_id = ''),
              swal
                .fire({
                  title: 'Success!',
                  text: 'Payment added successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.getResults()
                })
          })
          .catch(error => {
            // show error alert
            swal.fire({
              title: 'Error!',
              text: 'Failed to allocate.',
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
  },
}
</script>











