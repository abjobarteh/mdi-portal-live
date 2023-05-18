<template>
  <v-card>
    <v-card-text>
      <v-data-table :headers="headers" :items="courses" :items-per-page="13" class="elevation-1" hide-default-footer>
        <template v-slot:[`item.action`]="{ item }">
          <v-btn small color="primary">Edit</v-btn>
          <v-btn small color="error">Delete</v-btn>
        </template>
      </v-data-table>
      <!-- <v-pagination v-model="page" :length="pageCount" @input="getResults" /> -->
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'CourseTable',
  data() {
    return {
      courses: [
        {
          courseCode: 'CPS 10',
          courseName: 'Java',
          lecturer: 'Baba',
          register: true,
        },
      ],
      headers: [
        { text: 'Course Code', value: 'courseCode' },
        { text: 'Course Name', value: 'courseName' },
        { text: 'Lecturer', value: 'lecturer' },
        { text: 'Register', value: 'register' },
      ],
    }
  },
}
</script>


<!-- <v-card-text>
  <v-data-table
    :headers="headers"
    :items="filteredDepartments"
    :items-per-page="13"
    :search="search"
    class="elevation-1"
    hide-default-footer
  >
    <template v-slot:[`item.action`]="{ item }">
      <v-btn small color="primary" @click="editDepartment(item)">Edit</v-btn>
      <v-btn small color="error" @click="deleteDepartment(item)">Delete</v-btn>
    </template>
  </v-data-table>
  <v-pagination v-model="page" :length="pageCount" @input="getResults" />
</v-card-text> -->