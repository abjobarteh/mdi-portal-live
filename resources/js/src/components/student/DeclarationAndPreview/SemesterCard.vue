<template>
    <v-card>
      <v-card-title class="text-h5">Semester Info</v-card-title>
      <v-card-text>
        <v-simple-table>
          <template v-slot:default>
            <tbody v-for="item in studentProfile" :key="item.id">

              <tr>
                <td>Semester</td>
                <td>{{ item.semester_name }}</td>
              </tr>
            </tbody>
          </template>
        </v-simple-table>
      </v-card-text>
    </v-card>
  </template>
  
  <script>
  import apiBaseURL from '../../../../api-config'
  
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
        return apiBaseURL + filename
      },
    },
  }
  </script>
  