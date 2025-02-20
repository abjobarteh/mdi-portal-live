<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Agents</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
      
        </v-toolbar>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="users"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.status`]="{ item }">
              <fas
                @click="blockUser(item.id)"
                v-if="item.is_active == 1"
                icon="fa-solid fa-check-circle"
                style="color: green; font-size: 24px"
              />
              <fas @click="UnBlockUser(item.id)" v-else icon="fa-solid fa-ban" style="color: red; font-size: 24px" />
            </template>
            <template v-slot:[`item.fullname`]="{ item }">
              {{ item.firstname + ' ' + item.lastname }}
            </template>
            <template v-slot:[`item.created_at_abbreviated`]="{ item }">
              {{ item.registered_at }}
            </template>
         
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add user duration dialog -->
    <v-dialog v-model="addUserDialog" max-width="750px">
      <v-card>
        <v-card-title>Add User</v-card-title>
        <v-card-text>
          <v-form ref="addUserForm">
            <v-text-field outlined v-model="addUserFormData.username" label="Username"></v-text-field>
            <v-text-field outlined v-model="addUserFormData.email" label="Email"></v-text-field>
            <v-text-field outlined v-model="addUserFormData.address" label="Company Issued To"></v-text-field>
            <v-row>
              <v-col cols="6">
                <v-text-field outlined v-model="addUserFormData.password" label="Password"></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field
                  outlined
                  v-model="addUserFormData.password_confirmation"
                  label="Confirm Password"
                ></v-text-field>
              </v-col>
            </v-row>
            <v-text-field outlined v-model="addUserFormData.phonenumber" label="Phone Number"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddUserForm">Add</v-btn>
          <v-btn color="secondary" @click="addUserDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Edit user duration dialog -->
    <v-dialog v-model="editUserDialog" max-width="750px">
      <v-card>
        <v-card-title>Edit User</v-card-title>
        <v-card-text>
          <v-form ref="editUserForm">
            <v-text-field outlined v-model="editedItem.username" label="Username"></v-text-field>
            <v-text-field outlined v-model="editedItem.email" label="Email"></v-text-field>
            <v-text-field outlined v-model="editedItem.address" label="Location"></v-text-field>
            <v-text-field outlined v-model="editedItem.phonenumber" label="Phone Number"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitUpdateAgent">Save Changes</v-btn>
          <v-btn color="secondary" @click="editUserDialog = false">Cancel</v-btn>
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
  name: 'User',
  props: {},
  components: {},
  data() {
    return {
      users: [],

      headers: [
        { text: 'Email', value: 'email' },
        { text: 'Username', value: 'username' },
        { text: 'User Type', value: 'role.rank' },
        { text: 'Date Registered', value: 'created_at_abbreviated' },
        { text: 'Status', value: 'status', sortable: false },
      ],
      items: [],
      dialog: false,
      editedIndex: -1,
      editedItem: {
        id: null,
        phonenumber: '',
        email: '',
        address: '',
        username: '',
      },
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new User /////////
      addUserDialog: false,
      addUserFormData: {
        phonenumber: '',
        password: '',
        password_confirmation: '',
        email: '',
        address: '',
      },

      // edit User
      editUserDialog: false,
      editUserFormData: {
        course_code: '',
        course_name: '',
        program_id: '',
      },

      rules: {
        name: { required },
      },

      v$: useVuelidate(this.rules, this.addUserFormData),
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/view-agents?page=' + this.page)
        .then(response => {
          this.users = response.data.result.data
          console.log('users ', this.users)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.users = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.users)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'users')
      XLSX.writeFile(workbook, 'users.xlsx')
    },

    editUser(item) {
      this.editedIndex = this.items.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editUserDialog = true
    },

    submitUpdateAgent() {
      // make a PUT request to update the gradingSystem data
      // axios.post(`/api/update-agent/${this.editedItem.id}`, this.editedItem).then(response => {
      //   // show a success notification
      //   this.$toast.success('users information has been updated.')
      //   // refresh the data table
      //   this.getResults()
      // })
      axios
        .post(`/api/update-agent/${this.editedItem.id}`, this.editedItem)
        .then(result => {
          // show success alert
          this.editUserDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'User update successfully.',
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
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
      // hide the dialog
      this.editUserDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },
    cancelEdit() {
      // hide the dialog
      this.editUserDialog = false
      // clear the edited item
      this.editedItem = {
        id: null,
        name: '',
      }
      this.editedIndex = -1
    },

    deleteUser(item) {
      // perform delete action on item
      console.log(`Deleting user ${item.id}`)
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
              .delete(`/api/delete-user/${item.id}`)
              .then(result => {
                // show success alert
                swal
                  .fire({
                    title: 'Success!',
                    text: 'User deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.getResults()
                  })
              })
              .catch(err => {
                swal.fire({
                  title: 'Error!',
                  text: err.response.data.error,
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },

    showeditUserDialog() {
      this.editUserDialog = true
    },

    ////////  Adding ///////
    async submitaddUserForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-agent', this.addUserFormData)
          .then(result => {
            // show success alert
            this.addUserDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Agent Added successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
              })
              .then(() => {
                ;(this.addUserFormData.phonenumber = ''),
                  (this.addUserFormData.password = ''),
                  (this.addUserFormData.password_confirmation = ''),
                  (this.addUserFormData.email = ''),
                  (this.addUserFormData.address = ''),
                  this.getResults()
              })
          })
          .catch(error => {
            // show error alert
            swal.fire({
              title: 'Error!',
              text: error.response.data.message,
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: error.response.data.message,
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },
    showAddUserDialog() {
      this.addUserDialog = true
    },

    cancelAdd() {
      // Clear the new User name and hide the add dialog
      this.newUserName = ''
      this.editUserDialog = false
    },
    // /block-user/{id}
    blockUser(id) {
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, block user!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.put(`/api/block-user/${id}`).then(response => {
              // show a success notification
              swal.fire('Blocked!', 'User has been blocked.', 'success')
              // refresh the data table
              this.getResults()
            })
          }
        })
    },

    UnBlockUser(id) {
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, unblock user!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.put(`/api/unblock-user/${id}`).then(response => {
              // show a success notification
              swal.fire('UnBlocked!', 'User has been unblocked.', 'success')
              // refresh the data table
              this.getResults()
            })
          }
        })
    },
  },

  computed: {
    filteredcoures() {
      return this.users.filter(user => {
        return (
          user.firstname.toLowerCase().includes(this.search.toLowerCase()) ||
          user.lastname.toLowerCase().includes(this.search.toLowerCase())
        )
      })
    },
  },
}
</script>











