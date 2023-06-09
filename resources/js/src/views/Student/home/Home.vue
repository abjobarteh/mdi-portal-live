<template>
  <div>
    <div v-if="studentInfo.student_email_verified_at == null">
      <v-card>
        <v-card-title class="text-center font-weight-bold">Enter Email Token to Continue</v-card-title>
        <v-card-text>
          <v-form @submit.prevent="submitVerifyRegistrationToken">
            <v-text-field
              outlined
              v-model="registarationVerificationFormData.registration_verification_token"
              label="Email Token"
              required
            ></v-text-field>
            <v-btn type="submit" color="green" block>Submit</v-btn>
          </v-form>
        </v-card-text>
      </v-card>
    </div>
    <div v-else>
      <v-container
        v-if="studentInfo.is_applicant == 1 && admission_status == 0"
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #eae4f0"
      >
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" lg="6">
            <div outlined class="text-center">
              <v-card-title class="headline text-center mb-5" style="margin-left: 22%; font-weight: bold">
                admission closed, try again later
              </v-card-title>
            </div>
          </v-col>
        </v-row>
      </v-container>
      <v-container
        v-else-if="studentInfo.is_applicant == 1 && studentInfo.verified_at == null && admission_status == 1"
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #eae4f0"
      >
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" lg="6">
            <div outlined class="text-center">
              <v-card-title class="headline text-center mb-5" style="margin-left: 22%; font-weight: bold">
                Congratulations for joining!
              </v-card-title>
              <v-card-text>
                <div class="text-body-1 text-lg mb-4">Enter your admission code to continue with the application.</div>
                <div class="text-body-1">
                  <v-btn @click="showAdmissionCodeDialog" class="text-primary" style="text-decoration: none"
                    >Enter code</v-btn
                  >
                </div>
              </v-card-text>
            </div>
          </v-col>
        </v-row>
      </v-container>
      <v-container
        v-else-if="
          studentInfo.is_applicant == 1 &&
          studentInfo.verified_at != null &&
          studentInfo.application_completed == 0 &&
          admission_status == 1
        "
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #fcfafe"
      >
        <new-application :progress="progress" :profile="profile"></new-application>
      </v-container>

      <v-container
        v-else-if="
          studentInfo.is_applicant == 1 &&
          studentInfo.verified_at != null &&
          studentInfo.application_completed == 1 && // completed by the user
          studentInfo.accepted == 'pending' // this is by the registrar
        "
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #eae4f0"
      >
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" lg="6">
            <div outlined class="text-center">
              <v-card-title class="headline text-center mb-5" style="margin-left: 22%; font-weight: bold">
                Pending wait
              </v-card-title>
            </div>
          </v-col>
        </v-row>
      </v-container>

      <v-container
        v-else-if="
          studentInfo.is_applicant == 1 &&
          studentInfo.verified_at != null &&
          studentInfo.application_completed == 1 && // completed by the user
          studentInfo.accepted == 'rejected' // this is by the registrar
        "
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #eae4f0"
      >
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" lg="6">
            <div outlined class="text-center">
              <v-card-title class="headline text-center mb-5" style="margin-left: 22%; font-weight: bold">
                Sorry Rejected
              </v-card-title>
            </div>
          </v-col>
        </v-row>
      </v-container>

      <v-container
        v-else-if="
          studentInfo.is_applicant == 0 &&
          studentInfo.verified_at != null &&
          studentInfo.application_completed == 1 && // completed by the user
          studentInfo.accepted == 'accepted' // this is by the registrar
        "
        class="d-flex justify-center align-center"
        fill-height
        style="background-color: #eae4f0"
      >
        <v-row align="center" justify="center">
          <v-col cols="12" md="8" lg="12">
            <div outlined class="text-center">
              <v-card-title class="headline text-center mb-5" style="font-weight: bold">
                <AccountSettings />
              </v-card-title>
            </div>
          </v-col>
        </v-row>
      </v-container>

      <v-dialog v-model="inputAdmissionCodeDialog" max-width="500px">
        <v-card>
          <v-card-title> Admission Code </v-card-title>
          <v-card-text>
            <v-form ref="form">
              <v-text-field
                v-model="inputAdmissionCodeFormData.admission_code"
                outlined
                label="Enter Admission Code Here"
              ></v-text-field>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" @click="submitInputAdmissionCode">Save</v-btn>
            <v-btn color="secondary" @click="inputAdmissionCodeDialog = false">Cancel</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>



<!-- admission-status -->
<script>
import 'vuetify/dist/vuetify.min.css'
import NewApplication from '../../../components/student/application.vue'
import AccountSettings from '../../Student/account-settings/AccountSettings'

export default {
  name: 'Home',
  components: {
    NewApplication,
    AccountSettings,
  },

  data() {
    return {
      inputAdmissionCodeDialog: false,
      studentInfo: '',
      admission_status: '',

      inputAdmissionCodeFormData: {
        admission_code: '',
      },

      registarationVerificationFormData: {
        registration_verification_token: '',
      },

      profile: {},
      progress: {
        academic: 'Completed',
        personal: 'Completed',
        academicInfo: 'Completed',
        documents: 'Completed',
        declaration: 'Completed',
      },
    }
  },
  created() {
    this.getResults()
  },
  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      console.log(this.studentInfo)
      this.profile = {
        ...this.studentInfo,
        phone: this.studentInfo.phonenumber,
        status: this.studentInfo.is_applicant == 1 ? 'Applicant' : 'Student',
      }
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
  methods: {
    getResults() {
      axios
        .get('/api/admission-status')
        .then(response => {
          // this.admission_status = response.data.admission_status
          this.admission_status = response.data.result[0].admission_status

          console.log('good', this.admission_status)
        })
        .catch(err => {
          this.admission_status = []
        })
    },

    showAdmissionCodeDialog() {
      this.inputAdmissionCodeDialog = true
    },

    submitInputAdmissionCode() {
      axios
        .post('/api/redeem-admission-code', this.inputAdmissionCodeFormData)
        .then(result => {
          this.inputAdmissionCodeDialog = false
          // show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'code redeemed successfully, continue',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getResults()
              this.$store.dispatch('userProfile')
            })
        })
        .catch(error => {
          if (error.response.status === 409) {
            // console.log('Code already taken')
            swal.fire({
              title: 'Error!',
              text: error.response.data.message,
              icon: 'error',
              confirmButtonText: 'OK',
            })
          } else {
            swal.fire({
              title: 'Error!',
              text: error.response.data.message,
              icon: 'error',
              confirmButtonText: 'OK',
            })
          }
          // show error alert
        })
      // console.log(this.inputAdmissionCodeFormData.admission_code)
    },

    submitVerifyRegistrationToken() {
      axios
        .post('/api/verify-registration-token', this.registarationVerificationFormData)
        .then(result => {
          // show success alert
          swal
            .fire({
              title: 'Success!',
              text: 'registration token redeemed successfully, continue',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getResults()
              this.$store.dispatch('userProfile')
            })
        })
        .catch(error => {
          swal.fire({
            title: 'Error!',
            text: error.response.data.message,
            icon: 'error',
            confirmButtonText: 'OK',
          })
          // show error alert
        })
    },
  },
}
</script>