<template>
  <v-card flat class="pa-3 mt-2">
    <v-card-text class="d-flex">
      <v-avatar rounded size="120" class="me-6">
        <v-img :src="getImageUrl(user.picture)"></v-img>
      </v-avatar>
      <!-- {{ currentUser }} -->
      <!-- upload photo -->
      <div>
        <!-- <v-btn color="primary" class="me-3 mt-5">
          <v-icon class="d-sm-none">
            {{ icons.mdiCloudUploadOutline }}
          </v-icon>
          <span class="d-none d-sm-block">Upload new photo</span>
        </v-btn> -->

        <!-- <input ref="refInputEl" type="file" accept=".jpeg,.png,.jpg,GIF" :hidden="true" /> -->
        <div>
          <input type="file" ref="fileInput" style="display: none" @change="handleFileChange" />

          <v-btn color="primary" class="me-3 mt-5" @click="openFileInput">
            <v-icon class="d-sm-none">
              {{ icons.mdiCloudUploadOutline }}
            </v-icon>
            <span class="d-none d-sm-block">Upload new photo</span>
          </v-btn>
        </div>
        <p class="text-sm mt-5">Allowed JPG, GIF or PNG. Max size of 800K</p>
      </div>
    </v-card-text>

    <v-card-text>
      <v-form @submit.prevent="submitForm" class="multi-col-validation mt-6">
        <v-row>
          <v-col md="6" cols="12">
            <v-text-field v-model="accountDataLocale.firstname" label="Firstname" dense outlined></v-text-field>
          </v-col>

          <v-col md="6" cols="12">
            <v-text-field v-model="accountDataLocale.lastname" label="Lastname" dense outlined></v-text-field>
          </v-col>

          <v-col md="6" cols="12">
            <v-text-field v-model="accountDataLocale.username" label="Username" dense outlined></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field v-model="accountDataLocale.email" label="E-mail" dense outlined></v-text-field>
          </v-col>

          <!-- alert -->
          <!-- <v-col cols="12">
            <v-alert color="warning" text class="mb-0">
              <div class="d-flex align-start">
                <v-icon color="warning">
                  {{ icons.mdiAlertOutline }}
                </v-icon>

                <div class="ms-3">
                  <p class="text-base font-weight-medium mb-1">Your email is not confirmed. Please check your inbox.</p>
                  <a href="javascript:void(0)" class="text-decoration-none warning--text">
                    <span class="text-sm">Resend Confirmation</span>
                  </a>
                </div>
              </div>
            </v-alert>
          </v-col> -->

          <v-col cols="12">
            <v-btn type="submit" color="primary" class="me-3 mt-4"> Save changes </v-btn>
            <v-btn v-if="user.role_id === 4 || user.role_id === 1" color="primary" class="me-3 mt-4"
              @click="changematnumberdialog"> View Matriculation Number </v-btn>
            <v-btn color="secondary" outlined class="mt-4" type="reset" @click.prevent="resetForm"> Cancel </v-btn>
          </v-col>
        </v-row>
      </v-form>
      <v-dialog v-model="changematnumber" max-width="500px">
        <v-card>
          <v-card-title>Matriculation Number</v-card-title>
          <v-card-text>
            <v-form ref="addLocationForm">
              <v-text-field outlined v-model="changematnumberformData.mat_number"
                label="Matriculation Number"></v-text-field>
              <!--
            <span style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in addLocationV$.value.location_code.$errors" :key="error.$uid">{{ error.$message }}</span>
            -->
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="green" @click="updatematnumber">Update</v-btn>
            <v-btn color="secondary" @click="changematnumber = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-card-text>
  </v-card>
</template>

<script>
import { mdiAlertOutline, mdiCloudUploadOutline } from '@mdi/js'
import { ref, computed, onMounted } from '@vue/composition-api'
import store from '@/store'

import apiBaseURL from '../../../../api-config'

