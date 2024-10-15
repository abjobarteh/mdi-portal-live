<template>
  <v-form ref="form" @submit.prevent="submit">
    <v-container style="background-color: #fefcff">
      <v-card class="pa-6">
        <v-card-title>
          <span class="headline font-weight-medium">PERSONAL INFORMATION</span>
          <strong><span class="headline font-weight-medium" style="font-size: 15px;">  Upload Passport Size  (Any Other Photo Form Shall Result To Automatic Rejection) </span></strong>  
        </v-card-title>

        <v-row>
          <v-col class="pr-2">
            <!-- File Input for Image Upload -->
            <v-file-input
              v-model="applicantPersonalInfoData.profile_image"
              @change="handleImageUpload"
            ></v-file-input>
          </v-col>

          <v-col class="text-right pl-2">
            <!-- Image Preview -->
            <v-img v-if="previewImage" :src="previewImage" alt="Profile Image" width="150" height="150"></v-img>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field
              outlined
              v-model="applicantPersonalInfoData.middlename"
              label="Middle Name"
              required
            ></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.gender"
              :items="applicantPersonalInfoData.genderOptions"
              required
            >
              <template v-slot:label>
                <span class="required-field">Gender</span>
              </template>
            </v-select>
            <span v-if="errors.gender" class="error-message">{{ errors.gender[0] }}</span>
          </v-col>
        </v-row>

        <v-row>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.marital_status"
              :items="applicantPersonalInfoData.maritalStatusOptions"
              required
              ><template v-slot:label>
                <span class="required-field">Marital Status</span>
              </template></v-select
            >
            <span v-if="errors.marital_status" class="error-message">{{ errors.marital_status[0] }}</span>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined label="Date of Birth *" type="date" required v-model="applicantPersonalInfoData.dob">
              <template v-slot:label>
                <span class="required-field">Date of Birth</span>
              </template></v-text-field
            >
            <span v-if="errors.dob" class="error-message">{{ errors.dob[0] }}</span>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <!-- <v-text-field outlined v-model="applicantPersonalInfoData.nationality" required>
              <template v-slot:label>
                <span class="required-field">Nationality</span>
              </template></v-text-field
            > -->
            <v-select
              outlined
              v-model="applicantPersonalInfoData.nationality"
              :items="applicantPersonalInfoData.nationalityOptions"
              required
              ><template v-slot:label>
                <span class="required-field">Nationality</span>
              </template></v-select
            >
            <span v-if="errors.nationality" class="error-message">{{ errors.nationality[0] }}</span>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="applicantPersonalInfoData.address" required>
              <template v-slot:label>
                <span class="required-field">Address</span>
              </template></v-text-field
            >
            <span v-if="errors.address" class="error-message">{{ errors.address[0] }}</span>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="applicantPersonalInfoData.phonenumber" required
              ><template v-slot:label>
                <span class="required-field">PhoneNumber</span>
              </template></v-text-field
            >
            <span v-if="errors.phonenumber" class="error-message">{{ errors.phonenumber[0] }}</span>
          </v-col>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="applicantPersonalInfoData.employment_status"
              :items="applicantPersonalInfoData.employmentStatusOptions"
             
              required
            >
              <template v-slot:label>
                <span class="required-field">Employment Status</span>
              </template></v-select
            >
            <span v-if="errors.employment_status" class="error-message">{{ errors.employment_status[0] }}</span>
          </v-col>
        
        
          
        </v-row>
        <v-row v-if="applicantPersonalInfoData.employment_status === 'Employed'">
          <v-col cols="12" md="6" >
            <v-text-field
           v-if="applicantPersonalInfoData.employment_status === 'Employed'"
              outlined
              v-model="applicantPersonalInfoData.employeename"
              label="Employee Name"
              required
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
            v-if="applicantPersonalInfoData.employment_status === 'Employed'"
            
              outlined
              v-model="applicantPersonalInfoData.employeeadresss"
              label="Employee Address"
              required
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="6">
            <v-text-field
              v-if="applicantPersonalInfoData.employment_status === 'Employed'"
              outlined
              v-model="applicantPersonalInfoData.employeecontact"
              label="Employee Contact Number"
              required
            ></v-text-field>
          </v-col>
        </v-row>
          
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="applicantPersonalInfoData.EmergencyContactName" required
              ><template v-slot:label>
                <span class="required-field">Emergency Contact Name</span>
              </template></v-text-field
            >
            <span v-if="errors.EmergencyContactName" class="error-message">{{ errors.EmergencyContactName[0] }}</span>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="applicantPersonalInfoData.EmergencyContactNumber" required
              ><template v-slot:label>
                <span class="required-field">Emergency Contact Number</span>
              </template></v-text-field
            >
            <span v-if="errors.EmergencyContactNumber" class="error-message">{{ errors.EmergencyContactNumber[0] }}</span>
          </v-col>
        </v-row>
       
        <v-card-actions class="d-flex justify-center">
          <v-btn color="primary" class="col-12" @click="submitForm()">Save</v-btn>
        </v-card-actions>
      </v-card>
    </v-container>
  </v-form>
