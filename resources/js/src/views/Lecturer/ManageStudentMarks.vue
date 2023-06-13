Copy code
<template>
  <div>
    <v-card>
      <v-card-title> Select Semester and Course </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="6">
            <v-select outlined v-model="selectedSemester" :items="semesters" label="Select Semester"></v-select>
          </v-col>
          <v-col cols="6">
            <!-- <v-select outlined v-model="selectedCourse" :items="courses" label="Select Course"></v-select> -->
            <v-select
              v-model="selectedCourse"
              :items="myCourses.map(course => ({ id: course.id, name: course.course_name }))"
              item-value="id"
              item-text="name"
              label="Select Course"
              outlined
            ></v-select>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card class="mt-3">
      <v-card-title> Add Grades </v-card-title>
      <v-card-text>
        <v-data-table :headers="tableHeaders" :items="students" :items-per-page="10" class="elevation-1">
          <template v-slot:item.testMark="{ item }">
            <v-text-field v-model="item.testMark" outlined dense class="mt-3"></v-text-field>
          </template>
          <template v-slot:item.examMark="{ item }">
            <v-text-field v-model="item.examMark" outlined dense class="mt-3"></v-text-field>
          </template>
        </v-data-table>
      </v-card-text>
      <v-card-actions>
        <v-row>
          <v-col cols="6">
            <v-btn block color="success" @click="saveGrades">Save</v-btn>
          </v-col>
          <v-col cols="6">
            <v-btn block color="success" @click="submitGrades">Submit</v-btn>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      myCourses: [],
      selectedSemester: null,
      selectedCourse: null,
      semesters: ['Semester 1', 'Semester 2', 'Semester 3'],
      courses: ['Course 1', 'Course 2', 'Course 3'],
      students: [
        { name: 'John Doe', testMark: 50, examMark: 50 },
        { name: 'Jane Smith', testMark: '', examMark: '' },
        { name: 'Mike Johnson', testMark: '', examMark: '' },
      ],
      tableHeaders: [
        { text: 'Student Name', value: 'name' },
        { text: 'Test Mark', value: 'testMark' },
        { text: 'Exam Mark', value: 'examMark' },
      ],
    }
  },

  created() {
    axios.get('/api/my-courses').then(result => {
      this.myCourses = result.data.result
      console.log(this.myCourses)
      swal.fire({
        title: 'Success!',
        text: 'grade added successfully.',
        icon: 'success',
        confirmButtonText: 'OK',
      })
    })
    // this.getMarks()
  },

  watch: {
    selectedCourse(newCourseId) {
      this.getMarks(newCourseId)
    },
  },

  methods: {
    getMarks(courseId) {
      axios.post('/api/manage-student-marks', { course_id: courseId }).then(result => {
        this.students = result.data.result.map(item => ({
          name: item.student.firstname + ' ' + item.student.lastname,
          testMark: item.test_mark,
          examMark: item.exam_mark,
        }))
        console.log('student ', this.students)
      })
    },
  },
}
</script>
<!-- manage-student-marks -->