export default {
  props: {
    accountData: {
      type: Object,
      default: () => { },
    },
  },

  data() {
    return {
      user: '',
      changematnumber: false,
      status: ['Active', 'Inactive', 'Pending', 'Closed'],
      accountDataLocale: JSON.parse(JSON.stringify(this.accountData)),
      icons: {
        mdiAlertOutline,
        mdiCloudUploadOutline,
      },
      matnumber : '',
      matriculation: [],
      changematnumberformData: {
        mat_number: '',
      }
    }

  },
  methods: {
    getImageUrl(filename) {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + 'images/avatars/' + filename
    },
    resetForm() {
      this.accountDataLocale = JSON.parse(JSON.stringify(this.accountData))
    },
    submitForm() {
      axios
        .put(`/api/update-account-info/${this.accountDataLocale.id}`, this.accountDataLocale)
        .then(result => {
          // show success alert
          this.$store.dispatch('fetchUser')
          swal.fire({
            title: 'Success!',
            text: 'User updated successfully.',
            icon: 'success',
            confirmButtonText: 'OK',
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
    },
    changematnumberdialog() {
      // changematnumberformData.mat_number = accountDataLocale.mat_number
      this.changematnumber = true
     // console.log('Data: ', this.accountDataLocale.id);
      axios
        .get(`/api/get-matnumber/${this.accountDataLocale.id}`)
        
        .then(response => {
          // show success alert

          this.matriculation = response.data.result;

          console.log('Data: ', response.data.result[0].mat_number);
          this.changematnumberformData.mat_number = response.data.result[0].mat_number;

        })
        .catch(error => {

         console.log('Data 2: ', error);
          // Check if the error response exists and contains a message
         const errorMessage = error.response && error.response.data && error.response.data.message
            ? error.response.data.message
            : 'Error';

          // Show error alert with the appropriate message
          swal.fire({
            title: 'Error!',
            text: error,
            icon: 'error',
            confirmButtonText: 'OK',
          }); 
        });
    },

    updatematnumber(){
      console.log('Debug 1: ',this.changematnumberformData);
      console.log('Debug 2 :',this.changematnumberformData.mat_number);
      console.log('Debug 3 :',this.accountDataLocale.id);
      axios
        .post(`/api/update-matnumber/${this.accountDataLocale.id}`,this.changematnumberformData)
        
        .then(response => {
        
   swal
            .fire({
              title: 'Success!',
              text: 'Matriculation Number updated successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.changematnumber = false
            })
        })
        .catch(error => {
          // Check if the error response exists and contains a message
          const errorMessage = error.response && error.response.data && error.response.data.message
            ? error.response.data.message
            : 'Error';

          // Show error alert with the appropriate message
          swal.fire({
            title: 'Error!',
            text: errorMessage,
            icon: 'error',
            confirmButtonText: 'OK',
          });
        }).then(
          this.changematnumber = false
        )
    },
    openFileInput() {
      // Trigger the file input element
      this.$refs.fileInput.click()
    },
    async handleFileChange(event) {
      const selectedFile = event.target.files[0]

      if (selectedFile) {
        try {
          // Create a form data object to send the file to the server
          const formData = new FormData()
          formData.append('photo', selectedFile) // 'photo' should match your Laravel controller's input name

          // Upload the file to the server using Axios or a similar library
          const response = await axios.post('api/upload-photo', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            },
          })

          // Assuming the response contains the updated user data
          const updatedUserData = response.data

          // Update the user's photo in your component's data
          this.accountDataLocale.photo = updatedUserData.photo // Adjust the property name as needed

          // Show a success message or handle the response as necessary
          swal.fire({
            title: 'Success!',
            text: 'Photo uploaded successfully.',
            icon: 'success',
            confirmButtonText: 'OK',
          })
        } catch (error) {
          // Handle errors and show an error message
          swal.fire({
            title: 'Error!',
            text: 'Failed to upload photo.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        }
      }
    },
  },
  watch: {
    getUserProfile: function () {
      this.user = this.getUserProfile
      console.log('user pic', `${this.user.picture}`)
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
}
</script>
