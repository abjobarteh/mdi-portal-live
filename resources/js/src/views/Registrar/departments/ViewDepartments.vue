<!-- <template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Grading System</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredDepartments"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:item.action="{ item }">
              <v-btn small color="primary" @click="editItem(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteItem(item)">Delete</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <v-dialog v-model="dialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Customer Information </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="editedItem.name" label="Department Name"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="saveItem">Save</v-btn>
          <v-btn color="secondary" @click="cancelEdit">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template> -->
<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Grading System</v-toolbar-title>
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
            <template v-slot:item.action="{ item }">
              <v-btn small color="primary" @click="editItem(item)">Edit</v-btn>
              <v-btn small color="error" @click="deleteItem(item)">Delete</v-btn>
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
            <v-text-field v-model="addDepartmentFormData.name" label="Department Name"></v-text-field>
            <!-- <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            > -->
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddDepartmentForm">Add</v-btn>
          <v-btn color="secondary" @click="addDepartmentDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit department dialog -->
    <v-dialog v-model="addDialog" max-width="500px">
      <v-card>
        <v-card-title> Add Department </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="addDepartmentFormData.name" label="Department Name"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddDepartmentForm">Add</v-btn>
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
  name: 'GradingSystem',
  props: {},
  components: {},
  data() {
    return {
      departments: [],
      headers: [
        { text: 'Department Name', value: 'name' },
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

      addDialog: false,

      //////////////// add new department /////////
      addDepartmentDialog: false,

      // edit department
      editDepartmentFormData: {
        name: '',
      },

      addDepartmentFormData: {
        name: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addDepartmentFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
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

    editItem(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    saveItem() {
      // make a PUT request to update the gradingSystem data
      axios.put(`/api/departments/${this.editedItem.id}`, this.editedItem).then(response => {
        // show a success notification
        this.$toast.success('Customer information has been updated.')
        // refresh the data table
        this.getResults()
      })
      // hide the dialog
      this.dialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.dialog = false
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

    showAddDialog() {
      this.addDialog = true
    },
    // addDepartment() {
    //   // Perform any necessary validation on the newDepartmentName property
    //   // Add the new department to the list of items
    //   // hit the endpoint here

    //   // Hide the add dialog
    //   this.addDialog = false
    // },
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
            this.addDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Employee added successfully.',
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
              text: 'Failed to add employee.',
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











