<template>
  <v-card style="background-color: #fcfbfe">
    <v-container class="my-5">
      <v-col cols="12" md="12" class="mb-4">
        <education-card :education="education"></education-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <certificate-card :certificates="certificates"></certificate-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <DepartmentCard :department="department" />
      </v-col>
      <v-btn @click="submitApplication" color="success lighten-2" block>Confirm</v-btn>
    </v-container>
  </v-card>
</template>

<script>
// import EducationCard from '../../components/student/DeclarationAndPreview/EducationCard.vue'
import EducationCard from '../student/DeclarationAndPreview/EducationCard.vue'
import CertificateCard from '../student/DeclarationAndPreview/CertificateCard.vue'
import DepartmentCard from '../student/DeclarationAndPreview/DepartmentCard.vue'

import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    EducationCard,
    CertificateCard,
    DepartmentCard,
  },
  data() {
    return {
      education: [],
      certificates: [],
      department: '',
    }
  },

  created() {
    this.getResults()
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

    getResults() {
      axios
        .get('/api/view-incoming-applications?page=' + this.page)
        .then(response => {
          this.education = response.data.result.data[0].education
          this.certificates = response.data.result.data[0].certificates
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          console.log('applications')
          this.incomingApplications = []
          this.pageCount = 0
        })
    },
  },
}
</script>