<template>
  <div>
    <v-card id="printable-content" v-for="payment in payments" :key="payment.SemesterSession">
      <v-card-title class="text-center d-flex justify-between">
        {{ payment.SemesterSession }}
        <v-btn color="success" class="ml-auto">Request Deferral</v-btn>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="[payment.Payments]"
        class="elevation-1"
        hide-default-footer
      ></v-data-table>
    </v-card>
  </div>
</template>


<style scoped>
.semester-heading {
  text-align: center;
}
</style>


<script>
export default {
  data() {
    return {
      headers: [
        { text: 'PaymentId', value: 'PaymentId' },
        { text: 'Date', value: 'Date' },
        { text: 'Amount', value: 'Amount' },
      ],
      payments: [],
    }
  },
  //
  created() {
    axios
      .get('/api/view-student-payments')
      .then(response => {
        this.payments = response.data.result
        console.log('running courses', this.runnings)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
  },

  methods: {},
}
</script>

