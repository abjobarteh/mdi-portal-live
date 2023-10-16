Copy code
<template>
  <div>
    <v-card>
      <v-card-title> Select Semester and Course </v-card-title>
      <v-card-text>
        <v-row>
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
          <v-col cols="6">
            <v-select
              outlined
              v-model="selectedAssesmentType"
              :items="assesmentTypes"
              label="Select Assessment Type"
            ></v-select>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>

    <v-card class="mt-3">
      <v-card-title> Add Grades </v-card-title>
      <v-card-text>
        <v-data-table
          v-if="selectedAssesmentType == 'Test'"
          :headers="tableHeaders.filter(header => header.text !== 'Exam Mark')"
          :items="students"
          :items-per-page="10"
          class="elevation-1"
        >
          <template v-slot:item.name="{ item }">
            <span>{{ item.student.firstname + ' ' + item.student.lastname }}</span>
          </template>
          <template v-slot:item.testMark="{ item }">
            <v-text-field v-model="item.test_mark" outlined dense class="mt-3"></v-text-field>
          </template>
        </v-data-table>

        <v-data-table
          v-if="selectedAssesmentType == 'Exam'"
          :headers="tableHeaders.filter(header => header.text !== 'Test Mark')"
          :items="students"
          :items-per-page="10"
          class="elevation-1"
        >
          <template v-slot:item.name="{ item }">
            <span>{{ item.student.firstname + ' ' + item.student.lastname }}</span>
          </template>
          <template v-slot:item.examMark="{ item }">
            <v-text-field v-model="item.exam_mark" outlined dense class="mt-3"></v-text-field>
          </template>
        </v-data-table>
      </v-card-text>
      <v-card-actions>
        <v-row>
          <v-col cols="12" v-if="selectedAssesmentType == 'Test'">
            <v-btn
              block
              color="success"
              :disabled="selectedCourse === null || selectedAssesmentType !== 'Test' || isTestMarkInvalid"
              @click="saveGrades"
              >Save</v-btn
            >
          </v-col>
          <v-col cols="12" v-if="selectedAssesmentType == 'Exam'">
            <v-btn
              block
              color="success"
              :disabled="selectedCourse == null || selectedAssesmentType !== 'Exam' || isExamMarkInvalid"
              @click="saveExamAndSubmitGrades"
              >Submit</v-btn
            >
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
      selectedAssesmentType: null,
      selectedCourse: null,
      assesmentTypes: ['Test', 'Exam'],
      courses: ['Course 1', 'Course 2', 'Course 3'],
      students: [],
      tableHeaders: [
        { text: 'Student Name', value: 'name' },
        { text: 'Test Mark', value: 'testMark' },
        { text: 'Exam Mark', value: 'examMark' },
      ],
    }
  },

  created() {
    this.getMyCourses()
  },

  watch: {
    selectedCourse(newCourseId) {
      this.getMarks(newCourseId)
    },
    selectedAssesmentType(type) {
      console.log('print ', type)
    },
  },

  computed: {
    isTestMarkInvalid() {
      return this.students.some(
        item =>
          item.test_mark < 0 ||
          item.test_mark > 50 ||
          item.test_mark == null ||
          item.test_mark == '' ||
          isNaN(item.test_mark),
      )
    },
    isExamMarkInvalid() {
      return this.students.some(
        item =>
          item.exam_mark < 0 ||
          item.exam_mark > 50 ||
          item.exam_mark == null ||
          item.exam_mark == '' ||
          isNaN(item.exam_mark),
      )
    },
  },

  methods: {
    getMyCourses() {
      axios.get('/api/my-courses').then(result => {
        this.myCourses = result.data.result
        console.log(this.myCourses)
      })
    },
    getMarks(courseId) {
      axios.post('/api/manage-student-marks', { course_id: courseId }).then(result => {
        this.students = result.data.result
        // this.students = result.data.result.map(item => ({
        //   name: item.student.firstname + ' ' + item.student.lastname,
        //   testMark: item.test_mark,
        //   examMark: item.exam_mark,
        // }))
      })
    },
    saveGrades() {
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .post('/api/save-student-test-marks', { student: this.students })
              .then(response => {
                console.log('Success:', response.data)
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Test Marks added successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    window.location.reload()
                  })
              })
              .catch(error => {
                console.error('Error:', error)
                // Handle the error
              })
          }
        })
    },
    saveExamAndSubmitGrades() {
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .post('/api/save-student-exam-marks-and-submit', { student: this.students })
              .then(response => {
                console.log('Success:', response.data)
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Exam Marks added successfully and submitted successfully',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    window.location.reload()
                  })
              })
              .catch(error => {
                console.error('Error:', error)
                // Handle the error
              })
          }
        })
    },
  },
}
</script>
<!-- manage-student-marks -->
