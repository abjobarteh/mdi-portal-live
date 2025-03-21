<template>
  <v-card>
    <v-form ref="form" @submit.prevent="submit">
      <v-container style="background-color: #fefcff">
        <v-card-title class="headline">New Program Applying For:</v-card-title>
        <v-card-text>
          <v-select outlined v-model="addApplicantProgramFormData.program_id"
            :items="programs.map(program => ({ text: program.name, value: program.id }))" item-value="value"
            item-text="text" label="Program"></v-select>

          <v-select outlined v-model="addApplicantProgramFormData.semester_name"
            :items="semesters.map(semester => ({ text: semester.semester_name, value: semester.id }))" item-value="text"
            item-text="text" label="Semester"></v-select>

          <v-card-title class="headline">Certificates</v-card-title>
          <v-card-title class="headline">All Uploaded Documents Should Be In PDF Format</v-card-title>

          <v-container v-for="(certificate, index) in addApplicantProgramFormData.certificates" :key="index"
            class="py-3">
            <v-row>
              <v-col cols="12" sm="2">
                <h3 class="headline">{{ `Certificate ${index + 1}` }}</h3>
              </v-col>
              <v-col cols="12" sm="8">
                <v-spacer></v-spacer>
              </v-col>
              <v-col cols="12" sm="2" v-if="index === 0">
                <v-btn color="primary" @click="addCertificate">Add Certificate</v-btn>
              </v-col>

              <v-col cols="12" sm="6">
                <v-text-field outlined v-model="certificate.certificateName" label="Certificate Name"></v-text-field>
              </v-col>

              <v-col cols="12" sm="5">
                <v-file-input :label="'Certificate Obtained for Certificate ' + (index + 1)" accept=".pdf" outlined
                  @change="onChange($event, index)"></v-file-input>
              </v-col>


              <v-col cols="12" sm="1">
                <button :disabled="index == 0" @click="removeCertificate(index)" style="
                    background-color: red;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 40px;
                    width: 40px;
                    margin-top: 10px;
                  ">
                  <fas icon="times" style="color: white; font-size: 24px"></fas>
                </button>
              </v-col>
            </v-row>

            <v-row>
              <v-col cols="12">
                <v-divider></v-divider>
              </v-col>
            </v-row>
          </v-container>

          <v-card-actions class="d-flex justify-center">
            <v-btn color="primary" class="col-12" type="submit">Submit New Application</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-container>
    </v-form>
  </v-card>
</template>

<style scoped>
.delete-button {
  background-color: red;
  border-radius: 50%;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 40px;
  width: 40px;
  margin-top: 10px;
}
</style>

<script>
export default {
  created() {
    axios
      .get('/api/view-students-programs')
      .then(response => {
        this.programs = response.data.result.data

      })
      .catch(err => {
        this.departments = []

      })

    axios
      .get('/api/view-semester')
      .then(response => {
        console.log(response.data);  // Log the full response to inspect the structure
        this.semesters = response.data.result;  // Access result

      })
      .catch(err => {
        console.error(err);  // Log the error to see if there's an issue with the request
        this.semesters = [];
        this.pageCount = 0;
      })
  },
  data() {
    return {
      programs: [],
      semesters: [],
      addApplicantProgramFormData: {
        program_id: '',
        semester_name: '',
        course_level: '',
        certificates: [
          {
            certificateName: '',
            certificate: null,
          },
        ],
      },
      courseLevelOptions: [
        'Diploma',
        'Higher National Diploma',
        'Diploma 1 or 2',
        'Advanced Diploma',
        'Graduate Diploma',
        'Postgraduate Diploma',
      ],
    };
  },
  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  computed: {
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },
  methods: {

    addCertificate() {
      this.addApplicantProgramFormData.certificates.push({
        certificateName: '',
        certificate: null,
      });
    },
    onChange(event, index) {

      const selectedFile = event
      this.addApplicantProgramFormData.certificates[index].certificate = selectedFile;

    },
    removeCertificate(index) {
      this.addApplicantProgramFormData.certificates.splice(index, 1);
    },
    submit() {
      swal.fire({
        title: "Are you sure you want to submit the application?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, submit it!",
      }).then((result) => {
        if (result.isConfirmed) {
          console.log("Submitting form:", this.addApplicantProgramFormData);

          let formData = new FormData();
          formData.append("program_id", this.addApplicantProgramFormData.program_id);
          formData.append("semester_name", this.addApplicantProgramFormData.semester_name);

          this.addApplicantProgramFormData.certificates.forEach((cert, index) => {
            formData.append(`certificates[${index}][certificateName]`, cert.certificateName);
            formData.append(`certificates[${index}][certificate]`, cert.certificate);
          });

          axios
            .post("/api/submit-new-application", formData, {
              headers: {
                "Content-Type": "multipart/form-data",
              },
            })
            .then((response) => {
              console.log("Response:", response.data);

              swal.fire({
                title: "Submitted!",
                text: "Your application has been submitted successfully.",
                icon: "success",
                confirmButtonColor: "#3085d6",
              }).then(() => {
                this.$store.dispatch('userProfile')
              })
            })
            .catch((error) => {
              console.error("Error submitting application:", error.response ? error.response.data : error);

              swal.fire({
                title: "Error!",
                text: error.response.data.errors,
                icon: "error",
                confirmButtonColor: "#d33",
              });
            });
        }
      });
      // Handle form submission logic here (e.g., API call)
    },
  },
};
</script>