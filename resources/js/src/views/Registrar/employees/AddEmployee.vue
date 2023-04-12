<template>
  <v-card class="form-card" style="border-radius: 20px">
    <v-container class="form-container" fluid>
      <h2>Personal Information</h2>
      <!-- <div>
        <input type="file" @change="onFileSelected" />
        <button @click="uploadFile">Upload</button>
      </div> -->
      <v-form>
        <v-row>
          <v-col cols="12" md="6">
            <v-text-field outlined label="First Name" v-model="formData.firstname" class="rounded-input"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.firstname.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-col>
          <v-col cols="12" md="6">
            <v-text-field outlined label="Last Name" v-model="formData.lastname" class="rounded-input"></v-text-field>
            <span
              style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
              v-for="error in v$.lastname.$errors"
              :key="error.$uid"
              >{{ error.$message }}</span
            >
          </v-col>
        </v-row>
        <v-text-field outlined label="Address" v-model="formData.address" class="rounded-input"></v-text-field>
        <span
          style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
          v-for="error in v$.address.$errors"
          :key="error.$uid"
          >{{ error.$message }}</span
        >
        <v-text-field outlined label="Telephone" v-model="formData.telephone" class="rounded-input"></v-text-field>
        <span
          style="color: #e6676b; position: absolute; margin-top: -30px; margin-left: 10px"
          v-for="error in v$.telephone.$errors"
          :key="error.$uid"
          >{{ error.$message }}</span
        >
        <v-btn color="primary" @click="submitForm" class="submit-btn">Submit</v-btn>
      </v-form>
    </v-container>
  </v-card>
</template>

<script>
import { ref } from '@vue/composition-api'
import useVuelidate from '@vuelidate/core'
import { required } from '@vuelidate/validators'
import 'vuetify/dist/vuetify.min.css'

export default {
  setup() {
    const selectedFile = ref(null)
    const formData = ref({
      firstname: '',
      lastname: '',
      address: '',
      telephone: '',
    })

    const rules = {
      firstname: { required },
      lastname: { required },
      address: { required },
      telephone: { required },
    }

    const v$ = useVuelidate(rules, formData)

    async function submitForm() {
      const result = await v$.value.$validate()
      if (result) {
        axios
          .post('/api/add-employee', formData.value)
          .then(result => {
            // show success alert
            swal.fire({
              title: 'Success!',
              text: 'Employee added successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
          })
          .catch(error => {
            // show error alert
            swal.fire({
              title: 'Error!',
              text: 'Failed to add employee.',
              icon: 'error',
              confirmButtonText: 'OK',
            })
          })
      } else {
        swal.fire({
          title: 'Error!',
          text: 'Failed to add employee.',
          icon: 'error',
          confirmButtonText: 'OK',
        })
      }
    }
    function onFileSelected(event) {
      selectedFile.value = event.target.files[0]
    }
    function uploadFile() {
      const formData = new FormData()
      formData.append('file', this.selectedFile)
      axios
        .post('/api/upload-excel-file', formData)
        .then(response => {
          console.log(response.data)
          // do something with the response
        })
        .catch(error => {
          console.log(error.response.data)
          // handle the error
        })
    }
    return { formData, submitForm, v$, uploadFile, onFileSelected, selectedFile }
  },
}
</script>

<style>
.rounded-input {
  border-radius: 30px;
  background-color: var(--input-background-color);
  border-color: var(--input-border-color);
}

.submit-btn {
  margin-bottom: 10px;
  border-radius: 30px;
  width: 100%;
  background-color: var(--button-background-color);
  color: var(--button-text-color);
}
</style>
