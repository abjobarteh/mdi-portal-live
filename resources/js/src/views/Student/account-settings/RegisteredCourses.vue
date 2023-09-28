<template>
  <div>
    <v-card id="printable-content" v-for="transcript in registeredCourses" :key="transcript.SemesterSession">
      <v-card-title class="text-center d-flex justify-center">{{ transcript.SemesterSession }}</v-card-title>
      <v-data-table :headers="headers" :items="transcript.Courses" class="elevation-1" hide-default-footer>
        <!-- Slot for custom rendering of each row -->
        <template v-slot:item="props">
          <tr>
            <td class="text-start" style="width: 30%">
              <!-- Set the width as needed -->
              {{ props.item.CourseCode }}
            </td>
            <td class="text-start" style="width: 45%">
              <!-- Set the width as needed -->
              <!-- Render the course as a router-link -->
              <router-link :to="`/courses/${props.item.SemesterCourseId}`" class="router-link">
                {{ props.item.CourseName }}
              </router-link>
            </td>
            <td class="text-start" style="width: 25%">
              <!-- Set the width as needed -->
              {{ props.item.Lecturer }}
            </td>
          </tr>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>


<style scoped>
.semester-heading {
  text-align: center;
}

.router-link {
  text-decoration: none;
  /* Add any other custom styles for the router-link here */
}
</style>


<script>
export default {
  data() {
    return {
      headers: [
        { text: 'CourseCode', value: 'CourseCode' },
        { text: 'CourseName', value: 'CourseName' },
        { text: 'Lecturer', value: 'Lecturer' },
      ],
      registeredCourses: [],
      registrationStatus: '',
    }
  },
  //
  created() {
    axios
      .get('/api/registerd-courses')
      .then(response => {
        this.registeredCourses = response.data.result
        console.log('registed courses', this.runnings)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
  },

  methods: {},
}
</script>

