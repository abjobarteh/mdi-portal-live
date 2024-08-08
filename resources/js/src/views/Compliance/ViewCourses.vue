<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Course</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field
            v-model="search"
            label="Filter this page"
            append-icon="mdi-magnify"
            clearable
            hide-details
          ></v-text-field>
          <v-btn icon @click="showSearchDialog">
            <fas icon="search"></fas>
          </v-btn>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
         
        </v-toolbar>

        <v-dialog v-model="searchDialog" max-width="400">
          <v-card>
            <v-card-title>Advanced Search</v-card-title>
            <v-card-text>
              <!-- Add your dropdown or any additional search options here -->
              <v-select v-model="selectedItem" :items="items" label="Search by Item"></v-select>
              <v-text-field
                v-model="advanceSearch"
                :label="advanceSearchLabel"
                append-icon="mdi-magnify"
                clearable
                hide-details
              ></v-text-field>
            </v-card-text>
            <v-card-actions>
              <v-btn
                color="primary"
                :disabled="selectedItem == null || advanceSearch === ''"
                @click="performAdvancedSearch"
                >Search</v-btn
              >
              <v-btn @click="closeSearchDialog">Close</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>

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
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.course_code.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-text-field outlined v-model="addCourseFormData.course_name" label="Coure Name"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.course_name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-select
              outlined
              v-model="addCourseFormData.program_id"
              :items="programs.map(program => ({ id: program.id, name: program.name }))"
              item-value="id"
              item-text="name"
              label="Course Category"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.program_id.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
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
        <v-card-title> Edit Course </v-card-title>
        <v-card-text>
          <v-form ref="addCourseForm">
            <v-text-field outlined v-model="editCourseFormData.course_code" label="Course Code"></v-text-field>
            <v-text-field outlined v-model="editCourseFormData.course_name" label="Coure Name"></v-text-field>
            <v-select
              outlined
              v-model="editCourseFormData.program_id"
              :items="programs.map(program => ({ id: program.id, name: program.name }))"
              item-value="id"
              item-text="name"
              label="Course Category"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitupdateProgramDurationForm">Update</v-btn>
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
      advanceSearchLabel: '',
      advanceSearch: '',
      searchDialog: false,
      selectedItem: null,
      items: [
        // Your dropdown items here
        { text: 'Course Name', value: '1' },
        { text: 'Course Code', value: '2' },
      ],

      programs: [],
      courses: [],
      headers: [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
        { text: 'Program Name', value: 'program.name' },
        { text: 'Department Name', value: 'program.department.name' },
      ],
      // items: [],
      dialog: false,
      editedIndex: -1,
      editCourseFormData: {
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
        id: null,
        course_code: '',
        course_name: '',
        program_id: '',
      },

      rules: {
        course_code: { required },
        course_name: { required },
        program_id: { required },
      },

      v$: useVuelidate(this.rules, this.addCourseFormData),
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  watch: {
    selectedItem(newValue) {
      // Find the corresponding text based on the selected value
      const selectedItemObject = this.items.find(item => item.value === newValue)

      // Update the label with the text associated with the selected value
      this.advanceSearchLabel = selectedItemObject ? 'search by ' + selectedItemObject.text.toLowerCase() : ''
    },
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
        .get('/api/view-courses', {
          params: {
            page: this.page,
            advanceSearch: this.advanceSearch,
            selectedItem: this.selectedItem, // Add the selectedItem here
          },
        })
        .then(response => {
          this.courses = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.courses = []
          this.pageCount = 0
        })
      this.closeSearchDialog()
    },
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addCourseFormData)
    },
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
        })
        .catch(err => {
          this.programs = []
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
      this.editCourseFormData = Object.assign({}, item)
      this.editCourseDialog = true
    },

    // submitupdateProgramDurationForm() {
    //   // make a PUT request to update the gradingSystem data
    //   axios.put(`/api/course/${this.editCourseFormData.id}`, this.editCourseFormData).then(response => {
    //     // show a success notification
    //     this.$toast.success('courses information has been updated.')
    //     // refresh the data table
    //     this.getResults()
    //   })
    //   // hide the dialog
    //   this.editCourseDialog = false
    //   // clear the edited item
    //   this.editCourseFormData = {
    //     id: null,
    //     course_code: '',
    //     course_name: '',
    //     program_id: '',
    //   }
    //   this.editedIndex = -1
    // },
    submitupdateProgramDurationForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/course/${this.editCourseFormData.id}`, this.editCourseFormData)
        .then(response => {
          // show success alert
          this.editCourseDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'course  updated successfully.',
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
            text: 'Failed to update course.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // sell-code/{id}
      // hide the dialog
      this.editCourseDialog = false
      // clear the edited item
      this.editCourseFormData = {
        id: null,
        course_code: '',
        course_name: '',
        program_id: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editCourseDialog = false
      // clear the edited item
      this.editCourseFormData = {
        id: null,
        course_code: '',
        course_name: '',
        program_id: '',
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
          text: 'Failed to add course.',
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











