<template>
  <v-form ref="form" @submit.prevent="submit">
    <v-container style="background-color: #fefcff">
      <v-card class="pa-6">
        <v-card-title>
          <span class="headline font-weight-medium">PERSONAL INFORMATION</span>
        </v-card-title>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.middlename"
              label="Middle Name *"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.gender"
              :items="applicantPersonalInfoData.genderOptions"
              label="Gender *"
              required
            ></v-select>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.marital_status"
              :items="applicantPersonalInfoData.maritalStatusOptions"
              label="Marital Status *"
              required
            ></v-select>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.dob"
              label="Date of Birth *"
              type="date"
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.nationality"
              label="Nationality *"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.address"
              label="Address *"
              required
            ></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.phonenumber"
              label="Phone Number *"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.employment_status"
              :items="applicantPersonalInfoData.employmentStatusOptions"
              label="Employment Status *"
              required
            ></v-select>
          </v-col>
        </v-row>
        <v-card-actions class="d-flex justify-center">
          <v-btn color="primary" class="col-12" @click="submitForm()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </v-form>
</template>

  <script>
export default {
  data() {
    return {
      studentInfo: '',
      applicantPersonalInfoData: {
        middlename: '',
        phonenumber: '',
        gender: '',
        genderOptions: ['Male', 'Female', 'Other'],
        marital_status: '',
        maritalStatusOptions: ['Single', 'Married', 'Divorced', 'Widowed'],
        dob: '',
        nationality: '',
        address: '',
        employment_status: '',
        employmentStatusOptions: ['Employed', 'Unemployed', 'Self-employed'],
      },
    }
  },
  methods: {
    submitForm() {
      swal
        .fire({
          title: 'Are you sure, you want to submit personal Info?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.post(`/api/submit-applicant-personal-info`, this.applicantPersonalInfoData).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'application submitted successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.$store.dispatch('userProfile')
                  this.$router.push('/student')
                })
            })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      this.education = this.studentInfo.education
      this.certificates = this.studentInfo.certificates
      this.applicantPersonalInfoData['id'] = this.studentInfo.user_id
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