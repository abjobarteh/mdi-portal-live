<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->
        <v-card-title class="d-flex align-center justify-center py-7">
          <router-link to="/" class="d-flex align-center">
            <v-img
              :src="require('@/assets/images/logos/mdi_logo_square.png').default"
              max-height="70px"
              max-width="70px"
              alt="logo"
              contain
              class="me-3"
            ></v-img>

            <h2 class="text-2xl font-weight-semibold">Materio</h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary mb-2">
            Welcome to <span style="color: green">MDI</span> Portal! 👋🏻
          </p>
          <p class="mb-2">Please sign-in to your account and start the adventure</p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form @submit.prevent="login">
            <v-text-field
              v-model="auth.email"
              outlined
              label="Email"
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

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="173"
      :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default"
    />

    <!-- tree -->
    <v-img class="auth-tree" width="247" height="185" :src="require('@/assets/images/misc/tree.png').default"></v-img>

    <!-- tree  -->
    <v-img
      class="auth-tree-3"
      width="377"
      height="289"
      :src="require('@/assets/images/misc/tree-3.png').default"
    ></v-img>
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
        console.log(error.response.data.errors)
        this.error = true
        this.errorMessage = error.response.data.errors
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/sass/preset/pages/auth.scss';
</style>
