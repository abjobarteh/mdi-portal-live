<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>My Courses</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
          <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="courses"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.course.course_name`]="{ item }">
              <!-- <router-link class="custom-link" :to="`courses/${item.id}`">{{ item.course.course_name }}</router-link> -->
              <router-link
                class="custom-link"
                :to="{
                  path: `courses/${item.id}`,
                  query: { course_name: item.course.course_name },
                }"
              >
                {{ item.course.course_name }}
              </router-link>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>
  <script>
import * as XLSX from 'xlsx'
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'
import useVuelidate from '@vuelidate/core'
import { required, minLength } from '@vuelidate/validators'

Vue.use(Vue2Filters)

export default {
  name: 'MyCourses',
  props: {},
  components: {},
  data() {
    return {
      courses: [],
      headers: [
        { text: 'Course Name', value: 'course.course_name' },
        { text: 'Course Code', value: 'course.course_code' },
        { text: 'Semester', value: 'username' },
      ],

      page: 1,
      pageCount: 0,
      search: '',

      rules: {
        lecturer_id: { required },
        semester_courses_ids: { required },
      },

      v$: null,
    }
  },

  created() {
    this.setupValidation()
    this.getResults()
  },

  methods: {
    setupValidation() {
      this.v$ = useVuelidate(this.rules, this.allocateCourseFormData)
    },
    getResults() {
      axios
        .get('/api/my-semester-courses?page=' + this.page)
        .then(response => {
          this.courses = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.lecturers = []
          this.pageCount = 0
        })
    },

    exportToExcel() {
      const worksheet = XLSX.utils.json_to_sheet(this.lecturers)
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'lecturers')
      XLSX.writeFile(workbook, 'lecturers.xlsx')
    },
  },
}
</script>

<style>
.custom-link {
  text-decoration: none; /* Remove underline */
}
</style>

  
  
  
  
  
  
  
  
  
  
  
  