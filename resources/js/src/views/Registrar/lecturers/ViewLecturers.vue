<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Lecturers</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAllocateSemesterCoursesDialog"
            >Allocate Courses</v-btn
          >
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="lecturers"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn @click="openLecturerSemesterCoursesPopup(item.semester_courses.map(course => course.course))"
                >View Courses</v-btn
              >
              <v-btn small color="primary" @click="editlecturer(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteLecturer(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <v-dialog v-model="editLecturerDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit lecturer </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="editLecturerFormData.mark_from" label="Mark From"></v-text-field>
            <v-text-field outlined v-model="editLecturerFormData.mark_to" label="Mark To"></v-text-field>
            <v-text-field outlined v-model="editLecturerFormData.grade" label="Grade"></v-text-field>
            <v-text-field outlined v-model="editLecturerFormData.interpretation" label="Interpretation"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitEditlecturerForm">Save</v-btn>
          <v-btn color="secondary" @click="cancelEdit">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- show lecturers courses dialog -->
    <v-dialog v-model="showLecturerSemesterCoursesPopup" persistent max-width="600px">
      <!-- <template v-slot:activator="{ on }"></template> -->
      <v-card>
        <v-card-title>
          Lecturers courses
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
              canCloseLecturerSemesterCoursesPopup = true
              closeLecturerSemesterCoursesPopup()
            "
          ></fas>
        </v-card-title>
        <v-card-text>
          <v-data-table :headers="lecturerSemesterCoursesHeaders" :items="lecturerSemesterCoursesArr">
            <!-- <template v-slot:item.admission_code="{ item }">
              {{ item.admission_code }}
            </template> -->
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>

    <!-- // allocate courses //  -->
    <v-dialog v-model="allocateCoursesDialog" max-width="500px">
      <v-card>
        <v-card-title>Allocate Courses</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-select
              outlined
              v-model="allocateCourseFormData.lecturer_id"
              :items="
                lecturers.map(lecturer => ({ id: lecturer.id, name: lecturer.firstname + ' ' + lecturer.lastname }))
              "
              item-value="id"
              item-text="name"
              label="Lecturers"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.lecturer_id.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-select
              multiple
              outlined
              v-model="allocateCourseFormData.semester_courses_ids"
              :items="
                semesterAvailableCourses.map(semesterCourse => ({
                  id: semesterCourse.course_id,
                  name: semesterCourse.course.course_name,
                }))
              "
              item-value="id"
              item-text="name"
              label="Courses"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.semester_courses_ids.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitallocateCourseForm">Add</v-btn>
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
      showLecturerSemesterCoursesPopup: false,
      lecturerSemesterCoursesArr: [],
      canCloseLecturerSemesterCoursesPopup: false,
      allocateCoursesDialog: false,
      lecturerSemesterCoursesHeaders: [],
      lecturers: [],
      semesterAvailableCourses: [],
      headers: [
        { text: 'Firstname', value: 'firstname' },
        { text: 'Lastname', value: 'lastname' },
        { text: 'username', value: 'username' },
        { text: 'Email', value: 'email' },
        { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      editLecturerDialog: false,
      editedIndex: -1,
      editLecturerFormData: {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      },
      allocateCourseFormData: {
        lecturer_id: '',
        semester_courses_ids: [],
      },
      page: 1,
      pageCount: 0,
      search: '',

      rules: {
        lecturer_id: { required },
        semester_courses_ids: { required },
      },

      v$: null,
    }
  },

  watch: {
    'allocateCourseFormData.lecturer_id'(newLecturerId) {
      if (newLecturerId) {
        axios
          .get(`/api/view-semester-available-courses/${newLecturerId}?page=` + this.page)
          .then(response => {
            this.semesterAvailableCourses = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            this.semesterAvailableCourses = []
            this.pageCount = 0
          })
      }
    },
  },

  created() {
    this.setupValidation()
    this.getResults()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.allocateCourseFormData)
    },
    getResults() {
      axios
        .get('/api/view-lecturers?page=' + this.page)
        .then(response => {
          this.lecturers = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.lecturers = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.lecturers)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'lecturers')
      XLSX.writeFile(workbook, 'lecturers.xlsx')
    },

    editlecturer(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editLecturerFormData = Object.assign({}, item)
      this.editLecturerDialog = true
    },

    submitEditlecturerForm() {
      // make a PUT request to update the lecturerSystem data
      axios
        .put(`/api/lecturer/${this.editLecturerFormData.id}`, this.editLecturerFormData)
        .then(response => {
          // show success alert
          this.editLecturerDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Lecturer updated successfully.',
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
            text: 'Failed to update grade.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editLecturerDialog = false
      // clear the edited item
      this.editLecturerFormData = {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the editLecturerDialog
      this.editLecturerDialog = false
      // clear the edited item
      this.editLecturerFormData = {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      }
      this.editedIndex = -1
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

    openLecturerSemesterCoursesPopup(lecturerSemesterCourses) {
      console.log(lecturerSemesterCourses)
      ;(this.lecturerSemesterCoursesHeaders = [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
      ]),
        (this.lecturerSemesterCoursesArr = lecturerSemesterCourses),
        (this.showLecturerSemesterCoursesPopup = true)
      this.canCloseLecturerSemesterCoursesPopup = false
    },

    closeLecturerSemesterCoursesPopup() {
      if (this.canCloseLecturerSemesterCoursesPopup) {
        this.showLecturerSemesterCoursesPopup = false
      }
    },

    //////////////  Allocate courses ///////////////////
    showAllocateSemesterCoursesDialog() {
      this.allocateCoursesDialog = true
    },

    async submitallocateCourseForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/allocate-semester-available-courses', this.allocateCourseFormData)
          .then(result => {
            this.allocateCoursesDialog = false
            // show success alert
            ;(this.allocateCourseFormData.semester_courses_ids = ''),
              swal
                .fire({
                  title: 'Success!',
                  text: 'course allocated successfully.',
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











