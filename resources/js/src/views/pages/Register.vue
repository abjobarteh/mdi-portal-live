<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->

        <!-- title -->
        <v-card-text class="d-flex flex-column align-center justify-center">
          <v-img width="100" height="70" :src="require('@/assets/images/logos/mdi_logo_square.png').default"></v-img>

          <p class="text-h4 font-weight-semibold text-center mb-2">MDI PORTAL</p>
          <p class="text-h6 font-weight-semibold text-center mb-2">Welcome! Please Register</p>
        </v-card-text>

        <!-- loading spinner -->
        <div v-if="isLoading" class="spinner">
          <div class="double-bounce1"></div>
          <div class="double-bounce2"></div>
        </div>

        <!-- login form -->
        <v-card-text>
          <v-form>
            <v-text-field
              v-model="registrationFormData.firstname"
              outlined
              label="Firstname"
              placeholder="John"
              hide-details
              class="mb-3"
            ></v-text-field
            ><v-text-field
              v-model="registrationFormData.lastname"
              outlined
              label="Lastname"
              placeholder="Doe"
              hide-details
              class="mb-3"
            ></v-text-field>
            <v-text-field
              v-model="registrationFormData.username"
              outlined
              label="Username"
              placeholder="JohnDoe"
              hide-details
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="registrationFormData.email"
              outlined
              label="Email"
              placeholder="john@example.com"
              hide-details
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="registrationFormData.password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
            ></v-text-field>
            <v-btn :disabled="isLoading" @click="register" block color="primary" class="mt-6"> Sign Up </v-btn>
          </v-form>
        </v-card-text>

        <!-- create new account  -->
        <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
          <span class="me-2"> Already have an account? </span>
          <router-link :to="{ name: 'pages-login' }"> Sign in instead </router-link>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'

export default {
  data() {
    return {
      isLoading: false,
      isPasswordVisible: false,
      registrationFormData: {
        firstname: '',
        lastname: '',
        username: '',
        email: '',
        password: '',
      },
      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
    }
  },

  methods: {
    register() {
      if (true) {
        this.isLoading = true
        axios
          .post('/api/register', this.registrationFormData)
          .then(result => {
            this.isLoading = false

            // show success alert
            this.addUserDialog = false
            swal
              .fire({
                title: 'Success!',
                text: 'User added successfully. Go Login',
                icon: 'success',
                confirmButtonText: 'OK',
              })
              .then(() => {
                this.$router.push('/login')
              })
          })
          .catch(error => {
            // show error alert
            this.isLoading = false

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
          text: 'Failed to add user.',
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
.auth-wrapper {
  /* Background Image */
  // background-image: url('../../assets/images/misc/mdi-image.png');
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../assets/images/misc/mdi-image.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

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
