<template>
  <v-card>
    <v-form ref="form" @submit.prevent="submit">
      <v-container style="background-color: #fefcff">
        <v-card-title class="headline">Course Applied For:</v-card-title>
        <v-card-text>
          <v-select
            outlined
            v-model="addApplicantProgramFormData.program_id"
            :items="programs.map(program => ({ id: program.id, name: program.name }))"
            item-value="id"
            item-text="name"
            label="Program"
          ></v-select>
          <!-- <v-select
            outlined
            v-model="addApplicantProgramFormData.course_level"
            :items="addApplicantProgramFormData.courseLevelOptions"
            required
            ><template v-slot:label>
              <span class="required-field">Course Level</span>
            </template></v-select
          > -->
          <v-card-actions class="d-flex justify-center">
            <v-btn color="primary" class="col-12" @click="submit()">Save</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      programs: [],
      studentInfo: '',
      educationFormData: {},
      addApplicantProgramFormData: {
        program_id: '',
        course_level: '',
        courseLevelOptions: [
          'Diploma',
          'Higher National Diploma',
          'Diploma 1 or 2',
          'Advanced Diploma',
          'Graduate Diploma',
          'Postgraduate Diploma',
        ],
      },
    }
  },
  methods: {
    submit() {
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
            axios
              .post(`/api/submit-applicant-department-info`, this.addApplicantProgramFormData)
              .then(result => {
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
              .catch(error => {
                swal.fire({
                  title: 'Error!',
                  text: error.response.data.message,
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },
  },
  created() {
    // axios
    //   .get('/api/view-departments?page=' + this.page)
    //   .then(response => {
    //     this.departments = response.data.result.data
    //     this.pageCount = response.data.result.last_page
    //   })
    //   .catch(err => {
    //     this.departments = []
    //     this.pageCount = 0
    //   })
    axios
      .get('/api/view-programs?page=' + this.page)
      .then(response => {
        this.programs = response.data.result.data
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.departments = []
        this.pageCount = 0
      })
  },
  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      this.addApplicantProgramFormData['id'] = this.studentInfo.user_id
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
