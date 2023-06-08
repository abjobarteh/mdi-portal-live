<template>
  <v-card>
    <v-form ref="form" @submit.prevent="submit">
      <v-container style="background-color: #fefcff">
        <v-card-title class="headline">Department Information</v-card-title>
        <v-card-text>
          <v-select
            outlined
            v-model="addApplicantDepartmentFormData.department_id"
            :items="departments.map(department => ({ id: department.id, name: department.name }))"
            item-value="id"
            item-text="name"
            label="Department"
          ></v-select>
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
      departments: [],
      studentInfo: '',
      educationFormData: {},
      addApplicantDepartmentFormData: {
        department_id: '',
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
            axios.post(`/api/submit-applicant-department-info`, this.addApplicantDepartmentFormData).then(result => {
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
  created() {
    axios
      .get('/api/view-departments?page=' + this.page)
      .then(response => {
        this.departments = response.data.result.data
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
      this.addApplicantDepartmentFormData['id'] = this.studentInfo.user_id
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