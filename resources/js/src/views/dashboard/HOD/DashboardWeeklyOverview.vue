<template>
  <v-card>
    <v-card-title class="align-start">
      <span>Weekly Overview</span>

      <v-spacer></v-spacer>

      <v-btn icon small class="mt-n2 me-n3">
        <v-icon size="22">
          {{ icons.mdiDotsVertical }}
        </v-icon>
      </v-btn>
    </v-card-title>

    <v-card-text>
      <!-- Chart -->
      <vue-apex-charts :options="chartOptions" :series="chartData" height="210"></vue-apex-charts>

      <div class="d-flex align-center">
        <h3 class="text-2xl font-weight-semibold me-4">45%</h3>
        <span>Students exhibited active engagement in portal activities, last week. 🤩</span>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
// eslint-disable-next-line object-curly-newline
import { mdiDotsVertical, mdiTrendingUp, mdiCurrencyUsd } from '@mdi/js'

export default {
  components: {
    VueApexCharts,
  },
  data() {
    const $vuetify = this.$vuetify || null
    const customChartColor = $vuetify ? ($vuetify.theme.isDark ? '#3b3559' : '#f5f5f5') : '#f5f5f5'

    return {
      chartOptions: {
        colors: [
          customChartColor,
          customChartColor,
          customChartColor,
          customChartColor,
          $vuetify ? $vuetify.theme.currentTheme.primary : customChartColor,
          customChartColor,
          customChartColor,
        ],
        chart: {
          type: 'bar',
          toolbar: {
            show: false,
          },
          offsetX: -15,
        },
        plotOptions: {
          bar: {
            columnWidth: '40%',
            distributed: true,
            borderRadius: 8,
            startingShape: 'rounded',
            endingShape: 'rounded',
          },
        },
        dataLabels: {
          enabled: false,
        },
        legend: {
          show: false,
        },
        xaxis: {
          categories: ['M', 'T', 'W', 'T', 'F', 'S', 'S'],
          axisBorder: {
            show: false,
          },
          axisTicks: {
            show: false,
          },
          tickPlacement: 'on',
          labels: {
            show: false,
            style: {
              fontSize: '12px',
            },
          },
        },
        yaxis: {
          show: true,
          tickAmount: 4,
          labels: {
            offsetY: 3,
            formatter: value => `${value}`,
          },
        },
        stroke: {
          width: [2, 2],
        },
        grid: {
          strokeDashArray: 12,
          padding: {
            right: 0,
          },
        },
      },
      chartData: [
        {
          data: [],
        },
      ],
    }
  },
  methods: {
    fetchStatusCounts() {
      axios
        .get('api/user-counts')
        .then(response => {
          console.log('data ', response.data)
          this.chartData[0].data = response.data.weekyStudentLogins
          // Add more data points if needed
          console.log('res ', this.chartData[0].data)
        })
        .catch(error => {
          console.error('Error fetching status counts:', error)
        })
    },
  },
  created() {
    // Fetch data when the component is created
    this.fetchStatusCounts()
  },
  computed: {
    icons() {
      return {
        mdiDotsVertical,
        mdiTrendingUp,
        mdiCurrencyUsd,
      }
    },
  },
}
</script>

<style>
/* Add your styles here */
</style>
