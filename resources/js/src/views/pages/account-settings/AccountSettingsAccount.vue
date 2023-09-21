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
            <v-btn color="secondary" outlined class="mt-4" type="reset" @click.prevent="resetForm"> Cancel </v-btn>
          </v-col>
        </v-row>
      </v-form>
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
      default: () => {},
    },
  },

  data() {
    return {
      user: '',
      status: ['Active', 'Inactive', 'Pending', 'Closed'],
      accountDataLocale: JSON.parse(JSON.stringify(this.accountData)),
      icons: {
        mdiAlertOutline,
        mdiCloudUploadOutline,
      },
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
        .put(`/api/update-user/${this.accountDataLocale.id}`, this.accountDataLocale)
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
            text: 'Failed to update user.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
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
