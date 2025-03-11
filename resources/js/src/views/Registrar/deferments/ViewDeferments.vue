<template>
  <div>
    <v-container fluid>
      <v-card>
        <v-toolbar color="primary" dark>
          <v-toolbar-title>Deferments</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-text-field v-model="search" label="Search" append-icon="mdi-magnify" clearable hide-details></v-text-field>
        </v-toolbar>
        <v-card-text>
          <v-data-table :headers="headers" :items="deferments" :items-per-page="13" :search="search" class="elevation-1"
            hide-default-footer>
            <template v-slot:[`item.is_approved`]="{ item }">

              <v-btn v-if="item.is_approved === 0" small color="success" @click="approveDeferment(item)">
                Approve
              </v-btn>

              <v-btn v-else-if="item.is_approved === 1" small color="primary" @click="reinstateDeferment(item)">
                Reinstate
              </v-btn>

            </template>
          </v-data-table>
          <v-pagination v-model="page" :length="pageCount" @input="getResults" />
        </v-card-text>
      </v-card>
    </v-container>
  </div>
</template>


<style scoped>
.semester-heading {
  text-align: center;
}
</style>


<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      headers: [
        { text: 'Student', value: 'fullname' },
        { text: 'Mat Number', value: 'mat_number' },
        { text: 'Program', value: 'name' },
        { text: 'Semester', value: 'semester_name' },
        { text: 'Reason', value: 'deferment_reason' },
        { text: 'Status', value: 'is_approved' },
      ],
      deferments: [],
      page: 1,
      pageCount: 0,
      search: '',
    }
  },
  //
  created() {
    this.getResults()
  },

  methods: {
    approveDeferment(item) {
      swal
        .fire({
          title: 'Are you sure?',
          text: 'Do you want to approve this deferement',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!',
        })
        .then(result => {
          if (result.isConfirmed) {

            console.log('Data: ', item.student_id)
            axios
              .post('/api/approve-deferment/' + item.student_id)
              .then(result => {

                // show success alert
                swal.fire({
                  title: 'Success!',
                  text: 'deferment updated successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
              })
              .then(res => {
                this.getResults()
              })
              .catch(error => {
                // show error alert
                swal.fire({
                  title: 'Error!',
                  text: 'Failed to add deferment.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
          }
        })
    },


    reinstateDeferment(item){
      swal
        .fire({
          title: 'Are you sure?',
          text: 'Do you want to Reinstate Student',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes!',
        })
        .then(result => {
          if (result.isConfirmed) {

            console.log('Data: ', item.student_id)
            axios
              .post('/api/reinstate/' + item.student_id)
              .then(result => {

                // show success alert
                swal.fire({
                  title: 'Success!',
                  text: 'Reinstated successfully And New Acceptance Email Sent To Student.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
              })
              .then(res => {
                this.getResults()
              })
              .catch(error => {
                // show error alert
                swal.fire({
                  title: 'Error!',
                  text: 'Failed to Reinstate.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
          }
        })
    },
    getResults() {
      axios
        .get('/api/registrar-deferments')
        .then(response => {
          this.deferments = response.data.result.data
          console.log('data: ', this.deferments)
          this.pageCount = response.data.result.last_page
        })
        .catch(err => {
          this.runnings = []
          this.pageCount = 0
        })
    },
  },
}
</script>
