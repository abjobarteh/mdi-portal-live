<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Users</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
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
            <v-row>
              <v-col cols="6">
                <v-text-field outlined v-model="addUserFormData.firstname" label="First Name"></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field outlined v-model="addUserFormData.middlename" label="Middle Name"></v-text-field>
              </v-col>
            </v-row>
            <v-text-field outlined v-model="addUserFormData.lastname" label="Last Name"></v-text-field>
            <v-text-field outlined v-model="addUserFormData.username" label="Username"></v-text-field>
            <v-text-field outlined v-model="addUserFormData.email" label="Email"></v-text-field>
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

            <v-select
              outlined
              v-model="addUserFormData.role_id"
              :items="roles.map(role => ({ id: role.id, name: role.rank }))"
              item-value="id"
              item-text="name"
              label="User Type"
            ></v-select>
            <v-select
              v-if="showDepartments"
              outlined
              v-model="addUserFormData.main_department_id"
              :items="departments.map(department => ({ id: department.id, name: department.name }))"
              item-value="id"
              item-text="name"
              label="Lecturer's Main Department?"
              @input="onDepartmentSelected"
            ></v-select>
            <v-select
              v-if="showhodDepts"
              outlined
              v-model="addUserFormData.main_department_id"
              :items="departments.map(department => ({ id: department.id, name: department.name }))"
              item-value="id"
              item-text="name"
              label="Hod Department?"
            ></v-select>
            <v-select
              v-if="showDepartments"
              outlined
              v-model="addUserFormData.lecturer_type"
              :items="addUserFormData.lecturerTypeOptions"
              required
              ><template v-slot:label>
                <span class="required-field">Lecturer Type</span>
              </template></v-select
            >

            <v-select
              v-if="showDepartments"
              outlined
              v-model="addUserFormData.department_id"
              multiple
              :items="departments.map(department => ({ id: department.id, name: department.name }))"
              item-value="id"
              item-text="name"
              label="Can teach courses in which departments?"
              @input="onDepartmentSelected"
            ></v-select>

            <v-select
              v-if="selectedDepartment && showDepartments"
              v-model="addUserFormData.course_ids"
              multiple
              :items="getFlattenedCourses(selectedDepartment)"
              item-value="id"
              item-text="name"
              label="Teachable Courses"
              outlined
            ></v-select>
            <v-text-field outlined v-model="addUserFormData.address" label="Residential Address"></v-text-field>
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
            <v-row>
              <v-col cols="6">
                <v-text-field outlined v-model="editedItem.firstname" label="First Name"></v-text-field>
              </v-col>
              <v-col cols="6">
                <v-text-field outlined v-model="editedItem.middlename" label="Middle Name"></v-text-field>
              </v-col>
            </v-row>
            <v-text-field outlined v-model="editedItem.lastname" label="Last Name"></v-text-field>
            <v-text-field outlined v-model="editedItem.username" label="Username"></v-text-field>
            <v-text-field outlined v-model="editedItem.email" label="Email"></v-text-field>
            <v-select
              outlined
              v-model="editedItem.role_id"
              :items="roles.map(role => ({ id: role.id, name: role.rank }))"
              item-value="id"
              item-text="name"
              label="User Type"
            ></v-select>
            <v-select
              v-if="showDepartments"
              outlined
              v-model="editedItem.lecturer_type"
              :items="editedItem.lecturerTypeOptions"
              required
            >
              <template v-slot:label>
                <span class="required-field">Lecturer Type</span>
              </template>
            </v-select>

            <v-select
              v-if="showDepartments"
              outlined
              v-model="editedItem.department_id"
              multiple
              :items="departments.map(department => ({ id: department.id, name: department.name }))"
              item-value="id"
              item-text="name"
              label="Department"
              @input="onDepartmentSelected"
            ></v-select>

            <v-select
              v-if="selectedDepartment && showDepartments"
              v-model="editedItem.course_ids"
              multiple
              :items="getFlattenedCourses(selectedDepartment)"
              item-value="id"
              item-text="name"
              label="Teachable Courses"
              outlined
            ></v-select>
            <v-text-field outlined v-model="editedItem.address" label="Residential Address"></v-text-field>
            <v-text-field outlined v-model="editedItem.phonenumber" label="Phone Number"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitupdateUserForm">Save Changes</v-btn>
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
      roles: [],
      users: [],
      departments: [],
      showDepartments: false,
      showhodDepts: false,
      selectedDepartment: [],

      headers: [
        { text: 'Full Name', value: 'fullname' },
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
        name: '',
      },
      page: 1,
      pageCount: 0,
      search: '',

      //////////////// add new User /////////
      addUserDialog: false,
      addUserFormData: {
        lecturer_type: '',
        lecturerTypeOptions: ['Fulltime', 'Part-time'],
        firstname: '',
        middlename: '',
        lastname: '',
        address: '',
        phonenumber: '',
        password: '',
        password_confirmation: '',
        role_id: '',
        username: '',
        email: '',
        department_id: '',
        course_ids: [],
        main_department_id: '',
      },

      editedItem: {
        firstname: '',
        middlename: '',
        lastname: '',
        username: '',
        email: '',
        role_id: '',
        lecturer_type: '',
        lecturerTypeOptions: ['Fulltime', 'Part-time'],
        department_id: '',
        course_ids: [],
        address: '',
        phonenumber: '',
        // Add other properties as needed
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

  watch: {
    'addUserFormData.role_id': function (newVal, oldVal) {
      if (newVal === 3) {
        this.showDepartments = true
      } else if (newVal === 7) {
        this.showhodDepts = true
      } else {
        this.showDepartments = false
        this.showhodDepts = false
      }
    },
  },

  created() {
    this.getResults()
  },

  methods: {
    getFlattenedCourses(selectedDepartments) {
      // Flatten courses from selected departments
      return this.selectedDepartment.flatMap(department => {
        return department.courses.map(course => ({
          id: course.id,
          name: course.course_name,
        }))
      })
    },
    onDepartmentSelected() {
      // Remove deselected departments
      this.selectedDepartment = this.departments.filter(department =>
        this.addUserFormData.department_id.includes(department.id),
      )

      console.log('selected de', this.selectedDepartment)

      // this.addUserFormData.course_id = null
    },
    getResults() {
      axios
        .get('/api/view-users?page=' + this.page)
        .then(response => {
          this.users = response.data.result.data
          console.log('users ', this.users)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.users = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-roles?page=' + this.page)
        .then(response => {
          this.roles = response.data.result.data
        
        })
        .catch(err => {
          this.programs = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-departments?page=' + this.page)
        .then(response => {
          this.departments = response.data.result.data
        
        })
        .catch(err => {
          this.departments = []
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

    submitupdateUserForm() {
      // make a PUT request to update the gradingSystem data
      axios.put(`/api/update-account-info/${this.editedItem.id}`, this.editedItem).then(response => {
        // show a success notification
        swal
                  .fire({
                    title: 'Success!',
                    text: 'User Edited successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.getResults()
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
          .post('/api/add-user', this.addUserFormData)
          .then(result => {
            // show success alert
            this.addUserDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'User added successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
              })
              .then(() => {
                this.addUserFormData.firstname = ''
                this.addUserFormData.middlename = ''
                this.addUserFormData.lastname = ''
                this.addUserFormData.address = ''
                this.addUserFormData.phonenumber = ''
                this.addUserFormData.password = ''
                this.addUserFormData.password_confirmation = ''
                ;(this.addUserFormData.role_id = ''), (this.addUserFormData.username = '')
                this.addUserFormData.email = ''
                this.addUserFormData.department_id = []
                this.addUserFormData.course_ids = []
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











