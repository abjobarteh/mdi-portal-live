<template>
  <v-card>
    <v-card-text>
      <v-data-table :headers="headers" :items="runnings" :items-per-page="13" class="elevation-1">
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
  name: 'RunningCourses',
  props: {
    runnings: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      headers: [
        { text: 'Course Code', value: 'course_code' },
        { text: 'Course Name', value: 'course_name' },
        { text: 'Lecturer Name', value: 'lecturer' },
      ],
      page: 1,
      pageCount: 0,
    }
  },

  mounted() {
    console.log('props running courses', this.runnings)
  },
}
</script>


