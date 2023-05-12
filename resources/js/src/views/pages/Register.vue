<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->

        <!-- title -->
        <v-card-text class="d-flex flex-column align-center justify-center">
          <p class="text-h4 font-weight-semibold text-center mb-2">MDI PORTAL</p>
          <p class="text-h6 font-weight-semibold text-center mb-2">Welcome! Please Register</p>
        </v-card-text>

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

            <v-checkbox hide-details class="mt-1">
              <template #label>
                <div class="d-flex align-center flex-wrap">
                  <span class="me-2">I agree to</span><a href="javascript:void(0)">privacy policy &amp; terms</a>
                </div>
              </template>
            </v-checkbox>

            <v-btn @click="register" block color="primary" class="mt-6"> Sign Up </v-btn>
          </v-form>
        </v-card-text>

        <!-- create new account  -->
        <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
          <span class="me-2"> Already have an account? </span>
          <router-link :to="{ name: 'pages-login' }"> Sign in instead </router-link>
        </v-card-text>
      </v-card>
    </div>

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="190"
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

<script>
// eslint-disable-next-line object-curly-newline
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import { ref } from '@vue/composition-api'

export default {
  data() {
    return {
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
        axios
          .post('/api/register', this.registrationFormData)
          .then(result => {
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
            swal.fire({
              title: 'Error!',
              text: 'Failed to add user.',
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
</style>
