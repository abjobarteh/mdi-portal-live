<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Program Duration</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddProgramDurationDialog">Add Duration</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredprogramDurations"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="editProgramDuration(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteProgramDuration(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add program duration dialog -->
    <v-dialog v-model="addProgramDurationDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Program Duration</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-text-field outlined v-model="addProgramDurationFormData.duration" label="Duration"></v-text-field>
            <v-select
              outlined
              v-model="addProgramDurationFormData.description"
              :items="['month', 'year']"
              label="Description"
            ></v-select>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddProgramDurationForm">Add</v-btn>
          <v-btn color="secondary" @click="addProgramDurationDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit program duration dialog -->
    <v-dialog v-model="editProgramDurationDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Program Duration </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="editProgramDurationFormData.duration" label="Duration"></v-text-field>
            <v-select
              outlined
              v-model="editProgramDurationFormData.description"
              :items="['month', 'year']"
              label="Description"
            ></v-select>
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
  name: 'ProgramDuration',
  props: {},
  components: {},
  data() {
    return {
      programDurations: [],
      headers: [
        { text: 'Duration', value: 'duration' },
        { text: 'Description', value: 'description' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      editedIndex: -1,
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new department /////////
      addProgramDurationDialog: false,
      addProgramDurationFormData: {
        duration: '',
        description: '',
      },

      // edit department
      editProgramDurationDialog: false,
      editProgramDurationFormData: {
        id: null,
        duration: '',
        description: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addProgramDurationFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-program-durations?page=' + this.page)
        .then(response => {
          this.programDurations = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.programDurations = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.programDurations)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'programDurations')
      XLSX.writeFile(workbook, 'programDurations.xlsx')
    },

    editProgramDuration(item) {
      this.editedIndex = this.programDurations.indexOf(item)
      this.editProgramDurationFormData = Object.assign({}, item)
      console.log('program ', this.editProgramDurationFormData, ' ', item)
      this.editProgramDurationDialog = true
    },

    submitupdateProgramDurationForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/program-duration/${this.editProgramDurationFormData.id}`, this.editProgramDurationFormData)
        .then(response => {
          // show success alert
          this.editProgramDurationDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Program Duration updated successfully.',
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
            text: 'Failed to update program duration.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editProgramDurationDialog = false
      // clear the edited item
      this.addProgramDurationFormData = {
        id: null,
        duration: '',
        description: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editProgramDurationDialog = false
      // clear the edited item
      this.editProgramDuration = {
        id: null,
        duration: '',
        description: '',
      }
      this.editedIndex = -1
    },

    deleteProgramDuration(item) {
      // perform delete action on item
      console.log(`Deleting program duration ${item.id}`)
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
            axios
              .delete(`/api/delete-program-duration/${item.id}`)
              .then(result => {
                // show success alert
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Program Duration deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.getResults()
                  })
              })
              .catch(error => {
                console.error('good', error.response.data.error)
                // show error alert
                swal.fire({
                  title: 'Error!',
                  text: error.response.data.error,
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },

    showeditProgramDurationDialog() {
      this.editProgramDurationDialog = true
    },

    ////////  Adding ///////
    async submitaddProgramDurationForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-program-duration', this.addProgramDurationFormData)
          .then(result => {
            // show success alert
            this.addProgramDurationDialog = false
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
              text: 'Failed to add program duration.',
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
    showAddProgramDurationDialog() {
      this.addProgramDurationDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editProgramDurationDialog = false
    },
  },

  computed: {
    filteredprogramDurations() {
      return this.programDurations.filter(duration => {
        return (
          // duration.duration.toLowerCase().includes(this.search.toLowerCase()) ||
          duration.description.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











