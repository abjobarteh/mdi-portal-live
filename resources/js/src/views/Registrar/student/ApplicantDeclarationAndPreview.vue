<template>
  <div>
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
        <v-row v-if="this.$route.query.param == 'incoming'">
          <v-col cols="4">
            <v-btn @click="rejectStudentApplication" color="red" block dark>Reject</v-btn>
          </v-col>
          <v-col cols="4">
            <v-btn @click="acceptStudentApplication" color="green" block dark>Accept</v-btn>
          </v-col>
          <v-col cols="4">
            <v-btn @click="conditionalStudentApplication" color="purple" block dark>Conditional</v-btn>
          </v-col>
        </v-row>
      </v-container>
    </v-card>
    <!-- loading spinner -->
    <div v-if="isLoading" class="spinner">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
  </div>
</template>

<script>
// import EducationCard from '../../components/student/DeclarationAndPreview/EducationCard.vue'
import EducationCard from '../student/DeclarationAndPreview/EducationCard.vue'
import CertificateCard from '../student/DeclarationAndPreview/CertificateCard.vue'
import DepartmentCard from '../student/DeclarationAndPreview/DepartmentCard.vue'
import UserInfoCard from '../student/DeclarationAndPreview/UserInfoCard.vue'

import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    UserInfoCard,
    EducationCard,
    CertificateCard,
    DepartmentCard,
  },
  data() {
    return {
      isLoading: false,
      education: [],
      certificates: [],
      program: '',
      applicantProfile: [],
    }
  },

  created() {
    console.log(this.$route.query.param)
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

    acceptStudentApplication() {
      // perform delete action on item
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Schedule Orientation',
        })
        .then(result => {
          if (result.isConfirmed) {
            // Show date selection popup
            swal
              .fire({
                title: 'Select Orientation Date',
                // html: '<input type="date" id="orientationDate">',
                html: '<input type="datetime-local" id="orientationDate">',

                showCancelButton: true,
                confirmButtonText: 'Schedule',
              })
              .then(dateResult => {
                if (dateResult.isConfirmed) {
                  this.isLoading = true
                  const orientationDate = document.getElementById('orientationDate').value

                  // Perform API request to schedule interview
                  axios
                    .post(`/api/accept-student-application`, {
                      userId: this.$route.params.id,
                      orientationDate: orientationDate,
                    })
                    .then(result => {
                      this.isLoading = false
                      swal
                        .fire({
                          title: 'Success!',
                          text: 'Interview scheduled successfully.',
                          icon: 'success',
                          confirmButtonText: 'OK',
                        })
                        .then(() => {
                          this.$router.push('/view-incoming-applications')
                        })
                    })
                }
              })
          }
        })
    },
    conditionalStudentApplication() {
      // perform delete action on item
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Schedule Orientation',
        })
        .then(result => {
          if (result.isConfirmed) {
            // Show date selection popup
            swal
              .fire({
                title: 'Select Orientation Date',
                // html: '<input type="date" id="orientationDate">',
                html: '<input type="datetime-local" id="orientationDate">',

                showCancelButton: true,
                confirmButtonText: 'Schedule',
              })
              .then(dateResult => {
                if (dateResult.isConfirmed) {
                  this.isLoading = true
                  const orientationDate = document.getElementById('orientationDate').value

                  // Perform API request to schedule interview
                  axios
                    .post(`/api/conditional-student-application`, {
                      userId: this.$route.params.id,
                      orientationDate: orientationDate,
                    })
                    .then(result => {
                      this.isLoading = false
                      swal
                        .fire({
                          title: 'Success!',
                          text: 'Interview scheduled successfully.',
                          icon: 'success',
                          confirmButtonText: 'OK',
                        })
                        .then(() => {
                          this.$router.push('/view-incoming-applications')
                        })
                    })
                }
              })
          }
        })
    },
    rejectStudentApplication() {
      // perform delete action on item
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, reject it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            this.isLoading = true

            axios.post(`/api/reject-student-application `, { userId: this.$route.params.id }).then(result => {
              swal
                .fire({
                  title: 'Success!',
                  text: 'Applicant rejected successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.isLoading = false

                  this.$router.push('/view-incoming-applications')
                })
            })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },

    mounted() {
      console.log('query', this.$route.query)
    },

    getResults() {
      if (this.$route.query.param == 'accepted') {
        axios
          .post(`/api/view-accepted-application-detail?page=` + this.page, { userId: this.$route.params.id })
          .then(response => {
            console.log('anme', response.data.result.data[0].firstname)
            this.education = response.data.result.data[0].education
            this.certificates = response.data.result.data[0].certificates
            this.program = response.data.result.data[0].program_name

            this.pageCount = response.data.result.last_page

            this.applicantProfile = [
              {
                firstname: response.data.result.data[0].firstname,
                middlename: response.data.result.data[0].middlename,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
                profile_image: response.data.result.data[0].profile_image,
                eme_name: response.data.result.data[0].eme_name,
                eme_numbr: response.data.result.data[0].eme_numbr
              },
            ]
          })
          .catch(err => {
            console.log('applications')
            this.incomingApplications = []
            this.pageCount = 0
          })
      } else if (this.$route.query.param == 'incoming') {
        axios
          .post(`/api/view-incoming-applications?page=` + this.page, { userId: this.$route.params.id })
          .then(response => {
            console.log(response.data.result.data)
            this.education = response.data.result.data[0].education
            this.certificates = response.data.result.data[0].certificates
            this.program = response.data.result.data[0].program_name
            this.pageCount = response.data.result.last_page
            this.applicantProfile = [
              {
                id: response.data.result.data[0].studentId,
                firstname: response.data.result.data[0].firstname,
                middlename: response.data.result.data[0].middlename,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
                profile_image: response.data.result.data[0].profile_image,
                eme_name: response.data.result.data[0].eme_name,
                eme_numbr: response.data.result.data[0].eme_numbr
              },
            ]
          })
          .catch(err => {
            console.log('applications')
            this.incomingApplications = []
            this.pageCount = 0
          })
      } else if (this.$route.query.param == 'rejected') {
        axios
          .post(`/api/view-rejected-applications?page=` + this.page, { userId: this.$route.params.id })
          .then(response => {
            console.log(response.data.result.data)
            this.education = response.data.result.data[0].education
            this.certificates = response.data.result.data[0].certificates
            this.program = response.data.result.data[0].program_name
            this.pageCount = response.data.result.last_page
            this.applicantProfile = [
              {
                firstname: response.data.result.data[0].firstname,
                middlename: response.data.result.data[0].middlename,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
                profile_image: response.data.result.data[0].profile_image,
                eme_name: response.data.result.data[0].eme_name,
                eme_numbr: response.data.result.data[0].eme_numbr
              },
            ]
          })
          .catch(err => {
            console.log('applications')
            this.incomingApplications = []
            this.pageCount = 0
          })
      }
    },
  },
}
</script>

<style lang="scss">
.spinner {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  width: 40px;
  height: 40px;
  z-index: 9999;
}

.double-bounce1,
.double-bounce2 {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #333;
  opacity: 0.6;
  position: absolute;
  top: 0;
  left: 0;
  animation: sk-bounce 2s infinite ease-in-out;
}

.double-bounce2 {
  animation-delay: -1s;
}

@keyframes sk-bounce {
  0%,
  100% {
    transform: scale(0);
  }
  50% {
    transform: scale(1);
  }
}
</style>
