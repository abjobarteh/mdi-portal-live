<template>
  <div>
    <v-card id="printable-content" v-for="transcript in transcripts" :key="transcript.SemesterSession">
      <v-card-title class="text-center d-flex justify-center">{{ transcript.SemesterSession }}</v-card-title>
      <v-data-table :headers="headers" :items="transcript.Courses" class="elevation-1" hide-default-footer>
        <template v-slot:footer>
          <tr style="display: inline-block; margin-left: 90%">
            <td style="font-size: 16px">GPA:</td>
            <td style="font-size: 16px">{{ transcript.Average.toFixed(3) }}</td>
            <!-- Add more columns for other average values if needed -->
          </tr>
        </template></v-data-table
      >
    </v-card>
    <p style="font-size: 16px">CGPA {{ this.cgpa.toFixed(3) }}</p>
    <v-btn @click="generatePDF" class="mt-2" block color="light-green">Generate PDF</v-btn>
  </div>
</template>


<style scoped>
.semester-heading {
  text-align: center;
}
</style>


<script>
import html2pdf from 'html2pdf.js'

export default {
  data() {
    return {
      studentInfo: '',
      cgpa: 0,
      count: 0,
      headers: [
        { text: 'CourseCode', value: 'CourseCode', width: '20%' },
        { text: 'CourseName', value: 'CourseName', width: '35%' },
        { text: 'Grade', value: 'Grade', width: '25%' },
        { text: 'GradePoint', value: 'GradePoint' },
        { text: 'Average', value: 'action' },
      ],
      transcripts: [],
      registrationStatus: '',
    }
  },
  //
  mounted() {
    this.$store.dispatch('userProfile')
    console.log('transcript mounted', this.studentInfo)
  },
  created() {
    // Dispatch 'userProfile' action to fetch user profile
    this.$store.dispatch('userProfile')

    // Set up a watcher to respond to changes in this.getUserProfile
    this.$watch('getUserProfile', this.fetchTranscriptCourses)

    // You can also make the API call to fetch student payments here
    axios
      .get('/api/view-student-payments')
      .then(response => {
        // Handle the response
      })
      .catch(err => {
        // Handle errors
      })
  },

  methods: {
    generatePDF() {
      const options = {
        margin: [0.3, 0.3], // Add margin values for top and bottom, left and right
        filename: 'transcript.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
      }

      const tableContent = this.generateTableContent()

      const watermarkImage = './mdi_logo.png'

      const htmlContent = `
      <html>
        <head>
          <style>

              .semester-heading {
                text-align: center;
                margin-bottom: 5px;
                font-size: 15px;

              }
              table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
              }
              th, td {
                border: 1px solid #ddd;
                padding: 4px;
                text-align: left;
              }
              .transcript-title {
                text-align: center;
                margin-bottom: 10px;
                margin-top: 10px;
              }

              .transcript-table {
                table-layout: fixed;
                width: 100%;
              }

              .transcript-table td,
              .transcript-table th {
                word-wrap: break-word;
                text-align: center; /* Add this line */
              }

              /* Watermark styles */
              .watermark-container {
                position: relative;
              }

              // .watermark-image {
              //   position: absolute;
              //   top: 60%;
              //   left: 50%;
              //   transform: translate(-50%, -50%);
              //   width: 100%;
              //   height: 100%;
              //   opacity: 0.09; /* Adjust the opacity as needed */
              //   z-index: -1;
              // }

              .watermark-image {
                position: fixed; /* Use fixed position to make it centered on the page */
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 45%;
                height: 75%;
                opacity: 0.09; /* Adjust the opacity as needed */
                z-index: -1;
              }

            table {
                border-collapse: separate;
                border-spacing: 1px;
                width: 100%;
                border: 1px ridge #CCCCCC;
              }

              td {
                border: 2px ridge #CCCCCC;
                padding: 3px;
                text-align: left;
              }

              /* Add style for the page container to give it a border */
            .page-container {
              // margin: 25px;
              // border: 0.7px solid #000; /* Set the border width and color for the page */
              padding: 10px; /* Add some padding to create space around the content */
              // height: auto;
            }

            /* Add style for the body element to remove any default margin and padding */
            body {
              margin: 0;
              padding: 0;
            }

          </style>
        </head>
        <body>
          <div class="page-container">
            <div class="watermark-container">
              ${tableContent}
              <img src="images/logos/mdi_logo.png" class="watermark-image" />
            </div>
        </div>
        </body>
      </html>
    `

      html2pdf().set(options).from(htmlContent).save()
    },

    generateTableContent() {
      let content = ''

      content += `
      <div class="transcript-title">
      <h3 style="font-size: 12px;">REPUBLIC OF <span style="position: relative; top: -5px;"><img src="images/logos/mdi_logo.png" style="width: 60px; height: 60px; display: inline-block; vertical-align: middle;" /></span> THE GAMBIA</h3>
      <h3 style="font-size: 12px; border-bottom: 1px solid black; display: inline-block;">MANAGEMENT DEVELOPMENT INSTITUTE</h3><br>
      <h3 style="font-size: 12px; border-bottom: 1px solid black; display: inline-block;">${this.studentInfo.program_name}</h3><br>
      <h3 style="font-size: 12px; border-bottom: 1px solid black; display: inline-block;">OFFICIAL TRANSCRIPT</h3>
    </div>
    `
      let startsession = this.transcripts[0].SemesterSession.substring(
        this.transcripts[0].SemesterSession.indexOf('('),
      ).slice(1, -1)
      let endsession = this.transcripts[this.transcripts.length - 1].SemesterSession.substring(
        this.transcripts[this.transcripts.length - 1].SemesterSession.indexOf('('),
      ).slice(1, -1)

      content += `
      <table>
        <tr>
          <td style='font-size: 13px' colspan="2">NAME: ${
            this.studentInfo.firstname.toUpperCase() + ' ' + this.studentInfo.lastname.toUpperCase()
          }</td>
          <td style='font-size: 13px'>STUDENT NO: ${this.studentInfo.mat_number}</td>
        </tr>
        <tr>
          <td style='font-size: 13px'>YEAR: ONE</td>
          <td style='font-size: 13px'>SESSION: ${startsession + ' - ' + endsession}</td>
          <td style='font-size: 13px'>DATE OF ISSUE: ${this.getCurrentDate()}</td>
        </tr>
      </table>
      `

      this.transcripts.forEach((transcript, index) => {
        this.count++
        let session = transcript.SemesterSession.substring(transcript.SemesterSession.indexOf('('))
        console.log('trans', transcript)

        if (index === 2) {
          content += `
            <table class="transcript-table">
              <thead>
                <td style="font-size: 13px; background-color: #f0f0f0; text-align: left;"  colspan="8">YEAR: TWO</td>
                <tr>
                  <th style='font-size: 13px'>${
                    transcript.SemesterSession.split(' ')[0] + ' ' + transcript.SemesterSession.split(' ')[1]
                  }</th>
                  <th style='font-size: 13px' colspan="4">SESSION: ${session.slice(1, -1)}</th>
                  <th style='font-size: 13px'>CREDIT HOURS</th>
                  <th style='font-size: 13px'>GRADE</th>
                  <th style='font-size: 13px'>GRADE POINT</th>
                </tr>
              </thead>
              <tbody>
        `
        } else {
          content += `
            <table class="transcript-table">
              <thead>
                <tr>
                  <th style='font-size: 14px'>${
                    transcript.SemesterSession.split(' ')[0] + ' ' + transcript.SemesterSession.split(' ')[1]
                  }</th>
                  <th style='font-size: 13px' colspan="4">SESSION: ${session.slice(1, -1)}</th>
                  <th style='font-size: 13px'>CREDIT HOURS</th>
                  <th style='font-size: 13px'>GRADE</th>
                  <th style='font-size: 13px'>GRADE POINT</th>
                </tr>
              </thead>
              <tbody>
        `
        }
        transcript.Courses.forEach(course => {
          content += `
              <tr>
                <td style='font-size: 13px'>${course.CourseCode}</td>
                <td colspan="4"  style='font-size: 13px; font-style: italic;'>${course.CourseName}</td>
                <td style='font-size: 13px'>3</td>
                <td style='font-size: 13px'>${course.Grade}</td>
                <td style='font-size: 13px'>${course.GradePoint}</td>
              </tr>
            `
        })

        content += `
            <tr>
              <td style='font-size: 13px' colspan="7">GRADE POINT AVERAGE</td>
              <td style='font-size: 13px'>${transcript.Average.toFixed(3)}</td>
            </tr>
              </tbody>
            </table>
          `
      })
      content += `
        <div style="position: relative">
          <div style="text-align: right">
          <td style='font-size: 10px'>CUMULATIVE GRADE POINT AVERAGE</td>
          <td style='font-size: 10px'>${this.cgpa.toFixed(3)}</td>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 50px">
          <div>
            <div>.....................................</div>
            <div>Registrar</div>
          </div>
          <div>School Stamp</div>
        </div>
        </div>

          `
      return content
    },
    getCurrentDate() {
      const monthNames = [
        'JANUARY',
        'FEBRUARY',
        'MARCH',
        'APRIL',
        'MAY',
        'JUNE',
        'JULY',
        'AUGUST',
        'SEPTEMBER',
        'OCTOBER',
        'NOVEMBER',
        'DECEMBER',
      ]

      const currentDate = new Date()
      const month = monthNames[currentDate.getMonth()]
      const year = currentDate.getFullYear()

      return `${month}, ${year}`
    },

    fetchTranscriptCourses() {
      // Check if this.getUserProfile has the required data
      if (this.getUserProfile && this.getUserProfile.user_id) {
        // Make the API call with this.getUserProfile.user_id
        axios
          .get('/api/transcript-courses/' + this.getUserProfile.user_id)
          .then(response => {
            // Handle the response and update data properties
            this.transcripts = response.data.result
            this.cgpa = response.data.cgpa
            this.pageCount = response.data.result.last_page
          })
          .catch(err => {
            // Handle errors
            this.runnings = []
            this.pageCount = 0
          })
      }
    },
  },

  watch: {
    getUserProfile: function () {
      this.studentInfo = this.getUserProfile
      console.log('ids', this.studentInfo.user_id)
    },
  },

  // created() {
  //   this.$store.dispatch('userProfile')
  //   // student-detail/this.$route.params.id
  // },
  computed: {
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },
}
</script>

