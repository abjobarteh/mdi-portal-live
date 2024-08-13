<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Lecturers</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAllocateSemesterCoursesDialog">Allocate
            Courses</v-btn>

          <v-btn color="red" small class="white--text" @click="announce">Announcements</v-btn>
        </v-toolbar>

        <v-card-text>
          <v-data-table :headers="headers" :items="lecturers" :items-per-page="13" :search="search" class="elevation-1"
            hide-default-footer>

            <template v-slot:[`item.fullname`]="{ item }">
              {{ item.firstname + ' ' + item.lastname }}
            </template>
            <template v-slot:[`item.action`]="{ item }">
              <v-btn @click="openLecturerSemesterCoursesPopup(item)">View Courses</v-btn>
              <v-btn small color="primary" @click="showlecturercourse(item)">Edit Lecturer Courses</v-btn>
              <v-btn small color="red" @click="showAllocateDepartment(item)">Assign Non Program Courses</v-btn>
              <!-- <v-btn small color="primary" @click="editlecturer(item)">Edit</v-btn> -->
              <!-- <v-btn small color="error" @click="deleteLecturer(item)">Delete</v-btn> -->
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- <v-dialog v-model="editLecturerDialog" max-width="500px">
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
    </v-dialog> -->

    <!-- show lecturers courses dialog -->
    <v-dialog v-model="showLecturerSemesterCoursesPopup" persistent max-width="600px">
      <!-- <template v-slot:activator="{ on }"></template> -->
      <v-card>
        <v-card-title>
          <p>
            <span style="font-weight: bold">{{ lecturerFullName }}'s</span> Courses
          </p>
          <v-spacer></v-spacer>
          <fas style="
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
            " icon="times" @click="
              canCloseLecturerSemesterCoursesPopup = true
            closeLecturerSemesterCoursesPopup()
              "></fas>
        </v-card-title>
        <v-card-text>
          <v-data-table :headers="lecturerSemesterCoursesHeaders" :items="lecturerSemesterCoursesArr">
            <!-- <template v-slot:item.admission_code="{ item }">
              {{ item.admission_code }}
            </template> -->
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="deallocateCourse(item)">Deallocate</v-btn>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-dialog>
    <v-dialog v-model="editlectdialog" max-width="500px">
      <v-card>
        <v-card-title> Edit lecturer </v-card-title>
        <v-card-text>
          <v-form ref="form">

            <v-select multiple outlined v-model="coursedetsformdata.course_ids" :items="formattedCourses"
              item-value="id" item-text="name" label="Courses"
              :rules="[v => !!v.length || 'At least one course is required']"></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="green" @click="addcourse">Add Course(s)</v-btn>
          <v-btn color="red" @click="removecourse">Remove Course(s)</v-btn>
          <v-btn color="secondary" @click="closepopup">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="editlectdept" max-width="650px">
      <v-card>
        <v-card-title> Edit lecturer Program Courses </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-select outlined v-model="progs.program_id"
              :items="programs.map(program => ({ id: program.id, name: program.name }))" item-value="id"
              item-text="name" label="Program" @input="handleProgramChange"
              :rules="[v => !!v || 'A Program is required']"></v-select>

            <v-select multiple outlined v-model="deptdetsformdata.id" :items="formatteddepts" item-value="id"
              item-text="name" label="Course"  :rules="[v => !!v.length || 'At least one course is required']">
            </v-select>

          </v-form>

        </v-card-text>
        <v-card-actions>
          <v-btn color="green" @click="addnonprogcourse">Add Program Course(s)</v-btn>
          <v-btn color="secondary" @click="closebtn">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <!-- // allocate courses //  -->
    <v-dialog v-model="allocateCoursesDialog" max-width="500px">
      <v-card>
        <v-card-title>Allocate Courses</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-select outlined v-model="allocateCourseFormData.lecturer_id" :items="lecturers.map(lecturer => ({ id: lecturer.id, name: lecturer.firstname + ' ' + lecturer.lastname }))
              " item-value="id" item-text="name" label="Lecturers"></v-select>
            <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.lecturer_id.$errors" :key="error.$uid">{{ error.$message }}</span>
            <v-select multiple outlined v-model="allocateCourseFormData.semester_courses_ids" :items="semesterAvailableCourses.map(semesterCourse => ({
              id: semesterCourse.course_id,
              name: semesterCourse.course.course_name,
            }))
              " item-value="id" item-text="name" label="Courses"></v-select>
            <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.semester_courses_ids.$errors" :key="error.$uid">{{ error.$message }}</span>
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
import Swal from 'sweetalert2';

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
      editlectdialog: false,
      editlectdept: false,
      lecturerSemesterCoursesHeaders: [],
      lecturers: [],
      lecturer_id: '',
      lecturerFullName: '',
      deptcourses: [],
      depts: [],
      progs: [],
      progcourses: [],
      programs: [],
      courses: [],
      formValid: false,
      semesterAvailableCourses: [],
      headers: [
        { text: 'Fullname', value: 'fullname' },
        { text: 'username', value: 'username' },
        { text: 'Email', value: 'email' },
        { text: 'Address', value: 'address' },
        { text: 'Phonenumber', value: 'phonenumber' },
        { text: 'Lecturer Type', value: 'lecturer_type' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      // editLecturerDialog: false,
      // editedIndex: -1,
      // editLecturerFormData: {
      //   id: null,
      //   mark_from: '',
      //   mark_to: '',
      //   grade: '',
      //   interpretation: '',
      // },
      allocateCourseFormData: {
        lecturer_id: '',
        semester_courses_ids: [],
      },
      coursedetsformdata: {
        lecturerId: null,
        course_ids: []
      },
      deptdetsformdata: {
        lecturerId: null,
        course_ids: [],
        id: []
      },
      page: 1,
      pageCount: 0,
      search: '',

      rules: {
        lecturer_id: { required },
        semester_courses_ids: { required },
        id : {required}
      },

      v$: null,
    }
  },
  handleProgramChange(selectedProgramId) {
      console.log('Selected Program ID:', selectedProgramId);
      axios
        .get(`/api/get-course/${selectedProgramId}`)
        .then(response => {

          this.progcourses = response.data.result.data;
          this.progcourses = response.data.result;
          console.log("DATA: ", this.progcourses);

        })
        .catch(err => {
          console.error('Error fetching courses:', err);
          this.progcourses = [];
        });
    },
    showAllocateDepartment(item) {
      this.editlectdept = true
      this.lecturerId = item.id
      axios
        .get('/api/view-programs')
        .then(response => {
          this.programs = response.data.result.data
        })
        .catch(err => {
          this.programs = []
        })
    },
    closepopup() {
      this.editlectdialog = false;

      this.coursedetsformdata = [];
    },
    closebtn() {
      this.editlectdept = false;
       this.deptdetsformdata = [];
    },
    addnonprogcourse(){
      console.log('Selected Courses:', this.deptdetsformdata.id);
      console.log('Lecturer ID:', this.lecturerId);

      axios.post('/api/add-course-lect', {
        lecturer_id: this.lecturerId, // Ensure you have this property in your data or computed
        courseids: this.deptdetsformdata.id // The selected course IDs
      })
        .then(response => {
          // Show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'Course added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getResults();
              this.deptdetsformdata = [];
              this.editlectdialog = false;
              // Clear the selection
            });
        })
        .catch(error => {
          // Handle error
          console.error(error);
        })
    },
  computed: {
    // Flatten courses array and format it for v-select
    formattedCourses() {
      return this.deptcourses.flatMap(department =>
        department.courses.map(course => ({
          id: course.id,
          name: course.course_name
        }))
      );
    },
    formatteddepts() {
      return this.progcourses.flatMap(departments => ({
        id: departments.id,
        name: departments.course_name
      }));
    }
  },
  watch: {
    'allocateCourseFormData.lecturer_id'(newLecturerId) {
      if (newLecturerId) {
        axios
          .get(`/api/view-semester-available-courses/${newLecturerId}`)
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
  showAllocateDepartment(item) {
      this.editlectdept = true
      this.lecturerId = item.id
      axios
        .get('/api/view-programs')
        .then(response => {
          this.programs = response.data.result.data
        })
        .catch(err => {
          this.programs = []
        })
    },
    closepopup() {
      this.editlectdialog = false;

      this.coursedetsformdata = [];
    },
    closebtn() {
      this.editlectdept = false;
       this.deptdetsformdata = [];
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
    handleProgramChange(selectedProgramId) {
      console.log('Selected Program ID:', selectedProgramId);
      axios
        .get(`/api/get-course/${selectedProgramId}`)
        .then(response => {

          this.progcourses = response.data.result.data;
          this.progcourses = response.data.result;
          console.log("DATA: ", this.progcourses);

        })
        .catch(err => {
          console.error('Error fetching courses:', err);
          this.progcourses = [];
        });
    },
    deallocateCourse(item) {
      axios
        .post('/api/deallocate-lecturer-courses', { courseId: item.id })
        .then(result => {
          this.showLecturerSemesterCoursesPopup = false
          // show success alert
          // ;(this.allocateCourseFormData.semester_courses_ids = ''),
          swal
            .fire({
              title: 'Success!',
              text: 'course deallocated successfully.',
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
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.lecturers)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'lecturers')
      XLSX.writeFile(workbook, 'lecturers.xlsx')
    },

    // editlecturer(item) {
    //   this.editedIndex = this.items.indexOf(item)
    //   this.editLecturerFormData = Object.assign({}, item)
    //   this.editLecturerDialog = true
    // },

    // submitEditlecturerForm() {
    //   // make a PUT request to update the lecturerSystem data
    //   axios
    //     .put(`/api/lecturer/${this.editLecturerFormData.id}`, this.editLecturerFormData)
    //     .then(response => {
    //       // show success alert
    //       this.editLecturerDialog = false
    //       swal
    //         .fire({
    //           title: 'Success!',
    //           text: 'Lecturer updated successfully.',
    //           icon: 'success',
    //           confirmButtonText: 'OK',
    //         })
    //         .then(() => {
    //           this.getResults()
    //         })
    //     })
    //     .catch(error => {
    //       // show error alert
    //       swal.fire({
    //         title: 'Error!',
    //         text: 'Failed to update grade.',
    //         icon: 'error',
    //         confirmButtonText: 'OK',
    //       })
    //     })
    //   // hide the dialog
    //   this.editLecturerDialog = false
    //   // clear the edited item
    //   this.editLecturerFormData = {
    //     id: null,
    //     mark_from: '',
    //     mark_to: '',
    //     grade: '',
    //     interpretation: '',
    //   }
    //   this.editedIndex = -1
    // },
    // cancelEdit() {
    //   // hide the editLecturerDialog
    //   this.editLecturerDialog = false
    //   // clear the edited item
    //   this.editLecturerFormData = {
    //     id: null,
    //     mark_from: '',
    //     mark_to: '',
    //     grade: '',
    //     interpretation: '',
    //   }
    //   this.editedIndex = -1
    // },

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
    showlecturercourse(item) {
      this.editlectdialog = true
      this.lecturerId = item.id
      axios
        .get(`/api/view-departmental-courses/${item.id}`)
        .then(response => {
          console.log('Response:', response);
          this.deptcourses = response.data.result.data

          // this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.deptcourses = []
          //  this.pageCount = 0
        })
    },
    addnonprogcourse(){
      console.log('Selected Courses:', this.deptdetsformdata.id);
      console.log('Lecturer ID:', this.lecturerId);

      axios.post('/api/add-course-lect', {
        lecturer_id: this.lecturerId, // Ensure you have this property in your data or computed
        courseids: this.deptdetsformdata.id // The selected course IDs
      })
        .then(response => {
          // Show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'Course added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getResults();
              this.deptdetsformdata = [];
              this.editlectdialog = false;
              // Clear the selection
            });
        })
        .catch(error => {
          // Handle error
          console.error(error);
        })
    },
    showAllocateDepartment(item) {
      this.editlectdept = true
      this.lecturerId = item.id
      axios
        .get('/api/view-programs')
        .then(response => {
          this.programs = response.data.result.data
        })
        .catch(err => {
          this.programs = []
        })
    },
    closebtn() {
      this.editlectdept = false;
       this.deptdetsformdata = [];
    },
    closepopup() {
      this.editlectdialog = false;
      this.coursedetsformdata = [];
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
              .post('/api/announce-lecturer', {
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
    openLecturerSemesterCoursesPopup(lecturer) {
      this.lecturerFullName = lecturer.firstname + ' ' + lecturer.lastname
      console.log(lecturer)
        ; (this.lecturerSemesterCoursesHeaders = [
          { text: 'Course Code', value: 'course_code' },
          { text: 'Course Name', value: 'course_name' },
          { text: 'Action', value: 'action', sortable: false },
        ]),
          (this.lecturerSemesterCoursesArr = lecturer.semester_courses.map(course => course.course)),
          (this.showLecturerSemesterCoursesPopup = true)
      this.canCloseLecturerSemesterCoursesPopup = false
    },

    closeLecturerSemesterCoursesPopup() {
      if (this.canCloseLecturerSemesterCoursesPopup) {
        this.showLecturerSemesterCoursesPopup = false
      }
    },
    addcourse() {

      console.log('Lecturer ID:', this.lecturerId);
      console.log('Selected Courses:', this.coursedetsformdata.semester_courses_ids);

      axios.post('/api/add-course-lect', {
        lecturer_id: this.lecturerId, // Ensure you have this property in your data or computed
        courseids: this.coursedetsformdata.course_ids // The selected course IDs
      })
        .then(response => {
          // Show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'Course added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getResults();
              this.coursedetsformdata = [];
              this.editlectdialog = false;
              // Clear the selection
            });
        })
        .catch(error => {
          // Handle error
          console.error(error);
        })
    },
    removecourse() {

console.log('Lecturer ID:', this.lecturerId);
console.log('Selected Courses:', this.coursedetsformdata.semester_courses_ids);

axios.post('/api/remove-course-lect', {
  lecturer_id: this.lecturerId, // Ensure you have this property in your data or computed
  courseids: this.coursedetsformdata.course_ids // The selected course IDs
})
  .then(response => {
    // Show success alert
    swal
      .fire({
        title: 'Success!',
        text: 'Course removed successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
      })
      .then(() => {
        this.getResults();
        this.coursedetsformdata = [];
        this.editlectdialog = false;
        // Clear the selection
      });
  })
  .catch(error => {
    // Handle error
    console.error(error);
  })
},
    //////////////  Allocate courses ///////////////////
    showAllocateSemesterCoursesDialog() {
      this.allocateCoursesDialog = true
    },

    async submitallocateCourseForm() {
      const result = await this.v$.value.$validate()
    /*  if (result) { */
        axios
          .post('/api/allocate-semester-available-courses', this.allocateCourseFormData)
          .then(result => {
            this.allocateCoursesDialog = false
              // show success alert
              ; (this.allocateCourseFormData.semester_courses_ids = ''),
                swal
                  .fire({
                    title: 'Success!',
                    text: 'course allocated successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.getResults()
                    window.location.reload()
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
    /*  } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to allocate courses.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      } */
    },
  },
}
</script>
