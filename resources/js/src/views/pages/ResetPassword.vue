<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card" style="background-color: rgba(255, 255, 255, 0.983)">
        <!-- logo -->
        <!-- <v-card-text class="d-flex flex-column align-center justify-center">
          <p class="text-h4 font-weight-semibold text-center mb-2">MDI PORTAL</p>
          <p class="text-h6 font-weight-semibold text-center mb-2">Welcome! Please Sign in</p>
        </v-card-text> -->
        <v-card-text class="d-flex flex-column align-center justify-center">
          <!-- Add your logo here -->
          <!-- <img
            src="../../assets/images/logos/mdi_logo_square.png"
            alt="Your Logo"
            class="mb-3"
            style="max-height: 100px"
          /> -->
          <v-img width="170" height="120" :src="require('@/assets/images/logos/mdi_logo_square.png').default"></v-img>

          <p class="text-h4 font-weight-semibold text-center mb-2">MDI PORTAL</p>
          <p class="text-h6 font-weight-semibold text-center mb-2">Reset Your Password</p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form @submit.prevent="submit">
            <v-text-field
              v-model="auth.password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="New Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="auth.passwordConfirmation"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
            ></v-text-field>

            <v-btn block color="primary" type="submit" class="mt-6"> Submit </v-btn>
          </v-form>
          <span v-if="error" style="color: red; margin-left: 100px; font-weight: bold">{{ errorMessage }}</span>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script>
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { mapActions } from 'vuex'
import axios from 'axios'

export default {
  props: ['token', 'email'], // Define props for token and email

  name: 'ResetPassword',
  data() {
    return {
      error: false,
      errorMessage: '',
      auth: {
        password: '',
        passwordConfirmation: '',
      },
      validationErrors: {},
      processing: false,
      isPasswordVisible: false,
      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
    }
  },
  methods: {
    async submit() {
      if (this.auth.password == '' || this.auth.passwordConfirmation == '') {
        swal.fire({
          title: 'Error!',
          text: 'Please fill the Password and Password Confirmation',
          icon: 'error',
          confirmButtonText: 'OK',
        })
        return
      }
      try {
        const response = await axios.post('/api/reset-user-password', {
          email: this.email,
          token: this.token,
          password: this.auth.password,
          password_confirmation: this.auth.passwordConfirmation,
        })
        console.log(response.data.message) // Success message
        swal
          .fire({
            title: 'Success!',
            text: 'Password reset successful',
            icon: 'success',
            confirmButtonText: 'OK',
          })
          .then(() => {
            this.$router.push('/login')
          })
      } catch (error) {
        console.error('Error:', error.response.data.error) // Error message
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/sass/preset/pages/auth.scss';

// .auth-wrapper {
//   /* Replace 'path/to/background-image.jpg' with the path to your background image */
//   background-image: url('../../assets/images/misc/mdi-image.png');
//   background-size: cover;
//   background-repeat: no-repeat;
//   background-position: center;
// }
.auth-wrapper {
  /* Background Image */
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../assets/images/misc/mdi-image.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.login-container {
  background-color: rgb(45, 118, 182);
}
</style>
