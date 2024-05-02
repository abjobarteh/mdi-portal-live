<template>
  <v-card>
    <div class="text-center">
      <h4 class="mb-4 p-2">Lecturers with Max Courses</h4>
      <v-data-table
        :headers="headers"
        :items="lecturers"
        item-key="full_name"
        class="table-rounded"
        hide-default-footer
        disable-sort
      >
        <!-- name -->
        <template v-slot:item.fullname="{ item }">
          <div class="d-flex flex-column">
            <span class="d-block font-weight-semibold text--primary text-truncate">{{
              item.lecturer.firstname + ' ' + item.lecturer.lastname
            }}</span>
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
    </div>
  </v-card>
</template>

<script>
import { mdiSquareEditOutline, mdiDotsVertical } from '@mdi/js'
import data from './datatable-data'

export default {
  data() {
    return {
      lecturers: [],
      headers: [
        { text: 'Name', value: 'fullname' },
        { text: 'PhoneNumber', value: 'phonenumber' },
        { text: 'TotalCourses', value: 'course_count' },
      ],
    }
  },

  methods: {
    fetchStatusCounts() {
      axios
        .get('/api/user-counts')
        .then(response => {
          this.lecturers = response.data.lecturersWithMostCourses
          console.log('Lecturers ', this.lecturers)
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
