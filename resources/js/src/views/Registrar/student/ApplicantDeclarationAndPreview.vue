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
        <DepartmentCard :department="department" />
      </v-col>
      <v-row v-if="this.$route.query.param == 'incoming'">
        <v-col cols="6">
          <v-btn @click="rejectStudentApplication" color="red" block dark>Reject</v-btn>
        </v-col>
        <v-col cols="6">
          <v-btn @click="acceptStudentApplication" color="green" block dark>Accept</v-btn>
        </v-col>
      </v-row>
    </v-container>
  </v-card>
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
      education: [],
      certificates: [],
      department: '',
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
          confirmButtonText: 'Schedule Interview',
        })
        .then(result => {
          if (result.isConfirmed) {
            // Show date selection popup
            swal
              .fire({
                title: 'Select Interview Date',
                // html: '<input type="date" id="interviewDate">',
                html: '<input type="datetime-local" id="interviewDate">',

                showCancelButton: true,
                confirmButtonText: 'Schedule',
              })
              .then(dateResult => {
                if (dateResult.isConfirmed) {
                  const interviewDate = document.getElementById('interviewDate').value

                  // Perform API request to schedule interview
                  axios
                    .post(`/api/accept-student-application`, {
                      userId: this.$route.params.id,
                      interviewDate: interviewDate,
                    })
                    .then(result => {
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
            axios.post(`/api/reject-student-application `, { userId: this.$route.params.id }).then(result => {
              swal
                .fire({
                  title: 'Success!',
                  text: 'Applicant rejected successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
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
          .post(`/api/view-accepted-applications?page=` + this.page, { userId: this.$route.params.id })
          .then(response => {
            console.log('anme', response.data.result.data[0].firstname)
            this.education = response.data.result.data[0].education
            this.certificates = response.data.result.data[0].certificates
            this.department = response.data.result.data[0].name

            this.pageCount = response.data.result.last_page

            this.applicantProfile = [
              {
                firstname: response.data.result.data[0].firstname,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
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
            this.department = response.data.result.data[0].name
            this.pageCount = response.data.result.last_page
            this.applicantProfile = [
              {
                firstname: response.data.result.data[0].firstname,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
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
            this.department = response.data.result.data[0].name
            this.pageCount = response.data.result.last_page
            this.applicantProfile = [
              {
                firstname: response.data.result.data[0].firstname,
                lastname: response.data.result.data[0].lastname,
                address: response.data.result.data[0].address,
                phonenumber: response.data.result.data[0].phonenumber,
                gender: response.data.result.data[0].gender,
                nationality: response.data.result.data[0].nationality,
                employment_status: response.data.result.data[0].employment_status,
                email: response.data.result.data[0].email,
                dob: response.data.result.data[0].dob,
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