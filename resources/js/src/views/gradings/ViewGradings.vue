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
            <v-text-field outlined v-model="editedItem.mark_from" label="Mark From"></v-text-field>
            <v-text-field outlined v-model="editedItem.mark_to" label="Mark To"></v-text-field>
            <v-text-field outlined v-model="editedItem.grade" label="Grade"></v-text-field>
            <v-text-field outlined v-model="editedItem.interpretation" label="Interpretation"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="saveItem">Save</v-btn>
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
        { text: 'Action', value: 'action', sortable: false },
      ],
      items: [],
      dialog: false,
      editedIndex: -1,
      editedItem: {
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

    editItem(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.dialog = true
    },

    saveItem() {
      // make a PUT request to update the gradingSystem data
      axios.put(`/api/customers/${this.editedItem.id}`, this.editedItem).then(response => {
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
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.dialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        mark_from: '',
        mark_to: '',
        grade: '',
        interpretation: '',
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











