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
          <p class="text-h6 font-weight-semibold text-center mb-2">Welcome! Please Sign in</p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form @submit.prevent="login">
            <v-text-field
              v-model="auth.email"
              outlined
              label="Email/Username"
              placeholder="john@example.com"
              hide-details
              class="mb-3"
            ></v-text-field>

            <v-text-field
              v-model="auth.password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              hide-details
              @click:append="isPasswordVisible = !isPasswordVisible"
            ></v-text-field>

            <div class="d-flex align-center justify-space-between flex-wrap">
              <v-checkbox label="Remember Me" hide-details class="me-3 mt-1"> </v-checkbox>

              <!-- forgot link -->
              <a href="javascript:void(0)" class="mt-1"> Forgot Password? </a>
            </div>

            <v-btn block color="primary" type="submit" class="mt-6"> Login </v-btn>
          </v-form>
          <span v-if="error" style="color: red; margin-left: 100px; font-weight: bold">{{ errorMessage }}</span>
        </v-card-text>

        <!-- create new account  -->
        <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
          <span class="me-2"> New on our platform? </span>
          <router-link :to="{ name: 'pages-register' }"> Create an account </router-link>
        </v-card-text>
      </v-card>
    </div>
  </div>
</template>

<!-- <script>
// eslint-disable-next-line object-curly-newline
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'

export default {
  setup() {
    const isPasswordVisible = ref(false)
    const email = ref('')
    const password = ref('')
    const device_name = ref('browser')

    async function handleLogin() {
      // axios.get('/sanctum/csrf-cookie')
      try {
        await axios.get('/sanctum/csrf-cookie')
        await axios.post('api/login', { email: email.value, password: password.value, device_name: 'browser' })
      } catch (error) {
        console.log(error)
      }
    }

    return {
      isPasswordVisible,
      email,
      password,
      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
      handleLogin,
    }
  },
}
</script> -->
<script>
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { mapActions } from 'vuex'
import axios from 'axios'

export default {
  name: 'Login',
  data() {
    return {
      error: false,
      errorMessage: '',
      auth: {
        email: '',
        password: '',
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
    async login() {
      console.log('Logging in user...')
      try {
        await this.$store.dispatch('login', {
          email: this.auth.email,
          password: this.auth.password,
          device_name: 'browser',
        })
        await this.$store.dispatch('fetchUser')
      } catch (error) {
        // console.log(error.response.data.errors)
        this.error = true
        // this.errorMessage = error.response.data.errors
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
  // background-image: url('../../assets/images/misc/mdi-image.png');
  background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('../../assets/images/misc/mdi-image.png');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
}

.login-container {
  background-color: rgb(45, 118, 182);
}
</style>
