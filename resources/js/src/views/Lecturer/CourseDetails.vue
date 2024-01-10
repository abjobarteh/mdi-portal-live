<template>
  <div>
    <v-container>
      <!-- Buttons Section -->
      <v-row align="center" justify="center" v-if="userRole == 3">
        <v-col cols="6" class="text-center">
          <v-btn style="margin-top: -24px" color="primary" @click="onFileUpload" block rounded> Upload file </v-btn>
        </v-col>
      </v-row>

      <!-- Subject Title Section -->
      <v-row>
        <v-col cols="12" class="text-center mt-6">
          <h2 class="subtitle custom-underline">
            {{ courseName }}
          </h2>
        </v-col>
      </v-row>

      <!-- Table Section -->
      <v-row>
        <v-col cols="12">
          <!-- Files Table -->
          <v-data-table :headers="headers" :items="files" class="elevation-1">
            <template v-slot:item.actions="{ item }">
              <v-btn @click="downloadFile(item.file_name)" color="primary" text>Download</v-btn>
            </template>
          </v-data-table>
        </v-col>
      </v-row>

      <!-- Students Table -->

      <v-row>
        <v-col cols="12">
          <v-card>
            <v-toolbar color="primary" dark>
              <v-toolbar-title>Course Students</v-toolbar-title>
              <v-btn color="purple darken-2" small class="white--text" @click="exportToExcel">Export to Excel</v-btn>
              <v-spacer></v-spacer>
            </v-toolbar>

            <v-card-text>
              <v-data-table
                :headers="headers2"
                :items="students"
                :items-per-page="13"
                class="elevation-1"
                hide-default-footer
              >
                <template v-slot:item.name="{ item }">
                  <span>{{ item.student.firstname + ' ' + item.student.lastname }}</span>
                </template>
                <template v-slot:item.mat_number="{ item }">
                  <span>{{ item.student.mat_number }}</span>
                </template>
                <template v-slot:item.mat_number="{ item }">
                  <span>{{ item.student.mat_number }}</span>
                </template>
                <template v-slot:item.phonenumber="{ item }">
                  <span>{{ item.student.phonenumber }}</span>
                </template>
              </v-data-table>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-dialog v-model="fileUploadDialog" max-width="500px">
      <v-card>
        <v-card-title class="headline">Upload File</v-card-title>
        <v-card-text>
          <v-text-field outlined v-model="fileName" label="File Name"></v-text-field>
          <v-file-input label="Select File" @change="onFileSelected" outlined></v-file-input>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="uploadFile">Upload</v-btn>
          <v-btn color="red" @click="closeFileUploadDialog">Cancel</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import 'vuetify/dist/vuetify.min.css'
import apiBaseURL from '../../../api-config'
import * as XLSX from 'xlsx'

