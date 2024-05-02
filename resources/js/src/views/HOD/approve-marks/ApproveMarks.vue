
<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Approve Marks</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="courses"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn @click="openStudentMarksPopup(item)">View Marks</v-btn>
              <v-btn v-if="item.approved == 0" small color="error" @click="approveMark(item)">Approve</v-btn>
              <v-btn v-if="item.registrar_approved == 0" disabled small color="yellow"
                >Registrar Approval Requested</v-btn
              >
              <v-btn v-else small color="success">Approved</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add AdmissionCode dialog -->
    <v-dialog v-model="showStudentMarksPopup" persistent max-width="600px">
      <template v-slot:activator="{ on }"></template>
      <v-card>
        <v-card-title>
          <p>
            Grades for the selected course <span style="font-weight: bold">{{ location }}</span>
          </p>
          <v-spacer></v-spacer>
          <fas
            style="
              margin-left: 30px;
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
              canCloseStudentMarksPopup = true
              closeStudentMarksPopup()
            "
          ></fas>
        </v-card-title>
        <v-card-text>
          <!-- <v-data-table :headers="studentMarksHeaders" :items="items"></v-data-table> -->
          <v-data-table :headers="studentMarksHeaders" :items="items">
            <template v-slot:item.action="{ item }">
              {{ item.student.firstname + ' ' + item.student.lastname }}
            </template>
            <template v-slot:item.update_mark="{ item }">
              <v-btn :disabled="item.registrar_approved == 1" @click="openUpdateMarkDialog(item)" class="success small"
                >Update</v-btn
              >
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- dialog for update of marks -->
    <v-dialog v-model="updateMarkDialog" max-width="500px">
      <v-card>
        <v-card-title class="headline">Update Marks</v-card-title>
        <v-card-text>
          <v-text-field outlined v-model="updateMark.testMark" label="Test Mark"></v-text-field>
          <v-text-field outlined v-model="updateMark.examMark" label="Exam Mark"></v-text-field>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="primary" @click="saveMarks">Save</v-btn>
          <v-btn color="error" @click="closeUpdateMarkDialog">Cancel</v-btn>
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

Vue.use(Vue2Filters)

export default {
  name: 'AdmissionCodesLocations',
  props: {},
  components: {},
  data() {
    return {
      updateMarkDialog: false,
      updateMark: {
        testMark: '',
        examMark: '',
        course_id: '',
        semester_id: '',
        student_id: '',
      },
      location: '',
      admissionCodeLocationItem: null,
      showStudentMarksPopup: false,
      studentMarksHeaders: [],
      items: [],
      canCloseStudentMarksPopup: false,

      courses: [],
      headers: [
        { text: 'Course', value: 'course.course_name' },
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
    openUpdateMarkDialog(mark) {
      console.log('mark ', mark)
      this.updateMarkDialog = true
      this.updateMark.testMark = mark.test_mark
      this.updateMark.examMark = mark.exam_mark
      this.updateMark.course_id = mark.course_id
      this.updateMark.semester_id = mark.semester_id
      this.updateMark.student_id = mark.student_id
    },
    closeUpdateMarkDialog() {
      this.updateMarkDialog = false
    },
    saveMarks() {
      // api/update-student-grades
      swal
        .fire({
          title: "Are you sure, you want to update the student's mark?",
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.post(`/api/update-student-grades`, this.updateMark).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'mark updated successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.getResults()
                  this.updateMarkDialog = false
                  this.showStudentMarksPopup = false
                  // this.$store.dispatch('userProfile')
                  // this.$router.push('/student')
                })
            })
          }
        })
    },
    openStudentMarksPopup(studentMarks) {
      console.log(studentMarks)
      //   this.location = admissionCodes.location_name
      ;(this.studentMarksHeaders = [
        { text: 'Student Name', value: 'action' },
        { text: 'Test Mark', value: 'test_mark' },
        { text: 'Exam Mark', value: 'exam_mark' },
        { text: 'Update', value: 'update_mark' },
      ]),
        (this.items = studentMarks.marks),
        (this.showStudentMarksPopup = true)
      this.canCloseStudentMarksPopup = false
    },
    closeStudentMarksPopup() {
      if (this.canCloseStudentMarksPopup) {
        this.showStudentMarksPopup = false
      }
    },

    getResults() {
      axios
        .get('/api/view-hod-courses-to-approve?page=' + this.page)
        .then(response => {
          this.courses = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.courses = []
          this.pageCount = 0
        })
    },

    showStudentMarks(id, admissionCodes) {
      this.$router.push({
        name: 'view-admission-codes',
        params: {
          id,
          admissionCodes,
        },
      })
    },

    approveMark(item) {
      // perform delete action on item
      console.log(`Deleting courses`, item)
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, approve it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.post('/api/approve-courses-hod', { course_id: item.course_id }).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Sent to be approve by registrar',
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

    cancelAdd() {
      // Clear the new courses name and hide the add dialog
      this.newDepartmentName = ''
      this.addDialog = false
    },
  },
}
</script>












