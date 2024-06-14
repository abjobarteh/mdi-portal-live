<template>
  <v-card>
    <v-card-title class="text-h5 d-flex justify-space-between align-center">
      Applicant Info
      <v-btn color="primary" @click="reverseApplication()">Revert Application</v-btn>
    </v-card-title>
    <v-card-text>
      <v-simple-table>
        <template v-slot:default>
          <tbody v-for="item in studentProfile" :key="item.id">
            <v-img max-width="170" :src="getImageUrl(item.profile_image)"></v-img>

            <tr>
              <td>Firstname</td>
              <td>{{ item.firstname }}</td>
            </tr>
            <tr>
              <td>Lastname</td>
              <td>{{ item.lastname }}</td>
            </tr>
            <tr>
              <td>Address</td>
              <td>{{ item.address }}</td>
            </tr>
            <tr>
              <td>Phonenumber</td>
              <td>{{ item.phonenumber }}</td>
            </tr>
            <tr>
              <td>Gender</td>
              <td>{{ item.gender }}</td>
            </tr>
            <tr>
              <td>DOB</td>
              <td>{{ item.dob }}</td>
            </tr>
            <tr>
              <td>Employment</td>
              <td>{{ item.employment_status }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>
  </v-card>
</template>

<script>
import apiBaseURL from '../../../../../api-config'

export default {
  name: 'Department',
  props: {
    studentProfile: {
      type: Array,
      required: true,
    },
  },

  methods: {
    getImageUrl(filename) {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + '/' + filename
    },
    reverseApplication() {
      console.log('studentId ', this.studentProfile[0])
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, revert it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios.post('/api/revert-student-application', { studentId: this.studentProfile[0].id }).then(result => {
              // show success alert
              swal
                .fire({
                  title: 'Success!',
                  text: 'Student application reverted successfully.',
                  icon: 'success',
                  confirmButtonText: 'OK',
                })
                .then(() => {
                  this.$router.go(-1)
                  this.getResults()
                })
            })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
        .catch(error => {
          // show error alert
          swal.fire({
            title: 'Error!',
            text: 'Failed to revert.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },
  },
}
</script>
<!-- <template>
  <v-card>
    <v-card-title class="text-h5 d-flex justify-space-between align-center">
      Applicant Info
      <v-btn color="primary" @click="reverseApplication(selectedItem)">Revert Application</v-btn>
    </v-card-title>
    <v-card-text>
      <v-simple-table>
        <template v-slot:default>
          <tbody>
            <tr v-for="item in studentProfile" :key="item.id">
              <td colspan="2">
                <v-img max-width="170" :src="getImageUrl(item.profile_image)"></v-img>
              </td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Firstname</td>
              <td>{{ item.firstname }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Lastname</td>
              <td>{{ item.lastname }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Address</td>
              <td>{{ item.address }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Phonenumber</td>
              <td>{{ item.phonenumber }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Gender</td>
              <td>{{ item.gender }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>DOB</td>
              <td>{{ item.dob }}</td>
            </tr>
            <tr v-for="item in studentProfile" :key="item.id">
              <td>Employment</td>
              <td>{{ item.employment_status }}</td>
            </tr>
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>
  </v-card>
</template>

<script>
import apiBaseURL from '../../../../../api-config'

export default {
  name: 'Department',
  props: {
    studentProfile: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      selectedItem: null,
    }
  },
  methods: {
    getImageUrl(filename) {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + '/' + filename
    },
    reverseApplication(item) {
      // Now you have access to the item here
      console.log(this.studentProfile)
    },
    selectItem(item) {
      this.selectedItem = item
    },
  },
}
</script> -->
