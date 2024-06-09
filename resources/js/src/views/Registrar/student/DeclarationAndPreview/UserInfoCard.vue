<template>
  <v-card>
    <v-card-title class="text-h5">Applicant Info</v-card-title>
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
  },
}
</script>
