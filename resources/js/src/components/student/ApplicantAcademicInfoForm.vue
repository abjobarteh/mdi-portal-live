<template>
  <v-card>
    <v-form v-model="valid" ref="form" @submit.prevent="submit">
      <v-container style="background-color: #fefcff">
        <v-card-title class="headline">School Information</v-card-title>
        <v-card-text>
          <v-container v-for="(school, index) in educationFormData.schools" :key="index" class="py-3">
            <v-row>
              <v-col cols="12" sm="2">
                <h3 class="headline">{{ `School ${index + 1}` }}</h3>
              </v-col>
              <v-col cols="12" sm="8">
                <v-spacer></v-spacer>
              </v-col>
              <v-col cols="12" sm="2" v-if="index === 0">
                <v-btn color="primary" @click="addSchool">Add School</v-btn>
              </v-col>

              <v-col cols="12" md="6">
                <v-text-field
                  v-model="school.name"
                  :label="'School ' + (index + 1) + ' Name *'"
                  outlined
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <v-text-field outlined v-model="school.startDate" label="Start Date" type="month"></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field outlined v-model="school.endDate" label="End Date" type="month"></v-text-field>
              </v-col>
              <v-col cols="12" sm="5">
                <v-text-field
                  v-model="school.certificate"
                  :label="'Certificate Obtained for School ' + (index + 1)"
                  outlined
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="1">
                <!-- <v-btn color="red" @click="removeSchool(index)">X</v-btn> -->
                <button
                  :disabled="index == 0"
                  @click="removeSchool(index)"
                  style="
                    background-color: red;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 40px;
                    width: 40px;
                    margin-top: 10px;
                  "
                >
                  <fas icon="times" style="color: white; font-size: 24px"></fas>
                </button>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-divider></v-divider>
              </v-col>
            </v-row>
          </v-container>
          <!-- <v-row>
            <v-col cols="12">
              <v-btn color="primary" @click="addSchool">Add School</v-btn>
            </v-col>
          </v-row> -->
          <v-card-actions class="d-flex justify-center">
            <v-btn color="primary" class="col-12" @click="submit()">Save</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      studentInfo: '',
      educationFormData: {
        schools: [
          {
            name: '',
            startDate: '',
            endDate: '',
            certificate: '',
          },
        ],
      },
    }
  },
  methods: {
    addSchool() {
      this.educationFormData.schools.push({
        name: '',
        startDate: '',
        endDate: '',
        certificate: '',
      })
    },
    removeSchool(index) {
      this.educationFormData.schools.splice(index, 1)
    },
    submit() {
      // Implement form submission logic here
      console.log('good', this.schools)
      axios
        .post('/api/add-applicant-education', this.educationFormData)
        .then(result => {
          // show success alert
          this.addSessionDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Academic Education added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.$router.push('/student')
              this.getResults()
            })
        })
        .catch(error => {
          // show error alert
          swal.fire({
            title: 'Error!',
            text: 'Failed to add school Info.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  computed: {
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },
}
</script>
