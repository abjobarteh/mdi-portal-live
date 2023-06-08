<template>
  <v-card>
    <v-form ref="form" @submit.prevent="submit">
      <v-container style="background-color: #fefcff">
        <v-card-title class="headline">Certificates</v-card-title>
        <v-card-text>
          <v-container v-for="(certificate, index) in certificateFormData.certificates" :key="index" class="py-3">
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
                <v-file-input
                  :label="'Certificate Obtained for Certificate ' + (index + 1)"
                  v-model="certificate.certificate"
                  outlined
                  @change="onChange($event, index)"
                ></v-file-input>
              </v-col>

              <v-col cols="12" sm="1">
                <button
                  :disabled="index == 0"
                  @click="removeCertificate(index)"
                  style="
                    background-color: red;
                    border-radius: 50%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 40px;
                    width: 40px;
                    margin-top: 10px;
                  "
                >
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
            <v-btn color="primary" class="col-12" @click="submit()">Save</v-btn>
          </v-card-actions>
        </v-card-text>
      </v-container>
    </v-form>
  </v-card>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      studentInfo: '',
      certificateFormData: {
        certificates: [
          {
            certificateName: '',
            certificate: null,
          },
        ],
      },
    }
  },
  methods: {
    addCertificate() {
      this.certificateFormData.certificates.push({
        certificateName: '',
        certificate: '',
      })
    },
    onChange(event, index) {
      console.log('good')
      const selectedFile = event
      this.certificateFormData.certificates[index].certificate = selectedFile
    },
    removeCertificate(index) {
      this.certificateFormData.certificates.splice(index, 1)
    },
    submit() {
      const formData = new FormData()
      this.certificateFormData.certificates.forEach((certificate, index) => {
        formData.append(`certificates[${index}][certificateName]`, certificate.certificateName)
        formData.append(`certificates[${index}][certificate]`, certificate.certificate, certificate.certificateName)
      })

      console.log(formData)

      axios
        .post('/api/add-applicant-certificates', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        })
        .then(result => {
          // show success alert
          this.addSessionDialog = false
          swal
            .fire({
              title: 'Success!',
              text: 'Academic Education added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.$router.push('/student')
              this.getResults()
            })
        })
        .catch(error => {
          // show error alert
          swal.fire({
            title: 'Error!',
            text: 'Failed to add session duration.',
            icon: 'error',
            confirmButtonText: 'OK',
          })
        })
    },
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
}
</script>