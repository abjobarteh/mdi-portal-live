<template>
  <v-card class="greeting-card">
    <v-row class="ma-0 pa-0">
      <v-col cols="8">
        <v-card-title class="text-no-wrap pt-1 ps-2"> Total Tution Fee Paid! </v-card-title>
        <v-card-subtitle class="text-no-wrap ps-2"> Overall </v-card-subtitle>
        <v-card-text class="d-flex align-center mt-2 pb-2 ps-2">
          <div>
            <p class="text-xl font-weight-semibold primary--text mb-2">D{{ Number(totalTutionFeePaid).toFixed(2) }}</p>
          </div>
        </v-card-text>
      </v-col>

      <v-col cols="4">
        <v-img
          contain
          height="180"
          width="159"
          :src="require(`@/assets/images/misc/triangle-${$vuetify.theme.dark ? 'dark' : 'light'}.png`).default"
          class="greeting-card-bg"
        ></v-img>
        <v-img
          contain
          height="148"
          max-width="123"
          class="greeting-card-trophy"
          :src="require('@/assets/images/logos/money.png').default"
        ></v-img>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      totalTutionFeePaid: 0,
    }
  },
  methods: {
    fetchStatusCounts() {
      axios
        .get('/api/profit-status')
        .then(response => {
          this.totalTutionFeePaid = response.data.totalTutionFeePaid
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


<style lang="scss" scoped>
.greeting-card {
  position: relative;
  .greeting-card-bg {
    position: absolute;
    bottom: 0;
    right: 0;
  }
  .greeting-card-trophy {
    position: absolute;
    bottom: 10%;
    right: 8%;
  }
}
// rtl
.v-application {
  &.v-application--is-rtl {
    .greeting-card-bg {
      right: initial;
      left: 0;
      transform: rotateY(180deg);
    }
    .greeting-card-trophy {
      left: 8%;
      right: initial;
    }
  }
}
</style>
<!-- totalTutionFeePaid -->
