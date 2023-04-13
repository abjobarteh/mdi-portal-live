<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Course</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddCourseDialog">Add Course</v-btn>
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
            <template v-slot:action="{ item }">
              <v-btn small color="primary" @click="editCourse(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteCourse(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add course duration dialog -->
    <v-dialog v-model="addCourseDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Course</v-card-title>
        <v-card-text>
          <v-form ref="addCourseForm">
            <v-text-field outlined v-model="addCourseFormData.course_code" label="Course Code"></v-text-field>
            <v-text-field outlined v-model="addCourseFormData.course_name" label="Coure Name"></v-text-field>
            <v-select
              outlined
              v-model="addCourseFormData.program_id"
              :items="programs.map(program => ({ id: program.id, name: program.name }))"
              item-value="id"
              item-text="name"
              label="Course Category"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddCourseForm">Add</v-btn>
          <v-btn color="secondary" @click="addCourseDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit course duration dialog -->
    <v-dialog v-model="editCourseDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Department </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="addCourseFormData.name" label="Department Name"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitupdateProgramDurationForm">Add</v-btn>
          <v-btn color="secondary" @click="cancelAdd">Cancel</v-btn>
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
import { required } from '@vuelidate/validators'

Vue.use(Vue2Filters)

export default {
  name: 'Course',
  props: {},
  components: {},
  data() {
    return {
      programs: [],
      courses: [],
      headers: [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
        { text: 'Program Name', value: 'program.name' },
        { text: 'Department Name', value: 'program.department.name' },

        { text: 'Action', value: 'action', sortable: false },
      ],
      items: [],
      dialog: false,
      editedIndex: -1,
      editedItem: {
        id: null,
        name: '',
      },
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new department /////////
      addCourseDialog: false,
      addCourseFormData: {
        course_code: '',
        course_name: '',
        program_id: '',
      },

      // edit department
      editCourseDialog: false,
      editCourseFormData: {
        course_code: '',
        course_name: '',
        program_id: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addCourseFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-courses?page=' + this.page)
        .then(response => {
          this.courses = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.courses = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-programs?page=' + this.page)
        .then(response => {
          this.programs = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.programs = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.courses)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'courses')
      XLSX.writeFile(workbook, 'courses.xlsx')
    },

    editCourse(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editCourseDialog = true
    },

    submitupdateProgramDurationForm() {
      // make a PUT request to update the gradingSystem data
      axios.put(`/api/course/${this.editedItem.id}`, this.editedItem).then(response => {
        // show a success notification
        this.$toast.success('courses information has been updated.')
        // refresh the data table
        this.getResults()
      })
      // hide the dialog
      this.editCourseDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editCourseDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },

    deleteCourse(item) {
      // perform delete action on item
      console.log(`Deleting course ${item.id}`)
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
            axios.delete(`/api/delete-course/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Course deleted successfully.',
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

    showeditCourseDialog() {
      this.editCourseDialog = true
    },

    ////////  Adding ///////
    async submitaddCourseForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-course', this.addCourseFormData)
          .then(result => {
            // show success alert
            this.addCourseDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Course added successfully.',
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
              text: 'Failed to add course duration.',
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to add employee.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },
    showAddCourseDialog() {
      this.addCourseDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editCourseDialog = false
    },
  },

  computed: {
    filteredcoures() {
      return this.courses.filter(course => {
        return (
          course.course_name.toLowerCase().includes(this.search.toLowerCase()) ||
          course.course_code.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











