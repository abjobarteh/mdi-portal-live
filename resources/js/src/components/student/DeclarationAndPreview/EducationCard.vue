<!-- <template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Education</h3>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>School Name</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Certificate</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="edu in education" :key="edu.id">
            <td>{{ edu.school_name }}</td>
            <td>{{ edu.start_date }}</td>
            <td>{{ edu.end_date }}</td>
            <td>{{ edu.certificate }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: {
    education: {
      type: Array,
      required: true,
    },
  },
}
</script> -->

<template>
  <v-card>
    <v-card-title class="text-h5">Education</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="education"
        :hide-default-footer="true"
        :items-per-page="5"
        class="elevation-1"
      >
        <template v-slot:item.certificate="{ item }">
          <a :href="`/certificates/${item.certificate}`" target="_blank">{{ item.certificate }}</a>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  props: {
    education: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      headers: [
        { text: 'School Name', value: 'school_name' },
        { text: 'Start Date', value: 'start_date' },
        { text: 'End Date', value: 'end_date' },
        { text: 'Certificate', value: 'certificate' },
      ],
    }
  },
}
</script>