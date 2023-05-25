<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Registration Status</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="registrationStatus"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.status`]="{ item }">
              <p>Status</p>
            </template>
            <template v-slot:[`item.action`]="{ item }">
              <v-switch v-model="item.switchValue" color="primary" @change="confirmSwitch(item)"></v-switch>
            </template>
          </v-data-table>
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>


<script>
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vue2Filters)

export default {
  name: 'registrationStatusystem',
  props: {},

  data() {
    return {
      registrationStatus: [],
      headers: [
        { text: 'Status', value: 'status' },
        { text: 'Action', value: 'action', sortable: false },
      ],
      search: '',
    }
  },

  created() {
    this.getResults()
  },

  methods: {
    getResults() {
      axios
        .get('/api/registration-status?page=' + this.page)
        .then(response => {
          this.registrationStatus.push(response.data.result)
          this.setSwitchState() // Call the method to set the switch state

          console.log(this.registrationStatus)
        })
        .catch(err => {
          this.registrationStatus = []
        })
    },

    confirmSwitch(item) {
      const action = item.switchValue ? 'open' : 'close'
      swal
        .fire({
          title: `Are you sure you want to ${action} Registration?`,
          icon: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes',
          cancelButtonText: 'No',
        })
        .then(result => {
          if (result.isConfirmed) {
            // Perform the action based on the switch value
            if (item.switchValue) {
              this.openRegistration(item)
            } else {
              this.closeRegistration(item)
            }
          } else {
            // Revert the switch value if the action is cancelled
            item.switchValue = !item.switchValue
          }
        })
    },
    setSwitchState() {
      if (this.registrationStatus.length === 1) {
        const item = this.registrationStatus[0]
        console.log('item', item)
        if (item.registration_status == 1) {
          item.switchValue = true // Set switchValue to true for open Registration
        } else if (item.registration_status == 0) {
          item.switchValue = false // Set switchValue to false for closed Registration
        }
      }
    },
    openRegistration(item) {
      console.log(item.switchValue)
      // Perform the logic for opening Registration here
      // You can make an API call or update the data locally

      // Example API call using axios:
      axios
        .post('/api/update-registration-status', { status: 'Open' })
        .then(response => {
          // Handle the API response if needed
          console.log(`Registration opened for ${item.status}`)
        })
        .catch(error => {
          // Handle any errors that occurred during the API call
          console.error('Failed to open Registration:', error)
          // Revert the switch value if the action failed
          item.switchValue = !item.switchValue
        })

      // Example local data update:
      // Assuming 'registrationStatus' is an array of objects
      const index = this.registrationStatus.indexOf(item)
      if (index !== -1) {
        this.registrationStatus[index].status = 'Open'
        console.log(`Registration opened for ${item.status}`)
      } else {
        console.error('Failed to find item in registrationStatus')
        // Revert the switch value if the action failed
        item.switchValue = !item.switchValue
      }
    },

    closeRegistration(item) {
      // Perform the logic for closing Registration here
      // You can make an API call or update the data locally

      // Example API call using axios:
      axios
        .post('/api/update-registration-status', { status: 'Close' })
        .then(response => {
          // Handle the API response if needed
          console.log(`Registration closed for ${item.status}`)
        })
        .catch(error => {
          // Handle any errors that occurred during the API call
          console.error('Failed to close Registration:', error)
          // Revert the switch value if the action failed
          item.switchValue = !item.switchValue
        })

      // Example local data update:
      // Assuming 'registrationStatus' is an array of objects
      const index = this.registrationStatus.indexOf(item)
      if (index !== -1) {
        this.registrationStatus[index].status = 'Closed'
        console.log(`Registration closed for ${item.status}`)
      } else {
        console.error('Failed to find item in registrationStatus')
        // Revert the switch value if the action failed
        item.switchValue = !item.switchValue
      }
    },
  },
}
</script>