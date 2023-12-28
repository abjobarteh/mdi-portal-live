<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Semester</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddSemesterDialog">Add Semester</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredsemester"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.is_current_semester`]="{ item }">
              <fas
                v-if="item.is_current_semester == 1"
                icon="check"
                style="
                  background-color: lightgreen;
                  color: #fff;
                  border-radius: 50%;
                  text-align: center;
                  line-height: 1.5;
                  width: 25px;
                  height: 25px;
                "
              ></fas>
              <fas
                v-else
                icon="times"
                disabled
                style="
                  background-color: red;
                  color: #fff;
                  border-radius: 50%;
                  text-align: center;
                  line-height: 1.5;
                  width: 25px;
                  height: 25px;
                "
              ></fas>
            </template>
            <template v-slot:item.session="{ item }">
              {{ item.session.session_start.split(' ')[0] }} - {{ item.session.session_end }}
            </template>
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="editSemester(item)">Edit</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add session dialog -->
    <v-dialog v-model="addSemesterDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Semester</v-card-title>
        <v-card-text>
          <v-form ref="addSessionForm">
            <v-text-field outlined v-model="addSemesterFormData.semester_name" label="Semester Name"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.semester_name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-select
              outlined
              v-model="addSemesterFormData.session_id"
              :items="
                sessions.map(session => ({
                  id: session.id,
                  name: `${session.session_start.split(' ')[0]} - ${session.session_end}`,
                }))
              "
              item-value="id"
              item-text="name"
              label="Session"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.session_id.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn v-if="!loading" color="primary" @click="submitaddSemesterForm">Add</v-btn>
          <v-btn v-else disabled color="primary">wait...</v-btn>

          <v-btn color="secondary" @click="addSemesterDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="editSemesterDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Program </v-card-title>
        <v-card-text>
          <v-form ref="addCourseForm">
            <v-text-field outlined v-model="editSemesterFormData.semester_name" label="Course Code"></v-text-field>
            <v-select
              outlined
              v-model="editSemesterFormData.session_id"
              :items="
                sessions.map(session => ({
                  id: session.id,
                  name: `${session.session_start.split(' ')[0]} - ${session.session_end}`,
                }))
              "
              item-value="id"
              item-text="name"
              label="Session"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitupdateSemesterForm">Update</v-btn>
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
  name: 'Session',
  props: {},
  components: {},
  data() {
    return {
      loading: false,
      semesters: [],
      sessions: [],
      headers: [
        { text: 'Semester', value: 'semester_name' },
        { text: 'Is current Semester', value: 'is_current_semester' },
        { text: 'Session', value: 'session' },
        { text: 'Next Semester', value: 'next_semester' },

        { text: 'Action', value: 'action', sortable: false },
      ],
      dialog: false,
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new department /////////
      addSemesterDialog: false,
      addSemesterFormData: {
        semester_name: '',
        session_id: '',
      },

      // edit department
      editSemesterDialog: false,
      editSemesterFormData: {
        id: null,
        semester_name: '',
        session_id: '',
      },

      rules: {
        semester_name: { required },
        session_id: { required },
      },

      v$: useVuelidate(this.rules, this.addSemesterFormData),
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addSemesterFormData)
    },
    getResults() {
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

      axios
        .get('/api/view-sessions?page=' + this.page)
        .then(response => {
          this.sessions = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.sessions = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.semesters)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'semesters')
      XLSX.writeFile(workbook, 'semesters.xlsx')
    },

    editSemester(item) {
      this.editedIndex = this.semesters.indexOf(item)
      this.editSemesterFormData = Object.assign({}, item)
      this.editSemesterFormData.program_name = item.name

      console.log(this.editSemesterFormData)
      this.editSemesterDialog = true
    },

    submitupdateSemesterForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/semester/${this.editSemesterFormData.id}`, this.editSemesterFormData)
        .then(response => {
          // show success alert
          this.editSemesterDurationDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Semester  updated successfully.',
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
            text: 'Failed to update session.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editSemesterDialog = false
      // clear the edited item
      this.editSemesterFormData = {
        id: null,
        program_name: '',
        program_abbreviation: '',
        department_id: '',
        program_duration_id: '',
        fee: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editSemesterDialog = false
      // clear the edited item
      this.editSemesterFormData = {
        id: null,
        program_name: '',
        program_abbreviation: '',
        department_id: '',
        program_duration_id: '',
        fee: '',
      }
      this.editedIndex = -1
    },

    showeditProgramDialog() {
      this.editSemesterDialog = true
    },

    ////////  Adding ///////
    async submitaddSemesterForm() {
      this.loading = true
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-semester', this.addSemesterFormData)
          .then(result => {
            this.loading = false
            // show success alert
            this.addSemesterDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Semester added successfully.',
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
              text: 'Failed to add semester.',
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to semester.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },
    showAddSemesterDialog() {
      this.addSemesterDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editSemesterDialog = false
    },
  },

  computed: {
    filteredsemester() {
      return this.semesters.filter(session => {
        return (
          session.semester_name.toLowerCase().includes(this.search.toLowerCase()) ||
          session.is_current_semester.toLowerCase().includes(this.search.toLowerCase()) ||
          session.next_semester.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>
\<style scoped>
.loading-spinner {
  width: 20px;
  height: 20px;
  border: 2px solid #3f51b5; /* Vuetify primary color */
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  display: inline-block;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>










