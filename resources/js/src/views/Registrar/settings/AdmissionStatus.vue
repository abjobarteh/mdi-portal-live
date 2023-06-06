 <template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Admission Status</v-toolbar-title>
          <v-spacer></v-spacer>
        </v-toolbar>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="admissionStatus"
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
  name: 'admissionStatusystem',
  props: {},

  data() {
    return {
      admissionStatus: [],
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
        .get('/api/admission-status?page=' + this.page)
        .then(response => {
          this.admissionStatus = response.data.result
          this.setSwitchState() // Call the method to set the switch state

          console.log(this.admissionStatus)
        })
        .catch(err => {
          this.admissionStatus = []
        })
    },

    confirmSwitch(item) {
      const action = item.switchValue ? 'open' : 'close'
      swal
        .fire({
          title: `Are you sure you want to ${action} admission?`,
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
              this.openAdmission(item)
            } else {
              this.closeAdmission(item)
            }
          } else {
            // Revert the switch value if the action is cancelled
            item.switchValue = !item.switchValue
          }
        })
    },
    setSwitchState() {
      if (this.admissionStatus.length === 1) {
        const item = this.admissionStatus[0]
        console.log('item', item)
        if (item.admission_status == 1) {
          item.switchValue = true // Set switchValue to true for open admission
        } else if (item.admission_status == 0) {
          item.switchValue = false // Set switchValue to false for closed admission
        }
      }
    },
    openAdmission(item) {
      console.log(item.switchValue)
      // Perform the logic for opening admission here
      // You can make an API call or update the data locally

      // Example API call using axios:
      axios
        .post('/api/update-admission', { status: 'Open' })
        .then(response => {
          // Handle the API response if needed
          console.log(`Admission opened for ${item.status}`)
        })
        .catch(error => {
          // Handle any errors that occurred during the API call
          console.error('Failed to open admission:', error)
          // Revert the switch value if the action failed
          item.switchValue = !item.switchValue
        })

      // Example local data update:
      // Assuming 'admissionStatus' is an array of objects
      const index = this.admissionStatus.indexOf(item)
      if (index !== -1) {
        this.admissionStatus[index].status = 'Open'
        console.log(`Admission opened for ${item.status}`)
      } else {
        console.error('Failed to find item in admissionStatus')
        // Revert the switch value if the action failed
        item.switchValue = !item.switchValue
      }
    },

    closeAdmission(item) {
      // Perform the logic for closing admission here
      // You can make an API call or update the data locally

      // Example API call using axios:
      axios
        .post('/api/update-admission', { status: 'Close' })
        .then(response => {
          // Handle the API response if needed
          console.log(`Admission closed for ${item.status}`)
        })
        .catch(error => {
          // Handle any errors that occurred during the API call
          console.error('Failed to close admission:', error)
          // Revert the switch value if the action failed
          item.switchValue = !item.switchValue
        })

      // Example local data update:
      // Assuming 'admissionStatus' is an array of objects
      const index = this.admissionStatus.indexOf(item)
      if (index !== -1) {
        this.admissionStatus[index].status = 'Closed'
        console.log(`Admission closed for ${item.status}`)
      } else {
        console.error('Failed to find item in admissionStatus')
        // Revert the switch value if the action failed
        item.switchValue = !item.switchValue
      }
    },
  },
}
</script>

