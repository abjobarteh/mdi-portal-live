<template>
  <v-card>
    <v-card-text>
      <v-data-table :headers="headers" :items="runnings" :items-per-page="13" class="elevation-1">
        <template v-slot:[`item.course_code`]="{ item }">
          {{ item.course.course_code }}
        </template>
        <template v-slot:[`item.course_name`]="{ item }">
          {{ item.course.course_name }}
        </template>
        <template v-slot:[`item.lecturer`]="{ item }">
          {{ item.lecturer.firstname }} {{ item.lecturer.lastname }}
        </template>
        <template v-slot:[`item.action`]="{ item }">
          <v-checkbox :disabled="registrationStatus == 0  || (!item.can_register && waivestatus == 0)" v-model="item.course.registered"
            @change="handleCheckboxChange(item)"></v-checkbox>
        </template>
      </v-data-table>
      <v-pagination v-model="page" :length="pageCount" />
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'RunningCourses',
  props: {
    runnings: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      registrationStatus: '',
      waivestatus: '',
      studentInfo: '',
      waivestudents: [],
      waiveData: {
        amount_paid: '',
        semester_id: '',
        student_id: '',
      },
      headers: [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
        { text: 'Lecturer Name', value: 'lecturer' },
        { text: 'Register', value: 'action', sortable: false },
      ],
      page: 1,
      pageCount: 0,
    }
  },

  created() {
    axios.get('/api/registerd-courses').then(response => {
      console.log('response ', response)
    })
    console.log('View',this.getUserProfile.id)
    this.waiveData.student_id = this.studentInfo.id
    axios.get(`/api/get-waive`, {
      params: {
        student_id: this.getUserProfile.id
      }
    })
      .then(response => {
        this.waivestatus=response.data.result
        console.log('Waive Data:', this.waivestatus);
      
      })
      .catch(error => {
        console.error('Error fetching waive data:', error);
      });


    axios
      .get('/api/registration-status')
      .then(response => {
        this.registrationStatus = response.data.result.registration_status
        console.log('running courses', this.runnings)
        console.log('running courses 2', this.registrationStatus)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
  },

  methods: {
    handleCheckboxChange(item) {
      console.log(item)
      if (item.course.registered) {
        this.showConfirmationDialog(item, 'Check')
      } else {
        this.showRemoveConfirmationDialog(item, 'Uncheck')
      }
    },

    showConfirmationDialog(item, action) {
      console.log('id ', item)
      swal
        .fire({
          title: 'Are you sure?',
          text: `You are about to register the course`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .post('/api/register-courses', {
                student_id: this.studentInfo.id,
                lecturer_id: item.lecturer.id,
                semester_id: item.semester_id,
                course_id: item.course_id,
                semester_course_id: item.id,
              })
              .then(result => {
                // show success alert
                this.addProgramDurationDialog = false
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Course Registered successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.$store.dispatch('userProfile')
                  })
              })
              .catch(error => {
                // show error alert
                swal
                  .fire({
                    title: 'Error!',
                    text: error.response.data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    {
                      window.location.reload() // this sometimes causes 419
                    }
                  })
              })
          } else {
            // Checkbox state is reverted if confirmation is canceled
            item.checked = !item.checked
          }
        })
    },

    showRemoveConfirmationDialog(item, action) {
      swal
        .fire({
          title: 'Are you sure?',
          text: `You are about to un-register the course`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .post('/api/un-register-courses', {
                student_id: this.studentInfo.id,
                lecturer_id: item.lecturer.id,
                semester_id: item.semester_id,
                course_id: item.course_id,
              })
              .then(result => {
                // show success alert
                this.addProgramDurationDialog = false
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Course unregistered successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.$store.dispatch('userProfile')
                  })
              })
              .catch(error => {
                console.log(error.response)
                // show error alert
                swal.fire({
                  title: 'Error!',
                  text: 'Failed to add program duration.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
          } else {
            // Checkbox state is reverted if confirmation is canceled
            item.checked = !item.checked
          }
        })
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
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
