<template>
  <v-card>
    <div class="text-center">
      <h4 class="mb-4 p-2">My Courses</h4>
      <v-data-table
        :headers="headers"
        :items="courses"
        item-key="full_name"
        class="table-rounded"
        hide-default-footer
        disable-sort
      >
        <!-- name -->
        <template v-slot:item.courseCode="{ item }">
          <div class="d-flex flex-column">
            <span class="d-block font-weight-semibold text--primary text-truncate">{{ item.course.course_code }}</span>
            <small>lecturer</small>
          </div>
        </template>
        <template v-slot:item.courseName="{ item }">
          {{ item.course.course_name }}
        </template>
      </v-data-table>
    </div>
  </v-card>
</template>

<script>
import { mdiSquareEditOutline, mdiDotsVertical } from '@mdi/js'
import data from './datatable-data'

export default {
  data() {
    return {
      courses: [],
      myStudentCount: '',
      headers: [
        { text: 'CourseCode', value: 'courseCode' },
        { text: 'CourseName', value: 'courseName' },
      ],
    }
  },

  methods: {
    fetchStatusCounts() {
      axios
        .get('/api/lecturer-dashboard')
        .then(response => {
          this.courses = response.data.myCourses.data
          console.log('courses', this.courses)
          this.myStudentCount = response.data.myStudents
          console.log(this.myStudentCount)
        })
        .catch(error => {
          console.error('Error fetching status counts:', error)
        })
    },
  },

  created() {
    this.fetchStatusCounts()
  },
}
</script>
