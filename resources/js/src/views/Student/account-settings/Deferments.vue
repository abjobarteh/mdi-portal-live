<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-card-title class="text-center d-flex justify-between">
          <v-btn @click="showAddDeferment" color="success" class="ml-auto">Request Deferral</v-btn>
        </v-card-title>

        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="deferments"
            :items-per-page="13"
            :search="search"
            class="elevation-1"
            hide-default-footer
          >
            <template v-slot:[`item.deferment_reason`]="{ item }">
              <div style="white-space: normal; max-width: 50%">{{ item.deferment_reason }}</div>
            </template>
            <template v-slot:[`item.is_approved`]="{ item }">
              <v-btn v-if="item.is_approved == 1" color="green" class="ml-auto">Approved</v-btn>
              <v-btn v-else color="red" class="ml-auto">Pending</v-btn>
            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>
    <v-dialog v-model="addAddDefermentDialog" max-width="700px">
      <v-card>
        <v-card-title>Request Deferment</v-card-title>
        <v-card-text>
          <v-form ref="addAdmissionCodeLocationForm">
            <v-select
              outlined
              v-model="addDefermentFormData.semester_id"
              :items="
                semesters.map(semester => ({
                  id: semester.id,
                  name:
                    semester.semester_name +
                    '(' +
                    semester.session.session_start.split(' ')[0] +
                    '-' +
                    semester.session.session_end +
                    ')',
                }))
              "
              item-value="id"
              item-text="name"
              label="Semester Name"
            ></v-select>
            <v-textarea
              v-model="addDefermentFormData.deferment_reason"
              label="Reason for deferment"
              :rows="rowsCount"
              outlined
              counter
              maxlength="150"
            ></v-textarea>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="submitaddDefermentForm">Add</v-btn>
          <v-btn color="secondary" @click="addAddDefermentDialog = false">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
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
        { text: 'Semester', value: 'semester.semester_name' },
        { text: 'Reason', value: 'deferment_reason' },
        { text: 'Status', value: 'is_approved' },
      ],
      deferments: [],
      semesters: [],
      addAddDefermentDialog: false,
      addDefermentFormData: {
        semester_id: '',
        deferment_reason: '',
      },
    }
  },

  computed: {
    rowsCount() {
      // You can adjust this logic based on your styling and content
      const words = this.addDefermentFormData.deferment_reason.split(/\s+/).length
      return Math.max(4, Math.ceil(words / 10)) // Assume 10 words per row
    },
  },
  //
  created() {
    this.getResults()
  },

  methods: {
    showAddDeferment() {
      this.addAddDefermentDialog = true
    },

    submitaddDefermentForm() {
      axios
        .post('/api/add-deferment', this.addDefermentFormData)
        .then(result => {
          // show success alert
          this.addAddDefermentDialog = false
          this.getResults()

          swal.fire({
            title: 'Success!',
            text: 'deferment successfully.',
            icon: 'success',
            confirmButtonText: 'OK',
          })
        })
        .catch(error => {
          // show error alert
          swal.fire({
            title: 'Error!',
            text: error.response.data.error,
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },

    getResults() {
      axios
        .get('/api/deferments')
        .then(response => {
          this.deferments = response.data.result.data
          console.log('', this.deferments)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.runnings = []
          this.pageCount = 0
        })

      axios
        .get('/api/view-current-semesters?page=' + this.page)
        .then(response => {
          this.semesters = response.data.result.data
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.semesters = []
          this.pageCount = 0
        })
    },
  },
}
</script>