</template>

  <script>
import 'vuetify/dist/vuetify.min.css'

export default {
  data() {
    return {
      previewImage: null, // Store the URL for previewing the image
      studentInfo: '',
      showemployee: false,
      applicantPersonalInfoData: {
        middlename: '',
        employeename:'',
        employeeadresss:'',
        employeecontact:'',
        phonenumber: '',
        gender: '',
        genderOptions: ['Male', 'Female', 'Other'],
        marital_status: '',
        maritalStatusOptions: ['Single', 'Married', 'Divorced', 'Widowed'],
        dob: '',
        nationality: '',

        nationalityOptions: [
          "Gambia",
    "Afghanistan",
    "Albania",
    "Algeria",
    "Andorra",
    "Angola",
    "Antigua and Barbuda",
    "Argentina",
    "Armenia",
    "Australia",
    "Austria",
    "Azerbaijan",
    "Bahamas",
    "Bahrain",
    "Bangladesh",
    "Barbados",
    "Belarus",
    "Belgium",
    "Belize",
    "Benin",
    "Bhutan",
    "Bolivia",
    "Bosnia and Herzegovina",
    "Botswana",
    "Brazil",
    "Brunei",
    "Bulgaria",
    "Burkina Faso",
    "Burundi",
    "Cabo Verde",
    "Cambodia",
    "Cameroon",
    "Canada",
    "Central African Republic",
    "Chad",
    "Chile",
    "China",
    "Colombia",
    "Comoros",
    "Congo",
    "Congo, Democratic Republic of the",
    "Costa Rica",
    "Croatia",
    "Cuba",
    "Cyprus",
    "Czech Republic",
    "Denmark",
    "Djibouti",
    "Dominica",
    "Dominican Republic",
    "East Timor",
    "Ecuador",
    "Egypt",
    "El Salvador",
    "Equatorial Guinea",
    "Eritrea",
    "Estonia",
    "Eswatini",
    "Ethiopia",
    "Fiji",
    "Finland",
    "France",
    "Gabon",
    "Gambia",
    "Georgia",
    "Germany",
    "Ghana",
    "Greece",
    "Grenada",
    "Guatemala",
    "Guinea",
    "Guinea-Bissau",
    "Guyana",
    "Haiti",
    "Honduras",
    "Hungary",
    "Iceland",
    "India",
    "Indonesia",
    "Iran",
    "Iraq",
    "Ireland",
    "Israel",
    "Italy",
    "Jamaica",
    "Japan",
    "Jordan",
    "Kazakhstan",
    "Kenya",
    "Kiribati",
    "Korea, North",
    "Korea, South",
    "Kosovo",
    "Kuwait",
    "Kyrgyzstan",
    "Laos",
    "Latvia",
    "Lebanon",
    "Lesotho",
    "Liberia",
    "Libya",
    "Liechtenstein",
    "Lithuania",
    "Luxembourg",
    "Madagascar",
    "Malawi",
    "Malaysia",
    "Maldives",
    "Mali",
    "Malta",
    "Marshall Islands",
    "Mauritania",
    "Mauritius",
    "Mexico",
    "Micronesia",
    "Moldova",
    "Monaco",
    "Mongolia",
    "Montenegro",
    "Morocco",
    "Mozambique",
    "Myanmar",
    "Namibia",
    "Nauru",
    "Nepal",
    "Netherlands",
    "New Zealand",
    "Nicaragua",
    "Niger",
    "Nigeria",
    "North Macedonia",
    "Norway",
    "Oman",
    "Pakistan",
    "Palau",
    "Panama",
    "Papua New Guinea",
    "Paraguay",
    "Peru",
    "Philippines",
    "Poland",
    "Portugal",
    "Qatar",
    "Romania",
    "Russia",
    "Rwanda",
    "Saint Kitts and Nevis",
    "Saint Lucia",
    "Saint Vincent and the Grenadines",
    "Samoa",
    "San Marino",
    "Sao Tome and Principe",
    "Saudi Arabia",
    "Senegal",
    "Serbia",
    "Seychelles",
    "Sierra Leone",
    "Singapore",
    "Slovakia",
    "Slovenia",
    "Solomon Islands",
    "Somalia",
    "South Africa",
    "South Sudan",
    "Spain",
    "Sri Lanka",
    "Sudan",
    "Suriname",
    "Sweden",
    "Switzerland",
    "Syria",
    "Taiwan",
    "Tajikistan",
    "Tanzania",
    "Thailand",
    "Timor-Leste",
    "Togo",
    "Tonga",
    "Trinidad and Tobago",
    "Tunisia",
    "Turkey",
    "Turkmenistan",
    "Tuvalu",
    "Uganda",
    "Ukraine",
    "United Arab Emirates",
    "United Kingdom",
    "United States",
    "Uruguay",
    "Uzbekistan",
    "Vanuatu",
    "Vatican City",
    "Venezuela",
    "Vietnam",
    "Yemen",
    "Zambia",
    "Zimbabwe"
]
,
        address: '',
        employment_status: '',
        employmentStatusOptions: ['Employed', 'Unemployed', 'Self-employed'],
        profile_image: null,
        EmergencyContactName: '',
        EmergencyContactNumber:''
        // Store the selected file
      },
      errors: {}, // Add this line to store validation errors
    }
  },
  methods: {
    handleImageUpload() {
      const file = this.applicantPersonalInfoData.profile_image
      if (file) {
        // Read the file as a data URL to display the preview
        const reader = new FileReader()
        reader.onload = e => {
          this.previewImage = e.target.result
        }
        reader.readAsDataURL(file)
      } else {
        // Reset preview if no file is selected
        this.previewImage = null
      }
    },
    selectemployee(){
      if (this.applicantPersonalInfoData.employment_status=='Employed'){
        
      }
    },
    submitForm() {
      const formData = new FormData()
      this.applicantPersonalInfoData['id'] = this.studentInfo.user_id
      formData.append('id', this.studentInfo.user_id) // Add other form fields as needed
      formData.append('middlename', this.applicantPersonalInfoData.middlename) // Add other form fields as needed
      formData.append('phonenumber', this.applicantPersonalInfoData.phonenumber) // Add other form fields as needed
      formData.append('gender', this.applicantPersonalInfoData.gender) // Add other form fields as needed
      formData.append('genderOptions', this.applicantPersonalInfoData.genderOptions) // Add other form fields as needed
      formData.append('marital_status', this.applicantPersonalInfoData.marital_status) // Add other form fields as needed
      formData.append('maritalStatusOptions', this.applicantPersonalInfoData.maritalStatusOptions) // Add other form fields as needed
      formData.append('dob', this.applicantPersonalInfoData.dob) // Add other form fields as needed
      formData.append('nationality', this.applicantPersonalInfoData.nationality) // Add other form fields as needed
      formData.append('address', this.applicantPersonalInfoData.address) // Add other form fields as needed
      formData.append('employment_status', this.applicantPersonalInfoData.employment_status) // Add other form fields as needed
      formData.append('employmentStatusOptions', this.applicantPersonalInfoData.employmentStatusOptions)
      formData.append('employeename', this.applicantPersonalInfoData.employeename)
      formData.append('employeeadresss', this.applicantPersonalInfoData.employeeadresss)
      formData.append('employeecontact', this.applicantPersonalInfoData.employeecontact) // Add other form fields as needed
      formData.append('profile_image', this.applicantPersonalInfoData.profile_image) // Add other form fields as needed
      formData.append('EmergencyContactName', this.applicantPersonalInfoData.EmergencyContactName)
      formData.append('EmergencyContactNumber', this.applicantPersonalInfoData.EmergencyContactNumber)
      swal
        .fire({
          title: 'Are you sure, you want to submit personal Info?',
          text: "You won't be able to revert this!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, submit it!',
        })
        .then(result => {
          if (result.isConfirmed) {
            axios
              .post(`/api/submit-applicant-personal-info`, formData)
              .then(result => {
                // show success alert
                swal
                  .fire({
                    title: 'Success!',
                    text: 'application submitted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                  })
                  .then(() => {
                    this.$store.dispatch('userProfile')
                    this.applicantPersonalInfoData.dob = ''
                    this.errors = {}
                    this.$router.push('/student')
                  })
              })
              .catch(error => {
                // Handle error response
                if (error.response && error.response.status === 422) {
                  console.log('error', error.response.data.errors)
                  this.errors = error.response.data.errors
                  swal.fire({
                    title: 'Error!',
                    text: error.response.data.message,
                    icon: 'error',
                    confirmButtonText: 'OK',
                  })
                }
              })
            // swal.fire('Deleted!', 'Department has been deleted.', 'success')
          }
        })
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      this.education = this.studentInfo.education
      this.certificates = this.studentInfo.certificates
      this.applicantPersonalInfoData['id'] = this.studentInfo.user_id
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

<style scoped>
.required-field::after {
  content: ' *';
  color: red;
}
.error-message {
  color: red;
  font-size: 14px;
  margin-top: -25px;
  display: block; /* Ensure the error message is a block element */
  margin-bottom: 10px;
}
</style>
