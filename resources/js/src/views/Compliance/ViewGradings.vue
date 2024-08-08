<template>
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
            :items="filteredGradings"
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

    <v-dialog v-model="editGradingDialog" max-width="500px">
      <v-card>
        <v-card-title> Edit Grading </v-card-title>
        <v-card-text>
          <v-form ref="form">
            <v-text-field outlined v-model="editGradingFormData.mark_from" label="Mark From"></v-text-field>
            <v-text-field outlined v-model="editGradingFormData.mark_to" label="Mark To"></v-text-field>
            <v-text-field outlined v-model="editGradingFormData.grade" label="Grade"></v-text-field>
            <v-text-field outlined v-model="editGradingFormData.interpretation" label="Interpretation"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitEditGradingForm">Save</v-btn>
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

Vue.use(Vue2Filters)

export default {
  name: 'GradingSystem',
  props: {},
  components: {},
  data() {
    return {
      customers: [],
      headers: [
        { text: 'Mark From', value: 'mark_from' },
        { text: 'Mark To', value: 'mark_to' },
        { text: 'Grade', value: 'grade' },
        { text: 'Interpretation', value: 'interpretation' },
      ],
      items: [],
      editGradingDialog: false,
      editedIndex: -1,
      editGradingFormData: {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      },
      page: 1,
      pageCount: 0,
      search: '',
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-gradings?page=' + this.page)
        .then(response => {
          this.customers = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.customers = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.customers)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Customers')
      XLSX.writeFile(workbook, 'customers.xlsx')
    },

    editGrading(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editGradingFormData = Object.assign({}, item)
      this.editGradingDialog = true
    },

    submitEditGradingForm() {
      // make a PUT request to update the gradingSystem data
      axios
        .put(`/api/grading/${this.editGradingFormData.id}`, this.editGradingFormData)
        .then(response => {
          // show success alert
          this.editDepartmentDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Grade updated successfully.',
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
            text: 'Failed to update grade.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editGradingDialog = false
      // clear the edited item
      this.editGradingFormData = {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the editGradingDialog
      this.editGradingDialog = false
      // clear the edited item
      this.editGradingFormData = {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      }
      this.editedIndex = -1
    },

    deleteGrading(item) {
      // perform delete action on item
      console.log(`Deleting grading ${item.id}`)
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
            axios.delete(`/api/delete-grading/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Grading deleted successfully.',
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
  },

  computed: {
    filteredGradings() {
      return this.customers.filter(grading => {
        return (
          // grading.mark_from.toLowerCase().includes(this.search.toLowerCase()) ||
          // grading.mark_to.toLowerCase().includes(this.search.toLowerCase()) ||
          grading.grade.toLowerCase().includes(this.search.toLowerCase()) ||
          grading.interpretation.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











