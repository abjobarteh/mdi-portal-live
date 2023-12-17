 <template>
  <div class="container">
    <v-card id="account-setting-card">
      <!-- tabs -->
      <v-tabs v-model="tab" show-arrows class="flexible-tabs">
        <v-tab v-for="tab in tabs" :key="tab.title">
          <v-icon size="20" class="me-3">
            {{ tab.icon }}
          </v-icon>
          <span>{{ tab.title }}</span>
        </v-tab>
      </v-tabs>

      <!-- tabs item -->
      <v-tabs-items v-model="tab">
        <v-tab-item>
          <running-courses v-if="runnings.length" :runnings="runnings"></running-courses>
          <h6 style="color: green !important; margin-top: 15px; margin-bottom: 15px" v-else>No Running Courses</h6>
        </v-tab-item>

        <v-tab-item>
          <registered-courses :courses="courses"></registered-courses>
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

        <v-tab-item>
          <deferments></deferments>
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
import RegisteredCourses from './RegisteredCourses.vue'
import Transcript from './Transcript.vue'
import Payments from './Payments.vue'
import Deferments from './Deferments.vue'

import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    RunningCourses,
    ProgramCourses,
    Payments,
    Transcript,
    RegisteredCourses,
    Deferments,
  },

  data() {
    return {
      studentInfo: '',
      tab: '',
      tabs: [
        { title: 'Running Courses', icon: mdiAccountOutline },
        { title: 'Registered Courses', icon: mdiLockOpenOutline },
        { title: 'Program Courses', icon: mdiLockOpenOutline },
        { title: 'Transcript List', icon: mdiInformationOutline },
        { title: 'Tuition Payments', icon: mdiInformationOutline },
        { title: 'Deferments', icon: mdiInformationOutline },
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
  created() {
    axios
      .get('/api/running-courses?page=' + this.page)
      .then(response => {
        this.runnings = response.data.result

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
.container {
  max-width: 1400px; /* Set the maximum width as you desire */
  margin: 0 auto; /* Center the content */
}

/* Media query for larger screens */
@media (min-width: 1400px) {
  .container {
    width: 100%; /* Remove the max-width constraint on larger screens */
  }
}
</style>


