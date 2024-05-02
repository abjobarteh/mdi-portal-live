<template>
  <v-card>
    <v-card-title class="align-start">
      <span>Total Earning This Semester</span>
      <v-spacer></v-spacer>

      <v-btn icon small class="me-n3 mt-n2">
        <v-icon>
          {{ icons.mdiDotsVertical }}
        </v-icon>
      </v-btn>
    </v-card-title>

    <v-card-text class="my-7">
      <div class="d-flex align-center">
        <h1 class="text-4xl font-weight-semibold">
          D{{
            Number(
              totalEarning[0].currentSemesterSoldAdmissionCodes + totalEarning[1].currentSemesterTuitionPaid,
            ).toFixed(2)
          }}
        </h1>

        <div class="d-flex align-center mb-n3">
          <v-icon size="40" color="success">
            {{ icons.mdiMenuUp }}
          </v-icon>
          <span class="text-base font-weight-medium success--text ms-n2">10%</span>
        </div>
      </div>

      <h4 class="mt-2 font-weight-medium">
        Compared to D{{
          Number(totalEarning[0].lastSemesterSoldAdmissionCodes + totalEarning[1].lastSemesterTuitionPaid).toFixed(2)
        }}
        last semester
      </h4>
    </v-card-text>

    <v-card-text>
      <div class="d-flex align-start">
        <v-avatar rounded size="38" color="#5e56690a" class="me-4">
          <v-img contain :src="totalEarning[0].avatar" height="20"></v-img>
        </v-avatar>

        <div class="d-flex align-center flex-grow-1 flex-wrap">
          <div>
            <h4 class="font-weight-medium">
              {{ totalEarning[0].title }}
            </h4>
            <span class="text-xs text-no-wrap">{{ totalEarning[0].subtitle }}</span>
          </div>

          <v-spacer></v-spacer>

          <div class="ms-1">
            <p class="text--primary font-weight-medium mb-1">
              D{{ Number(totalEarning[0].currentSemesterSoldAdmissionCodes).toFixed(2) }}
            </p>
            <v-progress-linear :value="totalEarning[0].progress" :color="totalEarning[0].color"></v-progress-linear>
          </div>
        </div>
      </div>

      <div class="mt-8 d-flex align-start">
        <v-avatar rounded size="38" color="#5e56690a" class="me-4">
          <v-img contain :src="totalEarning[1].avatar" height="20"></v-img>
        </v-avatar>

        <div class="d-flex align-center flex-grow-1 flex-wrap">
          <div>
            <h4 class="font-weight-medium">
              {{ totalEarning[1].title }}
            </h4>
            <span class="text-xs text-no-wrap">{{ totalEarning[1].subtitle }}</span>
          </div>

          <v-spacer></v-spacer>

          <div class="ms-1">
            <p class="text--primary font-weight-medium mb-1">
              D{{ Number(totalEarning[1].currentSemesterTuitionPaid).toFixed(2) }}
            </p>
            <v-progress-linear :value="totalEarning[1].progress" :color="totalEarning[1].color"></v-progress-linear>
          </div>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>

<script>
import { mdiDotsVertical, mdiMenuUp } from '@mdi/js'

export default {
  data() {
    return {
      totalEarning: [
        {
          avatar: require('@/assets/images/logos/zipcar.png').default,
          title: 'Admission Codes',
          currentSemesterSoldAdmissionCodes: '',
          lastSemesterSoldAdmissionCodes: '',
          progress: '85',
          color: 'primary',
        },
        {
          avatar: require('@/assets/images/logos/bitbank.png').default,
          title: 'Tuition Fees',
          currentSemesterTuitionPaid: '',
          lastSemesterTuitionPaid: '',
          progress: '65',
          color: 'info',
        },
      ],
      icons: { mdiDotsVertical, mdiMenuUp },
    }
  },
  methods: {
    fetchStatusCounts() {
      axios
        .get('/api/profit-status')
        .then(response => {
          this.totalEarning[0].currentSemesterSoldAdmissionCodes = response.data.currentSemesterSoldAdmissionCodes
          this.totalEarning[0].lastSemesterSoldAdmissionCodes = response.data.lastSemesterSoldAdmissionCodes
          this.totalEarning[0].currentSemesterTuitionPaid = response.data.currentSemesterTuitionPaid
          this.totalEarning[0].lastSemesterTuitionPaid = response.data.lastSemesterTuitionPaid
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
