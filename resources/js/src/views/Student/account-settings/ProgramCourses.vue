<template>
  <v-card>
    <v-card-text>
      <v-data-table :headers="headers" :items="courses[0].courses" :items-per-page="13" class="elevation-1">
        <template v-slot:[`item.action`]="{ item }">
          <v-btn small color="primary">Edit</v-btn>
          <v-btn small color="error">Delete</v-btn>
        </template>
      </v-data-table>
      <v-pagination v-model="page" :length="pageCount" />
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'ProgramCourses',
  props: {
    courses: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      headers: [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
      ],
      page: 1,
      pageCount: 0,
    }
  },

  created() {
    console.log('here we are')
  },

  mounted() {
    console.log('props courses', this.courses)
  },
}
</script>


