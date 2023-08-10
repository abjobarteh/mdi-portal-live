<template>
  <v-card style="background-color: #fcfbfe">
    <v-container class="my-5">
      <v-col cols="12" md="12" class="mb-4">
        <UserInfoCard :studentProfile="applicantProfile" />
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <education-card :education="education"></education-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <certificate-card :certificates="certificates"></certificate-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <DepartmentCard :program="program" />
      </v-col>
      <v-btn @click="submitApplication" color="success lighten-2" block>Confirm</v-btn>
    </v-container>
  </v-card>
</template>

<script>
import EducationCard from '../../components/student/DeclarationAndPreview/EducationCard.vue'
import CertificateCard from '../../components/student/DeclarationAndPreview/CertificateCard.vue'
import DepartmentCard from '../../components/student/DeclarationAndPreview/Department.vue'
import UserInfoCard from '../../components/student/DeclarationAndPreview/UserInfoCard.vue'
import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    EducationCard,
    CertificateCard,
    DepartmentCard,
    UserInfoCard,
  },
  data() {
    return {
      studentInfo: '',
      education: [],
      certificates: [],
      program: '',
      applicantProfile: [],
    }
  },
  methods: {
    submitApplication() {
      swal
        .fire({
          title: 'Are you sure, you want to submit the application?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.post(`/api/submit-applicantion`).then(result => {
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
      this.program = this.studentInfo.program_name // this is actually the department name
      this.applicantProfile = [
        {
          firstname: this.studentInfo.firstname,
          lastname: this.studentInfo.lastname,
          address: this.studentInfo.address,
          phonenumber: this.studentInfo.phonenumber,
          gender: this.studentInfo.gender,
          nationality: this.studentInfo.nationality,
          employment_status: this.studentInfo.employment_status,
          email: this.studentInfo.email,
          dob: this.studentInfo.dob,
        },
      ]
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
