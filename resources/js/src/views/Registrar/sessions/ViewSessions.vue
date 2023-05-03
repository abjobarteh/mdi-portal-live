<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Session</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddSessionDialog">Add Session</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredsessions"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.is_current_session`]="{ item }">
              <fas
                v-if="item.is_current_session == 1"
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
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add session dialog -->
    <v-dialog v-model="addSessionDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Session</v-card-title>
        <v-card-text>
          <v-form ref="addSessionForm">
            <v-text-field outlined v-model="addSessionFormData.session_name" label="Session"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.session_name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-text-field
              outlined
              v-model="addSessionFormData.next_session"
              label="Next Session"
              type="date"
            ></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.next_session.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddSessionForm">Add</v-btn>
          <v-btn color="secondary" @click="addSessionDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit sessioni dialog -->
    <v-dialog v-model="editSessionDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Program </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="addSessionFormData.session_name" label="Session"></v-text-field>
            <v-text-field outlined v-model="addSessionFormData.next_session" label="Next Session"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitupdateSessionnForm">Update</v-btn>
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
      sessions: [],
      headers: [
        { text: 'Session', value: 'session_name' },
        { text: 'Is current Session', value: 'is_current_session' },
        { text: 'Next Session', value: 'next_session' },

        { text: 'Action', value: 'action', sortable: false },
      ],
      dialog: false,
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new department /////////
      addSessionDialog: false,
      addSessionFormData: {
        session_name: '',
        next_session: '',
      },

      // edit department
      editSessionDialog: false,
      editSessionFormData: {
        id: null,
        session_name: '',
        next_session: '',
      },

      rules: {
        session_name: { required },
        next_session: { required },
      },

      v$: useVuelidate(this.rules, this.addSessionFormData),
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addSessionFormData)
    },
    getResults() {
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
      const worksheet = XLSX.utils.json_to_sheet(this.sessions)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'sessions')
      XLSX.writeFile(workbook, 'sessions.xlsx')
    },

    editSession(item) {
      this.editedIndex = this.sessions.indexOf(item)
      this.editSessionFormData = Object.assign({}, item)
      this.editSessionFormData.program_name = item.name

      console.log(this.editSessionFormData)
      this.editSessionDialog = true
    },

    submitupdateSessionnForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/session/${this.editSessionFormData.id}`, this.editSessionFormData)
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
      this.editSessionFormData = {
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
      this.editSessionFormData = {
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
      this.editSessionDialog = true
    },

    ////////  Adding ///////
    async submitaddSessionForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-session', this.addSessionFormData)
          .then(result => {
            // show success alert
            this.addSessionDialog = false
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
    showAddSessionDialog() {
      this.addSessionDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editSessionDialog = false
    },
  },

  computed: {
    filteredsessions() {
      return this.sessions.filter(session => {
        return (
          session.session_name.toLowerCase().includes(this.search.toLowerCase()) ||
          session.is_current_session.toLowerCase().includes(this.search.toLowerCase()) ||
          session.next_session.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











