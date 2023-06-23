
<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Admission Codes Locations</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="primary" v-if="userRole != 6" small class="white--text" @click="showAddDialog"
            >Add AdmissionCodeLocation</v-btn
          >
        </v-toolbar>

        <div v-if="isLoading" class="spinner">
          <div class="double-bounce1"></div>
          <div class="double-bounce2"></div>
        </div>

        <!-- Data table -->
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="admissionCodeLocations"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.action`]="{ item }">
              <!-- <v-btn small color="primary" @click="showAdmissionCodes(item.id, item.admission_codes)">Codes</v-btn> -->
              <v-btn @click="openAdmissionCodesPopup(item)">View Codes</v-btn>
              <v-btn v-if="userRole != 6" small color="error" @click="deleteAdmissionCodeLocation(item)">Delete</v-btn>
              <v-btn v-if="userRole != 6" small color="secondary" @click="addAdmissionCode(item)">Add Codes</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>

    <!-- Add AdmissionCodeLocation dialog -->
    <v-dialog v-model="addAdmissionCodeLocationDialog" max-width="700px">
      <v-card>
        <v-card-title>Add AdmissionCodeLocation</v-card-title>
        <v-card-text>
          <v-form ref="addAdmissionCodeLocationForm">
            <v-text-field
              outlined
              v-model="addAdmissionCodeLocationFormData.location_name"
              label="Location Name"
            ></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.location_name.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-select
              outlined
              v-model="addAdmissionCodeLocationFormData.semester_id"
              :items="
                semesters.map(semester => ({
                  id: semester.id,
                  name: semester.semester_name + '(' + semester.session.session_name + ')',
                }))
              "
              item-value="id"
              item-text="name"
              label="Semester Name"
            ></v-select>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.semester_id.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-text-field
              outlined
              v-model="addAdmissionCodeLocationFormData.total_number"
              label="Total Number"
            ></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.total_number.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
            <v-text-field outlined v-model="addAdmissionCodeLocationFormData.price" label="Price"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.value.price.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >

            <!-- New code -->
            <h3 class="mb-2 text-center" style="font-size: 20px">User Info</h3>

            <v-text-field outlined v-model="addAdmissionCodeLocationFormData.username" label="Username"></v-text-field>
            <v-text-field outlined v-model="addAdmissionCodeLocationFormData.email" label="Email"></v-text-field>
            <v-text-field outlined v-model="addAdmissionCodeLocationFormData.password" label="Password"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddAdmissionCodeLocationForm">Add</v-btn>
          <v-btn color="secondary" @click="addAdmissionCodeLocationDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Increase AdmissionCodes to a location dialog -->
    <v-dialog v-model="inceaseAdmissionCodeToLocationDialog" max-width="500px">
      <v-card>
        <v-card-title>Increase Admission Codes</v-card-title>
        <v-card-text>
          <v-form ref="IncreaseAdmissionCodeLocationForm">
            <v-text-field
              outlined
              v-model="increaseAdmissionCodesToLocationFormData.number_to_add"
              label="Number of Codes to Add"
            ></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitIncreaseAdmissionCodesInLocationForm">Add</v-btn>
          <v-btn color="secondary" @click="inceaseAdmissionCodeToLocationDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- Add AdmissionCode dialog -->
    <v-dialog v-model="showAdmissionCodesPopup" persistent max-width="600px">
      <template v-slot:activator="{ on }"></template>
      <v-card>
        <v-card-title>
          <p>
            Admission Codes for <span style="font-weight: bold">{{ location }}</span>
          </p>
          <v-spacer></v-spacer>
          <v-btn color="purple darken-2" @click="exportCodesToExcel" small class="white--text">Export Codes</v-btn>
          <fas
            style="
              margin-left: 30px;
              font-size: 24px;
              cursor: pointer;
              display: inline-block;
              background-color: #3498db;
              color: #fff;
              border-radius: 50%;
              text-align: center;
              line-height: 1.5;
              width: 36px;
              height: 36px;
            "
            icon="times"
            @click="
              canCloseAdminssionCodesPopup = true
              closeAdmissionCodesPopup()
            "
          ></fas>
        </v-card-title>
        <v-card-text>
          <!-- <v-data-table :headers="admissionCodesHeaders" :items="items"></v-data-table> -->
          <v-data-table :headers="admissionCodesHeaders" :items="items">
            <template v-slot:item.admission_code="{ item }">
              {{ item.admission_code }}
            </template>
            <template v-slot:item.is_sold="{ item }">
              <fas
                v-if="item.is_sold == 0"
                icon="check"
                style="
                  font-size: 24px;
                  cursor: pointer;
                  background-color: lightgreen;
                  color: #fff;
                  border-radius: 50%;
                  text-align: center;
                  line-height: 1.5;
                  width: 36px;
                  height: 36px;
                "
                @click="handleSold(item)"
              ></fas>
              <fas
                v-else
                icon="times"
                disabled
                style="
                  font-size: 24px;
                  background-color: red;
                  color: #fff;
                  border-radius: 50%;
                  text-align: center;
                  line-height: 1.5;
                  width: 36px;
                  height: 36px;
                "
              ></fas>
            </template>
          </v-data-table>
        </v-card-text>
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
  name: 'AdmissionCodesLocations',
  props: {},
  components: {},
  data() {
    return {
      userRole: '',
      location: '',
      admissionCodeLocationItem: null,

      //////////// admission codes ////////
      isLoading: false,
      semesters: [],
      showAdmissionCodesPopup: false,
      admissionCodesHeaders: [],
      items: [],
      canCloseAdminssionCodesPopup: false,

      admissionCodeLocations: [],
      headers: [
        { text: 'Issued To', value: 'location_name' },
        { text: 'Semester', value: 'semester.semester_name' },
        { text: 'Total Admission Codes', value: 'total_number' },
        { text: 'Total Sold', value: 'total_sold' },
        { text: 'Total Remains', value: 'total_remains' },

        { text: 'Action', value: 'action', sortable: false },
      ],

      /////////////// increase admission codes   ///
      inceaseAdmissionCodeToLocationDialog: false,

      increaseAdmissionCodesToLocationFormData: {
        number_to_add: '',
      },

      //////////// admission codes ends ////////

      page: 1,
      pageCount: 0,
      search: '',

      addDialog: false,

      //////////////// add new AdmissionCodeLocation /////////
      addAdmissionCodeLocationDialog: false,

      addAdmissionCodeLocationFormData: {
        location_name: '',
        semester_id: '',
        total_number: '',
        price: '',
        username: '',
        email: '',
        password: '',
      },

      rules: {
        location_name: { required, minLength: minLength(2) },
        semester_id: { required },
        total_number: { required, minLength: minLength(1) },
        price: { required, minLength: minLength(2) },
      },

      v$: null,
    }
  },

  created() {
    this.getResults()
    this.setupValidation()
  },

  watch: {
    getUserProfile: function () {
      this.userRole = this.getUserProfile[0].role_id
      console.log('user info ', this.userRole)
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  computed: {
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },

  methods: {
    openAdmissionCodesPopup(admissionCodes) {
      console.log(admissionCodes)
      this.location = admissionCodes.location_name
      ;(this.admissionCodesHeaders = [
        { text: 'Admission Code', value: 'admission_code' },
        { text: 'Status', value: 'is_sold' },
      ]),
        (this.items = admissionCodes.admission_codes),
        (this.showAdmissionCodesPopup = true)
      this.canCloseAdminssionCodesPopup = false
    },
    closeAdmissionCodesPopup() {
      if (this.canCloseAdminssionCodesPopup) {
        this.showAdmissionCodesPopup = false
      }
    },

    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.addAdmissionCodeLocationFormData)
    },
    getResults() {
      axios
        .get('/api/view-admission_codes_locations?page=' + this.page)
        .then(response => {
          this.admissionCodeLocations = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.admissionCodeLocations = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-semesters?page=' + this.page)
        .then(response => {
          this.semesters = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.semesters = []
          this.pageCount = 0
        })
    },

    // showAdmissionCodes(id, admissionCodes) {
    //   // this.$router.push('/view-admission-codes/' + id)
    //   this.$router.push({
    //     name: 'view-admission-codes',
    //     params: {
    //       id: id,
    //     },
    //   })
    // },
    showAdmissionCodes(id, admissionCodes) {
      this.$router.push({
        name: 'view-admission-codes',
        params: {
          id,
          admissionCodes,
        },
      })
    },

    exportCodesToExcel() {
      const excludedProperties = ['id', 'created_at', 'updated_at', 'admission_code_location_id']
      const data = this.items.map(item => {
        const newItem = {}
        for (const [key, value] of Object.entries(item)) {
          if (!excludedProperties.includes(key)) {
            newItem[key] = value
          }
        }
        return newItem
      })
      const worksheet = XLSX.utils.json_to_sheet(data)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Admission Codes')
      XLSX.writeFile(workbook, 'admissionCodeLocations.xlsx')
    },

    deleteAdmissionCodeLocation(item) {
      // perform delete action on item
      console.log(`Deleting admissionCodeLocations ${item.id}`)
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
            axios.delete(`/api/delete-admission_codes_location/${item.id}`).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Admission Code Location deleted successfully.',
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
      this.addAdmissionCodeLocationDialog = true
    },

    cancelAdd() {
      // Clear the new admissionCodeLocations name and hide the add dialog
      this.newDepartmentName = ''
      this.addDialog = false
    },

    async submitaddAdmissionCodeLocationForm() {
      const result = await this.v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-admission_codes_location', this.addAdmissionCodeLocationFormData)
          .then(result => {
            // show success alert
            this.addAdmissionCodeLocationDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'Admission Codes location added successfully.',
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
              text: 'Failed to add admissionCodeLocations.',
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to add admissionCodeLocations.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    },

    handleSold(item) {
      console.log('item', item)
      this.showAdmissionCodesPopup = false
      swal
        .fire({
          title: 'Are you sure you want to sell this code?',
          icon: 'question',
          showCancelButton: true,
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        })
        .then(result => {
          if (result.isConfirmed) {
            swal
              .fire({
                title: 'What would you like to do next?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Send',
                cancelButtonText: 'Done',
                cancelButtonColor: '#aaa',
              })
              .then(result => {
                this.showAdmissionCodesPopup = false

                if (result.isConfirmed) {
                  swal
                    .fire({
                      title: 'Please enter your email address',
                      input: 'email',
                      confirmButtonText: 'Send',
                      showCancelButton: true,
                      cancelButtonText: 'Cancel',
                      cancelButtonColor: '#aaa',
                    })
                    .then(result => {
                      if (result.isConfirmed) {
                        // console.log(`Email: ${result.value}`)
                        this.isLoading = true
                        axios
                          .post('/api/send-email', { email: result.value, admission_code: item.admission_code })
                          .then(result => {
                            this.isLoading = false

                            // show success alert
                            this.addDepartmentDialog = false
                            axios
                              .put(`/api/sell-code/${item.id}`)
                              .then(response => {
                                // show success alert
                                this.editCourseDialog = false
                                swal
                                  .fire({
                                    title: 'Success!',
                                    text: 'Code sold successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK',
                                  })
                                  .then(() => {
                                    // this.showAdmissionCodesPopup = true
                                    this.getResults()
                                  })
                              })
                              .catch(error => {
                                // show error alert
                                swal.fire({
                                  title: 'Error!',
                                  text: 'Failed to update course.',
                                  icon: 'error',
                                  confirmButtonText: 'OK',
                                })
                              })
                          })
                          .catch(error => {
                            this.isLoading = false
                            // show error alert
                            swal.fire({
                              title: 'Error!',
                              text: error.response.data.error,
                              icon: 'error',
                              confirmButtonText: 'OK',
                            })
                          })
                      } else if (result.dismiss === swal.DismissReason.cancel) {
                        console.log('Cancelled')
                      }
                    })
                } else if (result.dismiss === swal.DismissReason.cancel) {
                  // this is when done is selected
                  axios
                    .put(`/api/sell-code/${item.id}`)
                    .then(response => {
                      // show success alert
                      this.editCourseDialog = false
                      swal
                        .fire({
                          title: 'Success!',
                          text: 'Code sold successfully.',
                          icon: 'success',
                          confirmButtonText: 'OK',
                        })
                        .then(() => {
                          // this.showAdmissionCodesPopup = true
                          this.getResults()
                        })
                    })
                    .catch(error => {
                      // show error alert
                      swal.fire({
                        title: 'Error!',
                        text: 'Failed to update course.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                      })
                    })
                }
              })
              .finally(() => {
                // Set isLoading to false when the request completes
                this.isLoading = false
              })
          } else if (result.dismiss === swal.DismissReason.cancel) {
            console.log('Cancelled')
          }
        })
    },

    addAdmissionCode(item) {
      console.log('item', item)
      this.inceaseAdmissionCodeToLocationDialog = true
      this.admissionCodeLocationItem = item // set the instance variable to the item value
    },

    submitIncreaseAdmissionCodesInLocationForm() {
      console.log('here ' + this.admissionCodeLocationItem.id)
      axios
        .post('/api/add-admission_codes_to_location', {
          admission_code_location_id: this.admissionCodeLocationItem.id,
          price: this.admissionCodeLocationItem.price,
          total_number: this.increaseAdmissionCodesToLocationFormData.number_to_add,
        })
        .then(result => {
          // show success alert
          this.inceaseAdmissionCodeToLocationDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Admission Codes Increased successfully.',
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
            text: 'Failed to add admissionCodeLocations.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },
  },

  // computed: {
  //   filteredAdmissionCodesLocations() {
  //     return this.admissionCodeLocations.filter(admissionCodeLocations => {
  //       return admissionCodeLocations.name.toLowerCase().includes(this.search.toLowerCase())
  //     })
  //   },
  // },
}
</script>

<style scoped>
.spinner {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  width: 40px;
  height: 40px;
  z-index: 9999;
}

.double-bounce1,
.double-bounce2 {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #333;
  opacity: 0.6;
  position: absolute;
  top: 0;
  left: 0;
  animation: sk-bounce 2s infinite ease-in-out;
}

.double-bounce2 {
  animation-delay: -1s;
}

@keyframes sk-bounce {
  0%,
  100% {
    transform: scale(0);
  }
  50% {
    transform: scale(1);
  }
}
</style>











