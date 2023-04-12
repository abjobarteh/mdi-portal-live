<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Customers registered with promocode</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="filteredCustomers"
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
            <v-text-field outlined v-model="editedItem.firstname" label="First Name"></v-text-field>
            <v-text-field outlined v-model="editedItem.lastname" label="Last Name"></v-text-field>
            <v-text-field outlined v-model="editedItem.address" label="Address"></v-text-field>
            <v-text-field outlined v-model="editedItem.telephone" label="Telephone"></v-text-field>
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
  name: 'Customers',
  props: {},
  components: {},
  data() {
    return {
      customers: [],
      headers: [
        { text: 'First Name', value: 'firstname' },
        { text: 'Last Name', value: 'lastname' },
        { text: 'Address', value: 'address' },
        { text: 'Telephone', value: 'telephone' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      items: [],
      dialog: false,
      editedIndex: -1,
      editedItem: {
        id: null,
        firstname: '',
        lastname: '',
        address: '',
        telephone: '',
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
        .get('/api/view-employees?page=' + this.page)
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
      // make a PUT request to update the customer data
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
        firstname: '',
        lastname: '',
        address: '',
        telephone: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.dialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        firstname: '',
        lastname: '',
        address: '',
        telephone: '',
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
    filteredCustomers() {
      return this.customers.filter(customer => {
        return (
          customer.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
          customer.lastname.toLowerCase().includes(this.search.toLowerCase()) ||
          customer.address.toLowerCase().includes(this.search.toLowerCase()) ||
          customer.telephone.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











