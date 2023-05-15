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