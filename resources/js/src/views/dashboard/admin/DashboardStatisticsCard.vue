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
        <v-col cols="6" md="4" class="d-flex align-center">
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
              {{ statisticsData[0].total }}
            </h3>
          </div>
        </v-col>

        <!-- Second instance -->
        <v-col cols="6" md="4" class="d-flex align-center">
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
              {{ statisticsData[1].total }}
            </h3>
          </div>
        </v-col>

        <!-- Third instance -->
        <v-col cols="6" md="4" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[2].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[2].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[2].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ statisticsData[2].total }}
            </h3>
          </div>
        </v-col>

        <!-- Fourth instance -->
        <v-col cols="6" md="4" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[3].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[3].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[3].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ statisticsData[3].total }}
            </h3>
          </div>
        </v-col>
        <v-col cols="6" md="4" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[3].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[3].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[4].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ statisticsData[4].total }}
            </h3>
          </div> </v-col
        ><v-col cols="6" md="4" class="d-flex align-center">
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation(statisticsData[4].title).color"
            rounded
            class="elevation-1"
          >
            <v-icon dark color="white" size="30">
              {{ resolveStatisticsIconVariation(statisticsData[4].title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ statisticsData[5].title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ statisticsData[5].total }}
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
      statisticsData: [
        {
          title: 'Active Lecturers',
          total: '245',
        },
        {
          title: 'Active students',
          total: '12.5',
        },
        {
          title: 'Accepted Students',
          total: '100',
        },
        {
          title: 'Rejected Students',
          total: '88',
        },
        {
          title: 'Male Students',
          total: '88',
        },
        {
          title: 'Female Students',
          total: '88',
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
        .get('/api/user-counts')
        .then(response => {
          this.statisticsData[0].total = response.data.activeLecturers
          this.statisticsData[1].total = response.data.activeStudents
          this.statisticsData[2].total = response.data.acceptedStudents
          this.statisticsData[3].total = response.data.rejectedStudents
          this.statisticsData[4].total = response.data.maleStudents
          this.statisticsData[5].total = response.data.femaleStudents
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
