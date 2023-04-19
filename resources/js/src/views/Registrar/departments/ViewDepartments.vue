
<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Departments</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
          <v-btn color="primary" small class="white--text" @click="showAddDialog">Add Department</v-btn>
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredDepartments"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <v-btn small color="primary" @click="editDepartment(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteDepartment(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add department dialog -->
    <v-dialog v-model="addDepartmentDialog" max-width="500px">
      <v-card>
        <v-card-title>Add Department</v-card-title>
        <v-card-text>
          <v-form ref="addDepartmentForm">
            <v-text-field outlined v-model="addDepartmentFormData.name" label="Department Name"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddDepartmentForm">Add</v-btn>
          <v-btn color="secondary" @click="addDepartmentDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit department dialog -->
    <v-dialog v-model="editDepartmentDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Department </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="editDepartmentFormData.name" label="Department Name"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitEditDepartmentForm">Update</v-btn>
          <v-btn color="secondary" @click="cancelEdit">Cancel</v-btn>
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
  name: 'Departments',
  props: {},
  components: {},
  data() {
    return {
      departments: [],
      headers: [
        { text: 'Department Name', value: 'name' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      editDepartmentDialog: false,
      editedIndex: -1,
      editDepartmentFormData: {
        id: null,
        name: '',
      },
      page: 1,
      pageCount: 0,
      search: '',

      addDialog: false,

      //////////////// add new department /////////
      addDepartmentDialog: false,

      addDepartmentFormData: {
        name: '',
      },

      rules: {
        name: { required, minLength: minLength(2) },
      },

      v$: null,
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addDepartmentFormData)
    },
    getResults() {
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
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.departments)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'departments')
      XLSX.writeFile(workbook, 'departments.xlsx')
    },

    editDepartment(item) {
      this.editedIndex = this.departments.indexOf(item)
      this.editDepartmentFormData = Object.assign({}, item)
      this.editDepartmentDialog = true
    },

    submitEditDepartmentForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/department/${this.editDepartmentFormData.id}`, this.editDepartmentFormData)
        .then(response => {
          // show success alert
          this.editDepartmentDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Department updated successfully.',
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
            text: 'Failed to update department.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editDepartmentDialog = false
      // clear the edited item
      this.editDepartmentFormData = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editDepartmentDialog = false
      // clear the edited item
      this.editDepartmentFormData = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },

    deleteDepartment(item) {
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
            axios.delete(`/api/delete-department/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Department deleted successfully.',
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

    showAddDialog() {
      this.addDepartmentDialog = true
    },

    cancelAdd() {
      // Clear the new department name and hide the add dialog
      this.newDepartmentName = ''
      this.addDialog = false
    },

    async submitaddDepartmentForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-department', this.addDepartmentFormData)
          .then(result => {
            // show success alert
            this.addDepartmentDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Department added successfully.',
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
              text: 'Failed to add department.',
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to add department.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },
  },

  computed: {
    filteredDepartments() {
      return this.departments.filter(department => {
        return department.name.toLowerCase().includes(this.search.toLowerCase())
      })
    },
  },
}
</script>











