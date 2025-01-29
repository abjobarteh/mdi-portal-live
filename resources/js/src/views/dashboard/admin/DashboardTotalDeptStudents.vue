<template>
    <v-card>
        <div class="text-center">
            <v-data-table :headers="headers" :items="counts" item-key="name" class="table-rounded" hide-default-footer
                disable-sort>
                <!-- Department Name -->
                <template v-slot:item.name="{ item }">
                    <div class="d-flex flex-column">
                        <span class="d-block font-weight-semibold text--primary text-truncate">
                            {{ item.name }}
                        </span>
                    </div>
                </template>

                <!-- Student Count -->
                <template v-slot:item.student_count="{ item }">
                    {{ item.student_count }}
                </template>

                <!-- No Data Fallback -->
                <template v-slot:no-data>
                    <span>No department data available.</span>
                </template>
            </v-data-table>
        </div>
    </v-card>
</template>


<script>
import axios from 'axios';

export default {
    data() {
        return {
            counts: [],
            headers: [
                { text: 'Department', value: 'name' },
                { text: 'Total Number Of Students', value: 'student_count' },
            ],
        };
    },
    methods: {
        fetchStatusCounts() {
            axios
                .get('/api/deptcount', {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}` // Ensure you have a valid token stored
                    }
                })
                .then((response) => {
                    this.counts = response.data.result;
                    console.log('Counts:', this.counts);
                })
                .catch((error) => {
                    console.error('Error fetching status counts:', error);
                });
        },
    },
    created() {
        this.fetchStatusCounts();
    },
};
</script>