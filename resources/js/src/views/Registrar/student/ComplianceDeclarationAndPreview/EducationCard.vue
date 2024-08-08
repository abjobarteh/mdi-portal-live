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
          <p style="color: rgb(105, 105, 245); font-weight: bold">{{ item.certificate }}</p>
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