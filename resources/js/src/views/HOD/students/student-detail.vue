<template>
  <div>
    <div>
      <v-container class="d-flex justify-center align-center" fill-height style="background-color: #eae4f0">
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
    </div>
  </div>
</template>



<!-- admission-status -->
<script>
import 'vuetify/dist/vuetify.min.css'
import AccountSettings from './student-details/AccountSettings.vue'

export default {
  name: 'Home',
  components: {
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
  },
}
</script>
