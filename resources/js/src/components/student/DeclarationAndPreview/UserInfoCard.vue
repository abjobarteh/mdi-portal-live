<template>
  <v-card>
    <v-card-title class="text-h5">Applicant Info</v-card-title>
    <v-card-text>
      <v-simple-table>
        <template v-slot:default>
          <tbody v-for="item in studentProfile" :key="item.id">
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
          </tbody>
        </template>
      </v-simple-table>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'Department',
  props: {
    studentProfile: {
      type: Array,
      required: true,
    },
  },
}
</script>