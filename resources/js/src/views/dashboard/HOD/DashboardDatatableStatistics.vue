<template>
    <div>
        <v-container fluid>
            <v-card>
                <div class="text-center">
                    <v-card-text>
                        <h2 class="mb-4 p-2">My Department Statistics</h2>
                        <v-col cols="12" md="3" class="pt-2">
        <v-select v-model="selectedSemester" :items="semesters" item-value="id" item-text="semester_name"
          label="Select Semester" outlined dense @change="onSemesterChange"></v-select>
      </v-col>
                        <v-data-table :headers="headers" :items="departmentStatistics" :items-per-page="5"
                            :search="search" class="elevation-1" hide-default-footer>
                        </v-data-table>

                        <v-pagination v-model="page" :length="pageCount" @input="getResults" />
                    </v-card-text>
                </div>
            </v-card>
        </v-container>
    </div>
</template>
<script>
import Vue from 'vue'
import Vue2Filters from 'vue2-filters'
import 'vuetify/dist/vuetify.min.css'

Vue.use(Vue2Filters)

export default {
    name: 'HodLecturers',
    props: {},
    components: {},
    data() {
        return {
            departmentStatistics: [],
            selectedSemester: null,
            semesters: [],
            headers: [
                { text: 'Department Name', value: 'department_name' },
                { text: 'Total Students', value: 'student_count' },
                { text: 'Male Students', value: 'male_student_count' },
                { text: 'Female Students', value: 'female_student_count' },
            ],
            page: 1,
            pageCount: 0,
            search: '',

            v$: null,
        }
    },

    created() {
        this.getResults()
    },

    methods: {
        getResults() {
            axios
                .get('/api/view-statistics?page=' + this.page)
                .then(response => {
                    this.departmentStatistics = response.data.departmentCount.map(department => {
                        const maleCount = response.data.maledepartmentCount.find(
                            m => m.department_name === department.department_name
                        )?.male_student_count || 0;

                        const femaleCount = response.data.femaledepartmentCount.find(
                            f => f.department_name === department.department_name
                        )?.female_student_count || 0;
                        this.semesters = response.data.semester;
                        return {
                            department_name: department.department_name,
                            student_count: department.student_count,
                            male_student_count: maleCount,
                            female_student_count: femaleCount,
                        };
                    });
                })
                .catch(err => {
                    this.lecturers = []
                    this.pageCount = 0
                })
        },
        onSemesterChange(){
            axios
                .get(`api/get-hod-depts-stats/${this.selectedSemester}`)
                .then(response => {
                    this.departmentStatistics = response.data.departmentCount.map(department => {
                        const maleCount = response.data.maledepartmentCount.find(
                            m => m.department_name === department.department_name
                        )?.male_student_count || 0;

                        const femaleCount = response.data.femaledepartmentCount.find(
                            f => f.department_name === department.department_name
                        )?.female_student_count || 0;
                        this.semesters = response.data.semester;
                        return {
                            department_name: department.department_name,
                            student_count: department.student_count,
                            male_student_count: maleCount,
                            female_student_count: femaleCount,
                        };
                    });
                })
                .catch(err => {
                    this.lecturers = []
                    this.pageCount = 0
                })
        }
    },
}
</script>











s