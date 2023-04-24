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
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="editSession(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteSession(item)">Delete</v-btn>
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
            <v-text-field outlined v-model="addSemesterFormData.semester_name" label="Session"></v-text-field>
            <v-select
              outlined
              v-model="addSemesterFormData.is_current_semester"
              label="Is current semester"
              :items="[
                { text: 'Yes', value: 1 },
                { text: 'No', value: 0 },
              ]"
            ></v-select>
            <v-select
              outlined
              v-model="addSemesterFormData.session_id"
              :items="sessions.map(session => ({ id: session.id, name: session.session_name }))"
              item-value="id"
              item-text="name"
              label="Session"
            ></v-select>
            <v-text-field
              outlined
              v-model="addSemesterFormData.next_semester"
              label="Next Semester"
              type="date"
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddSemesterForm">Add</v-btn>
          <v-btn color="secondary" @click="addSemesterDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit sessioni dialog -->
    <v-dialog v-model="editSessionDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Program </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="addSemesterFormData.session_name" label="Session"></v-text-field>
            <v-text-field
              outlined
              v-model="addSemesterFormData.is_current_session"
              label="Is current session"
            ></v-text-field>
            <v-text-field outlined v-model="addSemesterFormData.next_session" label="Next Session"></v-text-field>
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
      semesters: [],
      sessions: [],
      headers: [
        { text: 'Semester', value: 'semester_name' },
        { text: 'Is current Semester', value: 'is_current_semester' },
        { text: 'Session', value: 'session.session_name' },
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
        is_current_semester: '',
        next_semester: '',
        session_id: '',
      },

      // edit department
      editSessionDialog: false,
      editSemesterFormData: {
        id: null,
        session_name: '',
        is_current_session: '',
        next_session: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addSemesterFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
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

    editSession(item) {
      this.editedIndex = this.semesters.indexOf(item)
      this.editSemesterFormData = Object.assign({}, item)
      this.editSemesterFormData.program_name = item.name

      console.log(this.editSemesterFormData)
      this.editSessionDialog = true
    },

    submitupdateSemesterForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/session/${this.editSemesterFormData.id}`, this.editSemesterFormData)
        .then(response => {
          // show success alert
          this.editSessionDurationDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Program  updated successfully.',
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
      this.editSessionDialog = false
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
      this.editSessionDialog = false
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

    deleteSession(item) {
      // perform delete action on item
      console.log(`Deleting department ${item.id}`)
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
            axios.delete(`/api/delete-session/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Program deleted successfully.',
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

    showeditProgramDialog() {
      this.editSessionDialog = true
    },

    ////////  Adding ///////
    async submitaddSemesterForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-semester', this.addSemesterFormData)
          .then(result => {
            // show success alert
            this.addSemesterDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Program Duration added successfully.',
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
              text: 'Failed to add session duration.',
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
    showAddSemesterDialog() {
      this.addSemesterDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editSessionDialog = false
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











