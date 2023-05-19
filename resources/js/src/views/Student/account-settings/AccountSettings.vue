 <template>
  <div>
    <!-- <v-card>
      <v-card-text>
        <v-simple-table>
          <template v-slot:default>
            <tbody>
              <tr>
                <td>General Registration ID</td>
                <td>profile.regId</td>
              </tr>
              <tr>
                <td>Email</td>
                <td>profile.email</td>
              </tr>
              <tr>
                <td>Phone #</td>
                <td>profile.phone</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>profile.status</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card-text>
    </v-card> -->
    <v-card id="account-setting-card">
      <!-- tabs -->
      <v-tabs v-model="tab" show-arrows class="flexible-tabs">
        <v-tab v-for="tab in tabs" :key="tab.title" style="width: 335px">
          <v-icon size="20" class="me-3">
            {{ tab.icon }}
          </v-icon>
          <span>{{ tab.title }}</span>
        </v-tab>
      </v-tabs>

      <!-- tabs item -->
      <v-tabs-items v-if="runnings.length" v-model="tab">
        <v-tab-item>
          <running-courses :runnings="runnings"></running-courses>
        </v-tab-item>

        <v-tab-item>
          <program-courses :courses="courses"></program-courses>
        </v-tab-item>

        <v-tab-item>
          <transcript></transcript>
        </v-tab-item>

        <v-tab-item>
          <payments></payments>
        </v-tab-item>
      </v-tabs-items>
    </v-card>
  </div>
</template>

<script>
import { mdiAccountOutline, mdiLockOpenOutline, mdiInformationOutline } from '@mdi/js'

// demos
import RunningCourses from './RunningCourses.vue'
import ProgramCourses from './ProgramCourses.vue'
import Transcript from './Transcript.vue'
import Payments from './Payments.vue'

import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    RunningCourses,
    ProgramCourses,
    Payments,
    Transcript,
  },

  data() {
    return {
      studentInfo: '',
      tab: '',
      tabs: [
        { title: 'Running Courses', icon: mdiAccountOutline },
        { title: 'Program Courses', icon: mdiLockOpenOutline },
        { title: 'Transcript', icon: mdiInformationOutline },
        { title: 'Payment', icon: mdiInformationOutline },
      ],
      icons: {
        mdiAccountOutline,
        mdiLockOpenOutline,
        mdiInformationOutline,
      },
      courses: '',
      runnings: '',
    }
  },
  // .get('/api/view-departments?page=' + this.page)
  created() {
    axios
      .get('/api/running-courses?page=' + this.page)
      .then(response => {
        this.runnings = response.data.result
        // this.runnings = response.data.result.map(course => {
        //   return {
        //     ...course.course,
        //     lecturer: course.lecturer.firstname + ' ' + course.lecturer.lastname,
        //   }
        // })
        console.log('running courses', this.runnings)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
  },

  methods: {
    getResults() {
      axios
        .post('/api/department-courses?page=' + this.page, { department_id: this.studentInfo.department_id })
        .then(response => {
          this.courses = response.data.result.data
          console.log('courses', this.courses[0].courses)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.courses = []
          this.pageCount = 0
        })
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      this.getResults()
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  computed: {
    getUserProfile() {
      return this.$store.getters.getUserProfile
    },
  },
}
</script>


<style>
.flex {
  width: 100%;
  margin: 0 auto;
}

@media (min-width: 1024px) {
  .flex {
    min-width: 1320px;
  }
}
</style>


