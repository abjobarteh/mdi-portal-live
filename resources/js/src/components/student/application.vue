<template>
  <div class="container">
    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>Applicant Information</v-card-title>
          <v-card-text>
            <v-simple-table>
              <template v-slot:default>
                <tbody>
                  <tr>
                    <td>General Registration ID</td>
                    <td>{{ profile.regId }}</td>
                  </tr>
                  <tr>
                    <td>Email</td>
                    <td>{{ profile.email }}</td>
                  </tr>
                  <tr>
                    <td>Phone #</td>
                    <td>{{ profile.phone }}</td>
                  </tr>
                  <tr>
                    <td>Address</td>
                    <td>{{ profile.address }}</td>
                  </tr>
                  <tr>
                    <td>Status</td>
                    <td>{{ profile.status }}</td>
                  </tr>
                  <tr>
                    <td>Semester Applied For</td>
                    <td>{{ profile.semester_name }}</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12">
        <v-card>
          <v-card-title>Please click on the section you are left with to complete </v-card-title>
          <v-card-text>
            <v-simple-table>
              <template v-slot:default>
                <thead>
                  <tr>
                    <th>Section</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody v-if="studentInfo.apply_new_course == 1">
                  <tr>
                    <td><router-link to="/applicant-academic-info">Academic Information</router-link></td>
                    <td
                      v-if="studentInfo.education.length !== 0"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-personal-info">Personal Information</router-link></td>
                    <td
                      v-if="studentInfo.personal_info_completed == 1"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-applied-department">Course Details</router-link></td>
                    <td
                      v-if="studentInfo.program_name != null"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-certificate-info">Upload Documents</router-link></td>
                    <td
                      v-if="studentInfo.certificates.length !== 0"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-declaration">Declaration and Preview</router-link></td>
                    <td style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <tr>
                    <td><router-link to="/applicant-academic-info">Academic Information</router-link></td>
                    <td
                      v-if="studentInfo.education.length !== 0"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-personal-info">Personal Information</router-link></td>
                    <td
                      v-if="studentInfo.personal_info_completed == 1"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-applied-department">Course Details</router-link></td>
                    <td
                      v-if="studentInfo.program_name != null"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-certificate-info">Upload Documents</router-link></td>
                    <td
                      v-if="studentInfo.certificates.length !== 0"
                      style="background-color: rgb(125, 209, 125); border-top: 5px solid white"
                    >
                      Completed
                    </td>
                    <td v-else style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                  <tr>
                    <td><router-link to="/applicant-declaration">Declaration and Preview</router-link></td>
                    <td style="background-color: rgb(218, 115, 115); border-top: 5px solid white">Incomplete</td>
                  </tr>
                </tbody>
              </template>
            </v-simple-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      studentInfo: '',
    }
  },
  props: {
    progress: {
      type: Object,
      required: true,
    },
    profile: {
      type: Object,
      required: true,
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      console.log('info student', this.studentInfo.education.length)
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

