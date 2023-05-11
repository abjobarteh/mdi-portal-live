<template>
  <v-form v-model="valid" ref="form" @submit.prevent="submit">
    <v-container style="background-color: #fefcff">
      <v-card class="pa-6">
        <v-card-title>
          <span class="headline font-weight-medium">PERSONAL INFORMATION</span>
        </v-card-title>
        <v-row>
          <v-col cols="12" md="6">
            <v-select outlined v-model="prefix" :items="prefixOptions" label="Prefix *" required></v-select>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="familyName" label="Family Name *" required></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="firstAndMiddleName" label="First & Middle Name *" required></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="phoneNumber" label="Phone Number *" required></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="email" label="Email Address *" type="email" required></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-select outlined v-model="gender" :items="genderOptions" label="Gender *" required></v-select>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="maritalStatus"
              :items="maritalStatusOptions"
              label="Marital Status *"
              required
            ></v-select>
          </v-col>
          <v-col cols="12" md="6">
            <v-menu
              v-model="menu"
              :close-on-content-click="false"
              :nudge-right="40"
              :return-value.sync="dateOfBirth"
              transition="scale-transition"
              offset-y
              full-width
            >
              <template v-slot:activator="{ on }">
                <v-text-field
                  outlined
                  v-model="formattedDateOfBirth"
                  label="Date of Birth *"
                  readonly
                  v-on="on"
                  required
                ></v-text-field>
              </template>
              <v-date-picker outlined v-model="dateOfBirth" scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu = false">Cancel</v-btn>
                <v-btn
                  text
                  color="primary"
                  @click="
                    $refs.form.validate()
                    menu = false
                  "
                  >OK</v-btn
                >
              </v-date-picker>
            </v-menu>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="nationality" label="Nationality *" required></v-text-field>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="address" label="Address *" required></v-text-field>
          </v-col>
        </v-row>
        <v-row>
          <v-col cols="12" md="6">
            <v-select
              outlined
              v-model="employmentStatus"
              :items="employmentStatusOptions"
              label="Employment Status *"
              required
            ></v-select>
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined v-model="ministryOrDepartment" label="Ministry/Department *" required></v-text-field>
          </v-col>
        </v-row>
      </v-card>
    </v-container>
  </v-form>
</template>

  <script>
export default {
  data() {
    return {
      prefix: '',
      prefixOptions: ['Mr', 'Mrs', 'Ms', 'Dr', 'Prof'],
      familyName: '',
      firstAndMiddleName: '',
      phoneNumber: '',
      email: '',
      gender: '',
      genderOptions: ['Male', 'Female', 'Other'],
      maritalStatus: '',
      maritalStatusOptions: ['Single', 'Married', 'Divorced', 'Widowed'],
      dob: '',
      nationality: '',
      address: '',
      employmentStatus: '',
      employmentStatusOptions: ['Employed', 'Unemployed', 'Self-employed'],
      ministry: '',
      employmentNumber: '',
    }
  },
  methods: {
    submitForm() {
      // handle form submission here
    },
  },
}
</script>