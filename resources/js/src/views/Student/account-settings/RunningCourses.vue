<template>
  <v-card>
    <v-card-text>
      <v-data-table :headers="headers" :items="runnings" :items-per-page="13" class="elevation-1">
        <template v-slot:[`item.course_code`]="{ item }">
          {{ item.course.course_code }}
        </template>
        <template v-slot:[`item.course_name`]="{ item }">
          {{ item.course.course_name }}
        </template>
        <template v-slot:[`item.lecturer`]="{ item }">
          {{ item.lecturer.firstname }} {{ item.lecturer.lastname }}
        </template>
        <template v-slot:[`item.action`]="{ item }">
          <v-checkbox v-model="item.checked" @change="handleCheckboxChange(item)"></v-checkbox>
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
        { text: 'Register', value: 'action', sortable: false },
      ],
      page: 1,
      pageCount: 0,
    }
  },

  methods: {
    handleCheckboxChange(item) {
      console.log(item)
      if (item.checked) {
        this.showConfirmationDialog(item, 'Check')
      } else {
        this.showConfirmationDialog(item, 'Uncheck')
      }
    },

    showConfirmationDialog(item, action) {
      swal
        .fire({
          title: 'Are you sure?',
          text: `You are about to register the course`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'Cancel',
        })
        .then(result => {
          if (result.isConfirmed) {
            // Confirmation is confirmed, perform further actions
            console.log(`Confirmation received to ${action.toLowerCase()} ${item.lecturer}`)
          } else {
            // Checkbox state is reverted if confirmation is canceled
            item.checked = !item.checked
          }
        })
    },
  },

  mounted() {
    console.log('props running courses', this.runnings)
  },
}
</script>


