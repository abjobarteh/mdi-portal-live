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

          <p class="text-h6 font-weight-semibold text-center mb-2">Enter Your Valid Email</p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form @submit.prevent="login">
            <input type="hidden" name="_token" :value="csrfToken" />
            <v-text-field
              v-model="auth.email"
              outlined
              label="Email"
              placeholder="john@example.com"
              hide-details
              class="mb-3"
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
// import axios from 'axios'

export default {
  name: 'ForgotPassword',
  mounted() {
    console.log('here')
    // this.fetchCsrfToken()
  },
  data() {
    return {
      csrfToken: '',
      error: false,
      errorMessage: '',
      auth: {
        email: '',
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
    async fetchCsrfToken() {
      console.log('here')
      try {
        const response = await axios.get('/api/csrf-token')
        this.csrfToken = response.data.csrf_token
        console.log('here ', this.csrfToken)
      } catch (error) {
        console.error('Error fetching CSRF token:', error)
      }
    },
    async forgotPassword() {
      // Form submission logic
    },
    async login() {
      if (this.auth.email == '') {
        swal.fire({
          title: 'Error!',
          text: 'Please fill the Email',
          icon: 'error',
          confirmButtonText: 'OK',
        })
        return
      }
      try {
        const response = await axios.post('/api/forgot-password', { email: this.auth.email })
        console.log(response.data.message) // Success message
        swal.fire({
          title: 'Success!',
          text: 'Password reset link sent to your email',
          icon: 'success',
          confirmButtonText: 'OK',
        })
      } catch (error) {
        console.error('Error:', error.response.data.error) // Error message
        swal.fire({
          title: 'Error!',
          text: error.response.data.error,
          icon: 'error',
          confirmButtonText: 'OK',
        })
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
