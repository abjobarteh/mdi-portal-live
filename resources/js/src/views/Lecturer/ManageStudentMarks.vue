<template>
  <div>
    <v-card>
      <v-card-title> Select Semester and Course </v-card-title>
      <v-card-text>
        <v-row>
          <v-col cols="4">
            <v-select outlined v-model="selectedGradeType" :items="GradeTypes" label="Select Grade Type"></v-select>
          </v-col>
          <v-col cols="4">
            <!-- <v-select outlined v-model="selectedCourse" :items="courses" label="Select Course"></v-select> -->
            <v-select v-model="selectedCourse"
              :items="myCourses.map(course => ({ id: course.id, name: course.course_name }))" item-value="id"
              item-text="name" label="Select Course" outlined></v-select>
          </v-col>
          <v-col cols="4">
            <v-select outlined v-model="selectedAssesmentType" :items="assesmentTypes"
              label="Select Assessment Type"></v-select>
          </v-col>


        </v-row>


        <div class="d-flex align-center" style="width: 400px; justify-content: space-between;">
          <v-file-input v-model="selectedFile" label="Upload Marks" class="mr-6"
            style="margin-right: 10px;"></v-file-input>

        </div>

        <div class="d-flex align-center" style="width: 400px; justify-content: space-between;">
          <v-btn @click="handleFile" color="success" style="margin-right: 10px;" :disabled="!isuploadEnabled">Handle
            File</v-btn>
          <v-btn @click="export_template" color="primary" :disabled="!isButtonEnabled"
            style="margin-right: 10px;">Generate Upload Mark
            Template</v-btn>
          <v-btn @click="submit_mark" color="secondary" :disabled="!isButtonEnabled">Submit Marks</v-btn>
        </div>

      </v-card-text>
    </v-card>

    <v-card class="mt-3">
      <v-card-title>
        <span class="mr-auto">Add Grades</span>

      </v-card-title>

      <v-card-text>
        <v-data-table v-if="selectedAssesmentType == 'Continuous Assessment'"
          :headers="tableHeaders.filter(header => header.text !== 'Exam Mark')" :items="students" :items-per-page="10"
          class="elevation-1">
          <template v-slot:item.name="{ item }">
            <span>{{ item.student.firstname + ' ' + item.student.lastname }}</span>
          </template>
          <template v-slot:item.testMark="{ item }">
            <v-text-field v-model="item.test" outlined dense class="mt-5"></v-text-field>
          </template>
          <template v-slot:item.assignmentMark="{ item }">
            <v-text-field v-model="item.assignment" outlined dense class="mt-5"></v-text-field>
          </template>
        </v-data-table>

        <v-data-table v-if="selectedAssesmentType == 'Exam'"
          :headers="tableHeaders.filter(header => header.text !== 'Test Mark')" :items="students" :items-per-page="10"
          class="elevation-1">
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
          <v-col cols="12" v-if="selectedAssesmentType == 'Continuous Assessment'">
            <v-btn block color="success" :disabled="selectedCourse === null || selectedAssesmentType !== 'Continuous Assessment' || isTestMarkInvalid || selectedGradeType == null
              " @click="saveGrades">Save</v-btn>
          </v-col>
          <v-col cols="12" v-if="selectedAssesmentType == 'Exam'">
            <v-btn block color="success"
              :disabled="selectedCourse == null || selectedAssesmentType !== 'Exam' || isExamMarkInvalid || selectedGradeType == null"
              @click="saveExamAndSubmitGrades">Save</v-btn>
          </v-col>
        </v-row>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import readXlsxFile from 'read-excel-file'
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      selectedFile: null,
      jsonData: [],
      myCourses: [],
      course_id: null,
      selectedAssesmentType: null,
      selectedCourse: null,
      selectedGradeType: null,
      grade_type: null,
      GradeTypes: ['Old', 'New'],
      assesmentTypes: ['Continuous Assessment', 'Exam'],
      courses: ['Course 1', 'Course 2', 'Course 3'],
      students: [],
      tableHeaders: [
        { text: 'Student Name', value: 'name' },
        { text: 'Test Mark (25%)', value: 'testMark' },
        { text: 'Assignment Mark (25%)', value: 'assignmentMark' },
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
      return this.students.some(item =>
        item.test < 0 ||
        item.test > 25 ||
        item.test == null ||
        item.test === '' ||
        isNaN(item.test) ||

        item.assignment < 0 ||
        item.assignment > 25 ||
        item.assignment == null ||
        item.assignment === '' ||
        isNaN(item.assignment)
      );
    }
    ,
    isButtonEnabled() {
      this.course_id = this.selectedCourse;
      console.log('Selected Course', this.course_id)
      return this.selectedCourse !== null;
    },
    isuploadEnabled() {
      return this.selectedCourse !== null && this.selectedGradeType !== null;
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
    handleFile() {
      if (!this.selectedFile) return;

      let formData = new FormData();
      formData.append("file", this.selectedFile);
      formData.append("course_id", this.selectedCourse);
      formData.append("grade_type", this.selectedGradeType);

      axios.post("/api/import-marks", formData, {
        headers: { "Content-Type": "multipart/form-data" }
      })
        .then(response => {
          swal.fire({
            icon: 'success',
            title: 'Marks uploaded successfully!',
            showConfirmButton: false,
            timer: 1500
          }).then(() => {
            window.location.reload()
          })
        })
        .catch(error => {
          if (error.response && error.response.data.error) {
            // Show error message using SweetAlert2
            swal.fire({
              icon: 'error',
              title: 'Error Uploading File',
              text: error.response.data.error,
            });

          } else {
            // General error handling
            swal.fire({
              icon: 'error',
              title: 'Error Uploading File',
            });
          }
        });
    },

    submit_mark() {
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
          axios.
          post('/api/submit-course-marks', {
              course_id: this.course_id
          }).then(response => {
            swal
              .fire({
                title: 'Success!',
                text: 'Marks Submitted Successfully',
                icon: 'success',
                confirmButtonText: 'OK',
              })
              .then(() => {
                window.location.reload()
              })
          })
            .catch(error => {
              console.error("Mark Submission Failed", error);
            });
        });
    },
    export_template() {
      axios.get('/api/get-students-export', {
        params: {
          course_id: this.course_id
        },
        responseType: 'blob' // Ensures correct file handling
      })
        .then(response => {
          const url = window.URL.createObjectURL(new Blob([response.data]));
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('download', 'MARK_UPLOAD_TEMPLATE_FILE.xlsx'); // Set download file name
          document.body.appendChild(link);
          link.click();
          link.remove();
        })
        .catch(error => {
          console.error("Export failed:", error);
        });
    }
    ,
    getMyCourses() {
      axios.get('/api/my-courses').then(result => {
        this.myCourses = result.data.result
        console.log(this.myCourses)
      })
    },
    getMarks(courseId) {
      axios.post('/api/manage-student-marks', { course_id: courseId }).then(result => {
        this.students = result.data.result

        // this.students[0].student.firstname == 'Baba'
        //   ? (this.students[0].test_mark = 50)
        //   : (his.students[0].test_mark = 40)
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
              .post('/api/save-student-test-marks', { student: this.students, grade_type: this.selectedGradeType })
              .then(response => {
                console.log('Success:', response.data)
                swal
                  .fire({
                    title: 'Success!',
                    text: 'Continuous Assessment Marks added successfully.',
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
              .post('/api/save-student-exam-marks-and-submit', { student: this.students, grade_type: this.selectedGradeType })
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