export default {
  data() {
    return {
      courseName: '',

      files: [],
      headers: [
        { text: 'File title', value: 'file_title' },
        { text: 'Uploaded Date', value: 'uploaded_date' },
        { text: 'File', value: 'actions' },
      ],

      students: [],
      headers2: [
        { text: 'FullName', value: 'name' },
        { text: 'Student Number', value: 'mat_number' },
        { text: 'Phonenumber', value: 'phonenumber' },
      ],
      page: 1,
      pageCount: 0,
      // Your data properties here (if needed)

      // New data properties for video upload
      videoUploadDialog: false,
      videoName: '',
      selectedVideo: null,

      fileUploadDialog: false,
      fileName: '',
      selectedFile: null,
      userRole: '',
    }
  },

  mounted() {
    console.log('user here ', this.$store.getters.currentUser.role_id)
    this.userRole = this.$store.getters.currentUser.role_id
  },
  created() {
    this.getLecturerFiles()
    this.getStudents()
  },
  methods: {
    exportToExcel() {
      // Extract only the columns you want (firstname and lastname)
      const dataToExport = this.students.map(student => ({
        Firstname: student.student.firstname + ' ' + student.student.lastname,
      }))

      // Convert the extracted data to a worksheet
      const worksheet = XLSX.utils.json_to_sheet(dataToExport)

      // Create a new workbook and add the worksheet
      const workbook = XLSX.utils.book_new()
      XLSX.utils.book_append_sheet(workbook, worksheet, 'Students')

      // Save the workbook as an Excel file
      XLSX.writeFile(workbook, 'students.xlsx')
    },
    getLecturerFiles() {
      this.courseName = this.$route.query.course_name

      axios
        .post('api/lecturer-files', { semester_course_id: this.$route.params.id })
        .then(response => {
          this.files = response.data.result.data
          console.log('files', this.files)
          // Handle the response from the API if needed
        })
        .catch(error => {
          console.error('File upload failed:', error)
          // Handle the error if needed
        })
    },
    getStudents() {
      axios
        .post('/api/manage-student-marks', { course_id: this.$route.query.course_id })
        .then(response => {
          this.students = response.data.result
          console.log('students ', this.students)
          // Handle the response from the API if needed
        })
        .catch(error => {
          console.error('File upload failed:', error)
          // Handle the error if needed
        })
    },
    downloadFile(file) {
      const fullURL = apiBaseURL + 'storage/lecturer-files/' + file
      window.open(fullURL, '_blank')
    },
    uploadFile() {
      const formData = new FormData()
      formData.append('file', this.selectedFile)
      formData.append('file_title', this.fileName)
      formData.append('semester_course_id', this.$route.params.id)
      // lecturer-files
      // Replace 'your-api-endpoint' with the actual API endpoint URL to handle file upload
      axios
        .post('api/upload-lecturer-files', formData)
        .then(response => {
          swal
            .fire({
              title: 'Success!',
              text: 'File uploaded successfully.',
              icon: 'success',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getLecturerFiles()
            })
        })
        .catch(error => {
          console.error('File upload failed:', error.response.data.message)
          swal
            .fire({
              title: 'Success!',
              text: error.response.data.message,
              icon: 'error',
              confirmButtonText: 'OK',
            })
            .then(() => {
              this.getLecturerFiles()
            })
        })

      this.closeFileUploadDialog()
    },

    onVideoUpload() {
      this.videoUploadDialog = true // Show the video upload modal
    },

    closeVideoUploadDialog() {
      this.videoUploadDialog = false // Hide the video upload modal
      this.videoName = '' // Clear the video name field
      this.selectedVideo = null // Clear the selected video file
    },

    onVideoSelected(videoFile) {
      this.selectedVideo = videoFile // Store the selected video file
    },

    uploadVideo() {
      // You can implement the video upload logic here using this.selectedVideo and this.videoName
      // Once the video is uploaded, close the modal:
      this.closeVideoUploadDialog()
    },

    ///////////////////  file /////////////
    onFileUpload() {
      this.fileUploadDialog = true // Show the video upload modal
    },

    closeFileUploadDialog() {
      this.fileUploadDialog = false // Hide the video upload modal
      this.fileName = '' // Clear the video name field
      this.selectedFile = null // Clear the selected video file
    },

    onFileSelected(file) {
      this.selectedFile = file // Store the selected video file
    },
  },
}
</script>

<style>
/* Add custom CSS styles for further styling */

.file-input .v-label {
  font-size: 14px;
  color: #333;
}

.subtitle {
  font-size: 28px;
  color: #293c89; /* Change the color to your desired title color */
  font-weight: bold;
  margin-bottom: 16px;
}

.custom-underline {
  position: relative;
  display: inline-block;
  padding-bottom: 5px;
}

.custom-underline::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 50%;
  height: 2px;
  background-color: #293c89; /* You can change the color to your desired underline color */
  transform: translateX(-50%);
}

/* Styling for the Upload Video button */
.upload-btn {
  font-size: 18px;
  padding: 12px 24px;
  /* Add other custom styles to make the button more beautiful */
}

/* Adjust button style for equal width and same line */
.v-btn {
  min-width: 200px; /* Set a minimum width for both buttons */
}
</style>
