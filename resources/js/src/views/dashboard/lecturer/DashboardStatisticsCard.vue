<template>
  <v-card>
    <v-card-title class="align-start">
      <span class="font-weight-semibold">Statistics Card</span>
      <v-spacer></v-spacer>
      <v-btn icon small class="me-n3 mt-n2">
        <v-icon>
          {{ icons.mdiDotsVertical }}
        </v-icon>
      </v-btn>
    </v-card-title>

    <v-card-text>
      <v-row>
        <!-- First instance -->
        <v-col cols="6" md="3" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[0].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[0].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[0].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ coursesCount }}
            </h3>
          </div>
        </v-col>

        <!-- Second instance -->
        <v-col cols="6" md="3" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[1].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[1].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[1].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ myStudentCount }}
            </h3>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiAccountOutline, mdiCurrencyUsd, mdiTrendingUp, mdiDotsVertical, mdiLabelOutline } from '@mdi/js'

export default {
  data() {
    return {
      myStudentCount: '',
      coursesCount: '',

      statisticsData: [
        {
          title: 'Total Courses',
          total: '245',
        },
        {
          title: 'Active students',
          total: '12.5',
        },
      ],

      resolveStatisticsIconVariation: data => {
        if (data === 'Sales') return { icon: mdiTrendingUp, color: 'primary' }
        if (data === 'Customers') return { icon: mdiAccountOutline, color: 'success' }
        if (data === 'Product') return { icon: mdiLabelOutline, color: 'warning' }
        if (data === 'Revenue') return { icon: mdiCurrencyUsd, color: 'info' }

        return { icon: mdiAccountOutline, color: 'success' }
      },

      // icons
      icons: {
        mdiDotsVertical,
        mdiTrendingUp,
        mdiAccountOutline,
        mdiLabelOutline,
        mdiCurrencyUsd,
      },
    }
  },

  methods: {
    fetchStatusCounts() {
      axios
        .get('/api/lecturer-dashboard')
        .then(response => {
          this.courses = response.data.myCourses.data
          this.myStudentCount = response.data.myStudents
          this.coursesCount = response.data.myCourses.data.length
          console.log('count', this.myStudentCount)
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
