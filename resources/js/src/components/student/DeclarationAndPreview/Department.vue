<template>
  <v-card>
    <v-card-title class="text-h5">Department Applied</v-card-title>
    <v-card-text>
      <v-data-table
        :headers="headers"
        :hide-default-footer="true"
        :items-per-page="5"
        class="elevation-1"
        :items="[department]"
      >
        <template v-slot:item="{ item }">
          <tr>
            <td>{{ item }}</td>
          </tr>
        </template>
      </v-data-table>
    </v-card-text>
  </v-card>
</template>

<script>
export default {
  name: 'Department',
  props: {
    department: {
      type: String,
      required: true,
    },
  },
  data() {
    return {
      headers: [{ text: 'Department', value: 'department' }],
    }
  },

  mounted() {
    console.log('departments ', this.department)
  },
}
</script>