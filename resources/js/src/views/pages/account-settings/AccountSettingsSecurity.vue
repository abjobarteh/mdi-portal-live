<template>
  <v-card flat class="mt-5">
    <v-form @submit.prevent="submitForm">
      <div class="px-3">
        <v-card-text class="pt-5">
          <v-row>
            <v-col cols="12" sm="8" md="6">
              <!-- current password -->
              <v-text-field
                v-model="currentPassword"
                :type="isCurrentPasswordVisible ? 'text' : 'password'"
                :append-icon="isCurrentPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="Current Password"
                outlined
                dense
                @click:append="isCurrentPasswordVisible = !isCurrentPasswordVisible"
              ></v-text-field>

              <!-- new password -->
              <v-text-field
                v-model="newPassword"
                :type="isNewPasswordVisible ? 'text' : 'password'"
                :append-icon="isNewPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="New Password"
                outlined
                dense
                hint="Make sure it's at least 8 characters."
                persistent-hint
                @click:append="isNewPasswordVisible = !isNewPasswordVisible"
              ></v-text-field>

              <!-- confirm password -->
              <v-text-field
                v-model="cPassword"
                :type="isCPasswordVisible ? 'text' : 'password'"
                :append-icon="isCPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="Confirm New Password"
                outlined
                dense
                class="mt-3"
                @click:append="isCPasswordVisible = !isCPasswordVisible"
              ></v-text-field>
            </v-col>
            <v-col cols="12" sm="4" md="6" class="d-none d-sm-flex justify-center position-relative">
              <!-- <v-img
                contain
                max-width="170"
                :src="require('@/assets/images/3d-characters/pose-m-1.png').default"
                class="security-character"
              ></v-img> -->
              <v-img max-width="170" :src="getImageUrl(user.picture)"></v-img>
            </v-col>
          </v-row>
        </v-card-text>
      </div>

      <!-- divider -->
      <v-divider></v-divider>

      <div class="pa-3">
        <!-- action buttons -->
        <v-card-text>
          <v-btn type="submit" color="primary" class="me-3 mt-3"> Save changes </v-btn>
          <v-btn color="secondary" outlined class="mt-3"> Cancel </v-btn>
        </v-card-text>
      </div>
    </v-form>
  </v-card>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiKeyOutline, mdiLockOpenOutline, mdiEyeOffOutline, mdiEyeOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'
import apiBaseURL from '../../../../api-config'

export default {
  setup() {
    const user = ref('')
    const isCurrentPasswordVisible = ref(false)
    const isNewPasswordVisible = ref(false)
    const isCPasswordVisible = ref(false)
    const currentPassword = ref('')
    const newPassword = ref('')
    const cPassword = ref('')

    const getImageUrl = filename => {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + 'images/avatars/' + filename
    }

    const submitForm = () => {
      if (!newPassword.value.length >= 8) {
        swal.fire({
          title: 'Error!',
          text: 'Password should atleast be 8 characters long',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      } else if (newPassword.value != cPassword.value) {
        swal.fire({
          title: 'Error!',
          text: 'Passwords should be the same',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      } else {
        axios
          .put(`/api/update-password/1`, { oldPassword: currentPassword.value, newPassword: newPassword.value })
          .then(result => {
            // show success alert
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
      }
    }

    return {
      isCurrentPasswordVisible,
      isNewPasswordVisible,
      currentPassword,
      isCPasswordVisible,
      newPassword,
      cPassword,
      user,
      getImageUrl,
      submitForm,
      icons: {
        mdiKeyOutline,
        mdiLockOpenOutline,
        mdiEyeOffOutline,
        mdiEyeOutline,
      },
    }
  },

  watch: {
    getUserProfile: function () {
      this.user = this.getUserProfile
      console.log('users ', this.user)
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

<style lang="scss" scoped>
.two-factor-auth {
  max-width: 25rem;
}
.security-character {
  position: absolute;
  bottom: -0.5rem;
}
</style>
