<template>
  <v-card>


    <v-row class="py-4">
      <v-col class="text-center">
        <span class="font-weight-bold text-h6">Department Student Statistics</span>
      </v-col>
    </v-row>
    <!-- First Table -->
    <v-data-table class="mt-2" :headers="deptheaders" :items="counts" item-key="name" hide-default-footer disable-sort>
      <template v-slot:item.name="{ item }">
        <div class="d-flex flex-column">
          <span class="d-block font-weight-semibold text--primary text-truncate">
            {{ item.name }}
          </span>
        </div>
      </template>
      <template v-slot:item.student_count="{ item }">
        {{ item.male_student_count + item.female_student_count  }}
      </template>
      <template v-slot:item.male_student_count="{ item }">
        {{ item.male_student_count }}
      </template>

      <template v-slot:item.female_student_count="{ item }">
        {{ item.female_student_count }}
      </template>
      <template v-slot:no-data>
        <span>No department data available.</span>
      </template>
    </v-data-table>

    <!-- Spacer -->
    <v-spacer></v-spacer>
    <v-divider class="my-4"></v-divider>

    <v-row class="py-4">
      <v-col class="text-center">
        <span class="font-weight-bold text-h6">Lecturer Data</span>
      </v-col>
    </v-row>
    <!-- Second Table -->
    <v-data-table class="mt-2" :headers="headers" :items="lecturers" item-key="full_name" hide-default-footer
      disable-sort>
      <template v-slot:item.fullname="{ item }">
        <div class="d-flex flex-column">
          <span class="d-block font-weight-semibold text--primary text-truncate">
            {{ item.lecturer.firstname + ' ' + item.lecturer.lastname }}
          </span>
          <small>lecturer</small>
        </div>
      </template>
      <template v-slot:item.phonenumber="{ item }">
        {{ item.lecturer.phonenumber }}
      </template>
      <template v-slot:item.course_count="{ item }">
        {{ item.course_count }}
      </template>
    </v-data-table>

  </v-card>
</template>

<script>
import { mdiSquareEditOutline, mdiDotsVertical } from '@mdi/js'
import data from './datatable-data'

export default {
  data() {
    return {
      lecturers: [],
      counts: [],
      malecounts: [],
      femalecounts: [],
      headers: [
        { text: 'Name', value: 'fullname' },
        { text: 'PhoneNumber', value: 'phonenumber' },
        { text: 'TotalCourses', value: 'course_count' },
      ],
      deptheaders: [
        { text: 'Department', value: 'department_name' },
        { text: 'Total Number Of Students', value: 'student_count' },
        { text: 'Total Number Of Male Students', value: 'male_student_count' },
        { text: 'Total Number Of Female Students', value: 'female_student_count' },
      ]
    }
  },

  methods: {
    fetchStatusCounts() {
      axios.get('/api/user-counts')
        .then(response => {
          const departments = response.data.departmentCount;
          const maleCounts = response.data.maledepartmentCount;
          const femaleCounts = response.data.femaledepartmentCount;

          // Merge male and female counts into the main department count array
          this.counts = departments.map(dept => {
            const maleDept = maleCounts.find(m => m.department_name === dept.department_name);
            const femaleDept = femaleCounts.find(f => f.department_name === dept.department_name);

            return {
              ...dept,
              male_student_count: maleDept ? maleDept.male_student_count : 0,
              female_student_count: femaleDept ? femaleDept.female_student_count : 0,
            };
          });

          this.lecturers = response.data.lecturersWithMostCourses;
          console.log('Merged Department Data:', this.counts);
        })
        .catch(error => {
          console.error('Error fetching status counts:', error);
        });
    },
  },

  created() {
    this.fetchStatusCounts()
  },
}
</script>
