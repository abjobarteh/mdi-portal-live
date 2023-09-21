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
      headers: [
        { text: 'CourseCode', value: 'CourseCode' },
        { text: 'CourseName', value: 'CourseName' },
        { text: 'Grade', value: 'Grade' },
        { text: 'GradePoint', value: 'GradePoint' },
        { text: 'Average', value: 'action' },
      ],
      transcripts: [],
      registrationStatus: '',
    }
  },
  //
  created() {
    axios
      .get('/api/view-student-payments')
      .then(response => {
        // this.transcripts = response.data.result
        console.log('running courses', this.runnings)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
    axios
      .get('/api/transcript-courses/' + this.$route.params.id)
      .then(response => {
        this.transcripts = response.data.result
        this.cgpa = response.data.cgpa
        console.log('running courses', this.cgpa)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
        this.pageCount = 0
      })
    axios
      .get('/api/student-detail/' + this.$route.params.id)
      .then(response => {
        this.studentInfo = response.data.result
        console.log('student info ', this.studentInfo)
      })
      .catch(err => {
        this.pageCount = 0
      })
  },

  methods: {
    // generatePDF() {
    //   const options = {
    //     filename: 'transcript.pdf',
    //     image: { type: 'jpeg', quality: 0.98 },
    //     html2canvas: { scale: 2 },
    //     jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' },
    //   }
    //   var element = document.getElementById('printable-content')

    //   html2pdf().set(options).from(element).save()
    // },
    generatePDF() {
      const options = {
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


          .watermark-image {
            position: absolute;
            top: 60%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
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
              padding: 4px;
              text-align: left;
            }

            /* Add style for the page container to give it a border */
          .page-container {
            margin: 25px;
            border: 1px solid #000; /* Set the border width and color for the page */
            padding: 25px; /* Add some padding to create space around the content */
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
            <img src="../../../../assets/images/logos/mdi_logo.png" class="watermark-image" />
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
      <h3 style="font-size: 13px;">REPUBLIC OF <span style="position: relative; top: -20px;"><img src="images/logos/mdi_logo.png" style="width: 70px; height: 70px; display: inline-block; vertical-align: middle;" /></span> THE GAMBIA</h3>
      <h3 style="font-size: 13px; border-bottom: 1px solid black; display: inline-block;">MANAGEMENT DEVELOPMENT INSTITUTE</h3><br>
      <h3 style="font-size: 13px; border-bottom: 1px solid black; display: inline-block;">${this.studentInfo.program_name}</h3><br>
      <h3 style="font-size: 13px; border-bottom: 1px solid black; display: inline-block;">OFFICIAL TRANSCRIPT</h3>
    </div>
    `

      content += `
      <table>
        <tr>
          <td style='font-size: 14px' colspan="2">NAME: ${
            this.studentInfo.firstname.toUpperCase() + ' ' + this.studentInfo.lastname.toUpperCase()
          }</td>
          <td style='font-size: 14px'>STUDENT NO: ${this.studentInfo.mat_number}</td>
        </tr>
        <tr>
          <td style='font-size: 14px'>YEAR: ONE</td>
          <td style='font-size: 14px'>SESSION: JULY - DECEMBER, 2019-JULY - DECEMBER - 2020</td>
          <td style='font-size: 14px'>DATE OF ISSUE: ${this.getCurrentDate()}</td>
        </tr>
      </table>
      `

      this.transcripts.forEach(transcript => {
        content += `
            <h3 class="semester-heading">${transcript.SemesterSession}</h3>
            <table class="transcript-table">
              <thead>
                <tr>
                  <th style='font-size: 14px'>CourseCode</th>
                  <th style='font-size: 14px' colspan="4">CourseName</th>
                  <th style='font-size: 14px'>CREDIT HOURS</th>
                  <th style='font-size: 14px'>GRADE</th>
                  <th style='font-size: 14px'>GRADE POINT</th>
                </tr>
              </thead>
              <tbody>
        `
        transcript.Courses.forEach(course => {
          content += `
              <tr>
                <td style='font-size: 14px'>${course.CourseCode}</td>
                <td colspan="4"  style='font-size: 14px; font-style: italic;'>${course.CourseName}</td>
                <td style='font-size: 14px'>3</td>
                <td style='font-size: 14px'>${course.Grade}</td>
                <td style='font-size: 14px'>${course.GradePoint}</td>
              </tr>
            `
        })

        content += `
            <tr>
              <td style='font-size: 14px' colspan="7">GRADE POINT AVERAGE</td>
              <td style='font-size: 14px'>${transcript.Average.toFixed(3)}</td>
            </tr>
              </tbody>
            </table>
          `
      })
      content += `
        <div style="text-align: right">
          <td style='font-size: 14px'>CUMULATIVE GRADE POINT AVERAGE</td>
          <td style='font-size: 14px'>${this.cgpa.toFixed(3)}</td>
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
  },
}
</script>

