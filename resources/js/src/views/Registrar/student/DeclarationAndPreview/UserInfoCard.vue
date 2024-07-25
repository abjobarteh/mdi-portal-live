<template>
  <div>
    <v-card>
      <v-card-title class="text-h5 d-flex justify-space-between align-center">
        Applicant Info
        <v-btn color="primary" @click="reverseApplication()" small>Revert Application</v-btn>
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
                <td>Middlename</td>
                <td>{{ item.middlename }}</td>
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
    <!-- loading spinner -->
    <div v-if="isLoading" class="spinner">
      <div class="double-bounce1"></div>
      <div class="double-bounce2"></div>
    </div>
  </div>
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
      isLoading: false,
    }
  },

  methods: {
    getImageUrl(filename) {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + '/' + filename
    },
    // reverseApplication() {
    //   console.log('studentId ', this.studentProfile[0])
    //   swal
    //     .fire({
    //       title: 'Are you sure?',
    //       text: "You won't be able to revert this!",
    //       icon: 'warning',
    //       showCancelButton: true,
    //       confirmButtonColor: '#3085d6',
    //       cancelButtonColor: '#d33',
    //       confirmButtonText: 'Yes, revert it!',
    //     })
    //     .then(result => {
    //       if (result.isConfirmed) {
    //         axios.post('/api/revert-student-application', { studentId: this.studentProfile[0].id }).then(result => {
    //           // show success alert
    //           swal
    //             .fire({
    //               title: 'Success!',
    //               text: 'Student application reverted successfully.',
    //               icon: 'success',
    //               confirmButtonText: 'OK',
    //             })
    //             .then(() => {
    //               this.$router.go(-1)
    //               this.getResults()
    //             })
    //         })
    //         // swal.fire('Deleted!', 'Department has been deleted.', 'success')
    //       }
    //     })
    //     .catch(error => {
    //       // show error alert
    //       swal.fire({
    //         title: 'Error!',
    //         text: 'Failed to revert.',
    //         icon: 'error',
    //         confirmButtonText: 'OK',
    //       })
    //     })
    // },

    reverseApplication() {
      swal
        .fire({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Revert!',
          input: 'textarea', // Adding textarea input field
          inputPlaceholder: 'Enter your message here...(optional)', // Placeholder for the textarea
          inputAttributes: {
            'aria-label': 'Type your message here', // Accessibility label
          },
        })
        .then(result => {
          if (result.isConfirmed) {
            this.isLoading = true
            let message = result.value // Getting the value entered in the textarea

            axios
              .post('/api/revert-student-application', {
                studentId: this.studentProfile[0].id,
                message: message, // Sending the message along with studentId
              })
              .then(response => {
                this.isLoading = false
                // Show success alert after successful revert
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
              .catch(error => {
                this.isLoading = false
                // Show error alert if revert fails
                swal.fire({
                  title: 'Error!',
                  text: 'Failed to revert.',
                  icon: 'error',
                  confirmButtonText: 'OK',
                })
              })
          }
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
<style lang="scss">
.spinner {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  margin: auto;
  width: 40px;
  height: 40px;
  z-index: 9999;
}

.double-bounce1,
.double-bounce2 {
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #333;
  opacity: 0.6;
  position: absolute;
  top: 0;
  left: 0;
  animation: sk-bounce 2s infinite ease-in-out;
}

.double-bounce2 {
  animation-delay: -1s;
}

@keyframes sk-bounce {
  0%,
  100% {
    transform: scale(0);
  }
  50% {
    transform: scale(1);
  }
}
</style>
