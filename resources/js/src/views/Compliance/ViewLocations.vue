<template>
    <div>
      <v-container fluid>
        <v-card>
          <v-toolbar color="primary" dark>
            <v-toolbar-title>Location</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-text-field v-model="search" label="Filter this page" append-icon="mdi-magnify" clearable
              hide-details></v-text-field>
            <v-btn icon @click="showSearchDialog">
              <fas icon="search"></fas>
            </v-btn>
            <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        
          </v-toolbar>
  
          <v-dialog v-model="searchDialog" max-width="400">
            <v-card>
              <v-card-title>Advanced Search</v-card-title>
              <v-card-text>
                <!-- Add your dropdown or any additional search options here -->
                <v-select v-model="selectedItem" :items="items" label="Search by Item"></v-select>
                <v-text-field v-model="advanceSearch" :label="advanceSearchLabel" append-icon="mdi-magnify" clearable
                  hide-details></v-text-field>
              </v-card-text>
              <v-card-actions>
                <v-btn color="primary" :disabled="selectedItem == null || advanceSearch === ''"
                  @click="performAdvancedSearch">Search</v-btn>
                <v-btn @click="closeSearchDialog">Close</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
  
          <!-- Data table -->
          <v-card-text>
            <v-data-table :headers="headers" :items="location" :items-per-page="13" :search="search" class="elevation-1"
              hide-default-footer>
              <template v-slot:[`item.action`]="{ item }">
                <v-btn small color="primary" @click="allocatelocation(item)">Allocate</v-btn>
                <v-btn small color="primary" @click="editlocation(item)">Edit</v-btn>
                <v-btn small color="error" @click="deleteCourse(item)">Delete</v-btn>
              </template>
            </v-data-table>
            <v-pagination v-model="page" :length="pageCount" @input="getResults" />
          </v-card-text>
        </v-card>
      </v-container>
  
      <!-- Add course duration dialog -->
      <v-dialog v-model="addLocationDialog" max-width="500px">
        <v-card>
          <v-card-title>Add Location</v-card-title>
          <v-card-text>
            <v-form ref="addLocationForm">
              <v-text-field outlined v-model="addLocationformData.location_code" label="Location Code"></v-text-field>
              <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
                v-for="error in v$.value.location_code.$errors" :key="error.$uid">{{ error.$message }}</span>
              <v-text-field outlined v-model="addLocationformData.location_name" label="Location Name"></v-text-field>
              <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
                v-for="error in v$.value.location_name.$errors" :key="error.$uid">{{ error.$message }}</span>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="submitaddLocationForm">Add</v-btn>
            <v-btn color="secondary" @click="addLocationDialog = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
  
      <!-- Edit course duration dialog -->
      <v-dialog v-model="editlocationDialog" max-width="500px">
        <v-card>
          <v-card-title> Edit Location </v-card-title>
          <v-card-text>
            <v-form ref="addLocationForm">
              <v-text-field outlined v-model="editLocationFormData.location_code" label="Location Code"></v-text-field>
              <v-text-field outlined v-model="editLocationFormData.location_name" label="Location Name"></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="submitupdateLocationForm">Update</v-btn>
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
    name: 'Course',
    props: {},
    components: {},
    data() {
      return {
        advanceSearchLabel: '',
        advanceSearch: '',
        searchDialog: false,
        selectedItem: null,
        items: [
          // Your dropdown items here
          { text: 'Location Name', value: '1' },
          
          { text: 'Location Code', value: '2' },
        ],
  
        programs: [],
        location: [],
        headers: [
          { text: 'Location Code', value: 'location_code' },
          { text: 'Location', value: 'location_name' },
         
        ],
        // items: [],
        dialog: false,
        editedIndex: -1,
        editLocationFormData: {
          id: null,
          name: '',
        },
        page: 1,
        pageCount: 0,
        search: '',
  
        //////////////// add new department /////////
        addLocationDialog: false,
        addLocationformData: {
          location_code: '',
          location_name: '',
        },
  
        // edit department
        editlocationDialog: false,
        editLocationFormData: {
          id: null,
          location_code: '',
          location_name: '',
        },
  
        rules: {
          location_code: { required },
          location_name: { required },
        },
  
        v$: useVuelidate(this.rules, this.addLocationformData),
      }
    },
  
    created() {
      this.getResults()
      this.setupValidation()
    },
  
    watch: {
      selectedItem(newValue) {
        // Find the corresponding text based on the selected value
        const selectedItemObject = this.items.find(item => item.value === newValue)
  
        // Update the label with the text associated with the selected value
        this.advanceSearchLabel = selectedItemObject ? 'search by ' + selectedItemObject.text.toLowerCase() : ''
      },
    },
  
    methods: {
      showSearchDialog() {
        this.searchDialog = true
      },
      closeSearchDialog() {
        this.searchDialog = false
      },
      performAdvancedSearch() {
        // Implement your advanced search logic here
        console.log('Performing advanced search...')
        // Close the dialog after searching
        axios
          .get('/api/view-locations', {
            params: {
              page: this.page,
              advanceSearch: this.advanceSearch,
              selectedItem: this.selectedItem, // Add the selectedItem here
            },
          })
          .then(response => {
            this.location = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            this.location = []
            this.pageCount = 0
          })
        this.closeSearchDialog()
      },
      setupValidation() {
        this.v$ = useVuelidate(this.rules, this.addLocationformData)
      },
      getResults() {
        axios
          .get('/api/view-locations?page=' + this.page)
          .then(response => {
            this.location = response.data.result.data
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            this.location = []
            this.pageCount = 0
          })
  
        /* axios
           .get('/api/view-programs?page=' + this.page)
           .then(response => {
             this.programs = response.data.result.data
           })
           .catch(err => {
             this.programs = []
           }) */
      },
  
      exportToExcel() {
        const worksheet = XLSX.utils.json_to_sheet(this.location)
        const workbook = XLSX.utils.book_new()
        XLSX.utils.book_append_sheet(workbook, worksheet, 'location')
        XLSX.writeFile(workbook, 'location.xlsx')
      },
  
      editlocation(item) {
        this.editedIndex = this.items.indexOf(item)
        this.editLocationFormData = Object.assign({}, item)
        this.editlocationDialog = true
      },
  
  
      allocatelocation(item) {
  
      },
  
      // submitupdateLocationForm() {
      //   // make a PUT request to update the gradingSystem data
      //   axios.put(`/api/course/${this.editLocationFormData.id}`, this.editLocationFormData).then(response => {
      //     // show a success notification
      //     this.$toast.success('location information has been updated.')
      //     // refresh the data table
      //     this.getResults()
      //   })
      //   // hide the dialog
      //   this.editlocationDialog = false
      //   // clear the edited item
      //   this.editLocationFormData = {
      //     id: null,
      //     course_code: '',
      //     course_name: '',
      //     location_id: '',
      //   }
      //   this.editedIndex = -1
      // },
      submitupdateLocationForm() {
        // make a PUT request to update the gradingSystem data
        console.log(this.editLocationFormData.location_id)
        axios
          .post(`/api/update-location/${this.editLocationFormData.location_id}`, this.editLocationFormData)
          .then(response => {
            // show success alert
            this.editlocationDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Location  updated successfully.',
                icon: 'success',
                confirmButtonText: 'OK',
              })
              .then(() => {
                this.getResults()
              })
          })
          .catch(error => {
              // Check if the error response exists and contains a message
              const errorMessage = error.response && error.response.data && error.response.data.message
                ? error.response.data.message
                : 'Failed to add Location';
  
              // Show error alert with the appropriate message
              swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK',
              });
            })
        // sell-code/{id}
        // hide the dialog
        this.editlocationDialog = false
        // clear the edited item
        this.editLocationFormData = {
          id: null,
          location_code: '',
          location_name: '',
          location_id: '',
        }
        this.editedIndex = -1
      },
      cancelEdit() {
        // hide the dialog
        this.editlocationDialog = false
        // clear the edited item
        this.editLocationFormData = {
          id: null,
          course_code: '',
          course_name: '',
          location_id: '',
        }
        this.editedIndex = -1
      },
  
      deleteCourse(item) {
        // perform delete action on item
        console.log(item.location_id)
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
              axios.post(`/api/delete-location/${item.location_id }`).then(result => {
                // show success alert
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Location deleted successfully.',
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
  
      showeditlocationDialog() {
        this.editlocationDialog = true
      },
  
      ////////  Adding ///////
      async submitaddLocationForm() {
        console.log(this.addLocationformData.location_name);
        console.log(this.addLocationformData.location_code);
  
        const result = await this.v$.value.$validate()
        console.log(result);
        console.log('Validation result:', result);
        console.log('Errors:', this.v$.value.$errors);
        if (result) {
          axios
            .post('/api/add-location', this.addLocationformData)
            .then(result => {
              // show success alert
              this.addLocationDialog = false
              swal
                .fire({
                  title: 'Success!',
                  text: 'Location added successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.getResults()
                })
            })
            .catch(error => {
              // Check if the error response exists and contains a message
              const errorMessage = error.response && error.response.data && error.response.data.message
                ? error.response.data.message
                : 'Failed to add Location';
  
              // Show error alert with the appropriate message
              swal.fire({
                title: 'Error!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK',
              });
            });
        } else {
          swal.fire({
            title: 'Error!',
            text: 'Error Saving Location',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        }
      },
      showAddCourseDialog() {
        this.addLocationDialog = true
      },
  
      cancelAdd() {
        // Clear the new department name and hide the add dialog
        this.newDepartmentName = ''
        this.editlocationDialog = false
      },
    },
  
    computed: {
      filteredcoures() {
        return this.location.filter(course => {
          return (
            course.course_name.toLowerCase().includes(this.search.toLowerCase()) ||
            course.course_code.toLowerCase().includes(this.search.toLowerCase())
          )
        })
      },
    },
  }
  </script>