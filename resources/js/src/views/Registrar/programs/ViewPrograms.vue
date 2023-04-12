<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Program</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddProgramDialog">Add Program</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredprograms"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:item.duration="{ item }">
              {{ item.duration.duration }} {{ item.duration.description }}
            </template>
            <template v-slot:item.action="{ item }">
              <v-btn small color="primary" @click="editProgram(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteItem(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add program duration dialog -->
    <v-dialog v-model="addProgramDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Program</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-select
              outlined
              v-model="addProgramFormData.department_id"
              :items="departments.map(department => ({ id: department.id, name: department.name }))"
              item-value="id"
              item-text="name"
              label="Department"
            ></v-select>
            <v-text-field outlined v-model="addProgramFormData.program_name" label="Program Name"></v-text-field>
            <v-text-field
              outlined
              v-model="addProgramFormData.program_abbreviation"
              label="Program Abbreviation"
            ></v-text-field>
            <v-select
              outlined
              v-model="addProgramFormData.program_duration_id"
              :items="programDurations.map(duration => ({ id: duration.id, name: duration.duration }))"
              item-value="id"
              item-text="name"
              label="Program Duration"
            ></v-select>
            <v-text-field outlined v-model="addProgramFormData.fee" label="Program Fee"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddProgramForm">Add</v-btn>
          <v-btn color="secondary" @click="addProgramDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit program duration dialog -->
    <v-dialog v-model="editProgramDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Department </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="addProgramFormData.name" label="Department Name"></v-text-field>
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
  name: 'Program',
  props: {},
  components: {},
  data() {
    return {
      programs: [],
      departments: [],
      programDurations: [],
      headers: [
        { text: 'Department', value: 'department.name' },
        { text: 'Program Name', value: 'name' },
        { text: 'Program Fee', value: 'fee' },
        { text: 'Program Duration', value: 'duration', sortable: false },

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
      addProgramDialog: false,
      addProgramFormData: {
        program_name: '',
        program_abbreviation: '',
        department_id: '',
        program_duration_id: '',
        fee: '',
      },

      // edit department
      editProgramDialog: false,
      editDepartmentFormData: {
        name: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addProgramFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
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
      axios
        .get('/api/view-departments?page=' + this.page)
        .then(response => {
          this.departments = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.departments = []
          this.pageCount = 0
        })
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
      const worksheet = XLSX.utils.json_to_sheet(this.programs)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'programs')
      XLSX.writeFile(workbook, 'programs.xlsx')
    },

    editProgram(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editProgramDialog = true
    },

    submitupdateProgramDurationForm() {
      // make a PUT request to update the gradingSystem data
      axios.put(`/api/program/${this.editedItem.id}`, this.editedItem).then(response => {
        // show a success notification
        this.$toast.success('programs information has been updated.')
        // refresh the data table
        this.getResults()
      })
      // hide the dialog
      this.editProgramDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editProgramDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },

    deleteItem(item) {
      // perform delete action on item
      console.log(`Deleting item ${item.id}`)
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
            swal.fire('Deleted!', 'Your file has been deleted.', 'success')
          }
        })
    },

    showeditProgramDialog() {
      this.editProgramDialog = true
    },

    ////////  Adding ///////
    async submitaddProgramForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-program', this.addProgramFormData)
          .then(result => {
            // show success alert
            this.addProgramDialog = false
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
    showAddProgramDialog() {
      this.addProgramDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.editProgramDialog = false
    },
  },

  computed: {
    filteredprograms() {
      return this.programs.filter(duration => {
        return (
          duration.name.toLowerCase().includes(this.search.toLowerCase()) ||
          duration.fee.toLowerCase().includes(this.search.toLowerCase()) ||
          duration.department.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











