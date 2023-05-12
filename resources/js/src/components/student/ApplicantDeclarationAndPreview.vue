<template>
  <v-card style="background-color: #fcfbfe">
    <v-container class="my-5">
      <v-col cols="12" md="12" class="mb-4">
        <education-card :education="education"></education-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <certificate-card :certificates="certificates"></certificate-card>
      </v-col>
      <v-btn @click="submitApplication" color="success lighten-2" block>Confirm</v-btn>
    </v-container>
  </v-card>
</template>

<script>
import EducationCard from '../../components/student/DeclarationAndPreview/EducationCard.vue'
import CertificateCard from '../../components/student/DeclarationAndPreview/CertificateCard.vue'
import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    EducationCard,
    CertificateCard,
  },
  data() {
    return {
      studentInfo: '',
      education: [],
      certificates: [],
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