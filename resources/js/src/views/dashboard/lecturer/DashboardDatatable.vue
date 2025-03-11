<template>
  <v-card>
    <div class="text-center">
      <h4 class="mb-4 p-2">My Courses</h4>
      <v-col cols="12" md="3" class="pt-1">
        <v-select v-model="selectedSemester" :items="semesters" item-value="id" item-text="semester_name"
          label="Select Semester" outlined dense @change="onSemesterChange"></v-select>
      </v-col>
      <v-data-table :headers="headers" :items="courses" item-key="course_code" class="table-rounded" hide-default-footer
        disable-sort>
        <!-- Course Code -->
        <template v-slot:item.courseCode="{ item }">
          <div class="d-flex flex-column">
            <span class="d-block font-weight-semibold text--primary text-truncate">
              {{ item.course_code }}
            </span>
          </div>
        </template>

        <!-- Course Name -->
        <template v-slot:item.courseName="{ item }">
          {{ item.course_name }}
        </template>

        <!-- Total Students -->
        <template v-slot:item.studentCount="{ item }">
          {{ item.student_count }}
        </template>

        <!-- Male Students -->
        <template v-slot:item.maleStudentCount="{ item }">
          {{ item.male_student_count }}
        </template>

        <!-- Female Students -->
        <template v-slot:item.femaleStudentCount="{ item }">
          {{ item.female_student_count }}
        </template>

      </v-data-table>
    </div>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      courses: [],
      selectedSemester: null,
      semesters: [],
      headers: [
        { text: 'Course Code', value: 'courseCode' },
        { text: 'Course Name', value: 'courseName' },
        { text: 'Total Students', value: 'studentCount' },
        { text: 'Male Students', value: 'maleStudentCount' },
        { text: 'Female Students', value: 'femaleStudentCount' },
      ],
    }
  },

  methods: {
    fetchCourses() {
      axios
        .get('/api/lecturer-dashboard')
        .then(response => {
          const coursestat = response.data.coursestat;
          const coursestatmale = response.data.coursestatmale;
          const coursestatfemale = response.data.coursestatfemale;

          // Merge male and female counts into coursestat
          this.courses = coursestat.map(course => {
            const male = coursestatmale.find(m => m.course_code === course.course_code);
            const female = coursestatfemale.find(f => f.course_code === course.course_code);
            this.semesters = response.data.semester;
            return {
              ...course,
              male_student_count: male ? male.student_count : 0,
              female_student_count: female ? female.student_count : 0,
            };
          });

          console.log("Courses: ", this.courses);
        })
        .catch(error => {
          console.error("Error fetching courses:", error);
        });
    },
    onSemesterChange() {
      axios.get(`api/department-count-lecturer/${this.selectedSemester}`)
        .then(response => {
          console.log('Selected Semester:', this.selectedSemester);
          const coursestat = response.data.coursestat;
          const coursestatmale = response.data.coursestatmale;
          const coursestatfemale = response.data.coursestatfemale;

          // Merge male and female counts into coursestat
          this.courses = coursestat.map(course => {
            const male = coursestatmale.find(m => m.course_code === course.course_code);
            const female = coursestatfemale.find(f => f.course_code === course.course_code);
            this.semesters = response.data.semester;
            return {
              ...course,
              male_student_count: male ? male.student_count : 0,
              female_student_count: female ? female.student_count : 0,
            };
          });
        })
        .catch(error => {
          console.error('Error fetching data:', error);
        });
    },
  },

  created() {
    this.fetchCourses();
  },
}
</script>
