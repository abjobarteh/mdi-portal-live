<template>
  <v-card style="background-color: #fcfbfe">
    <v-container class="my-5">
      <v-col cols="12" md="12" class="mb-4">
        <education-card :education="education"></education-card>
      </v-col>
      <v-col cols="12" md="12" class="mb-4">
        <certificate-card :certificates="certificates"></certificate-card>
      </v-col>
    </v-container>
  </v-card>
</template>

<script>
import EducationCard from '../../components/student/DeclarationAndPreview/EducationCard.vue'
import CertificateCard from '../../components/student/DeclarationAndPreview/CertificateCard.vue'
import 'vuetify/dist/vuetify.min.css'

export default {
  components: {
    EducationCard,
    CertificateCard,
  },
  data() {
    return {
      studentInfo: '',
      education: [],
      certificates: [],
    }
  },
  methods: {},

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      this.education = this.studentInfo.education
      this.certificates = this.studentInfo.certificates
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