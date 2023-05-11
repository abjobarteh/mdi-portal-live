<!-- <template>
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Certificates</h3>
    </div>
    <div class="card-body">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Certificate Name</th>
            <th>Certificate</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="cert in certificates" :key="cert.id">
            <td>{{ cert.certificate_name }}</td>
            <td>
              <a :href="`/certificates/${cert.certificate}`" target="_blank">{{ cert.certificate }}</a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
  
  <script>
export default {
  props: {
    certificates: {
      type: Array,
      required: true,
    },
  },
}
</script> -->

<template>
  <v-card>
    <v-card-title>Certificates</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :items="certificates"
        :hide-default-footer="true"
        :items-per-page="5"
        class="elevation-1"
      >
        <template v-slot:item.certificate="{ item }">
          <v-btn class="ma-2" color="success" :href="`/certificates/${item.certificate}`" target="_blank" small dark
            >View</v-btn
          >
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

  <script>
export default {
  props: {
    certificates: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      headers: [
        { text: 'Certificate Name', value: 'certificate_name' },
        { text: 'Certificate', value: 'certificate', sortable: false },
      ],
    }
  },
}
</script>