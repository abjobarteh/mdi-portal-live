<template>
  <v-container fluid>
    <v-row align="center" justify="center" style="height: 50vh">
      <v-col cols="12" md="6">
        <v-card class="elevation-12">
          <v-card-title class="headline text-center">Reset Password</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="resetPassword">
              <v-text-field
                v-model="formData.password"
                label="Password"
                type="password"
                required
                outlined
              ></v-text-field>

              <v-text-field
                v-model="formData.confirmPassword"
                label="Confirm Password"
                type="password"
                required
                outlined
              ></v-text-field>

              <v-btn type="submit" color="primary" block>Reset Password</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      formData: {
        password: '',
        confirmPassword: '',
      },
    }
  },
  methods: {
    resetPassword() {
      //
      axios
        .post('/api/reset-password', this.formData)
        .then(result => {
          // this.addPaymentDialog = false
          // show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'Password reset successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.$router.push('/view-admission-codes-locations')
              this.getResults()
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
  },
}
</script>

<style scoped>
/* Add any custom styles here */
</style>
