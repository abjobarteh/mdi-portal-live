<template>
  <div>
    <v-card id="printable-content" v-for="transcript in transcripts" :key="transcript.SemesterSession">
      <v-card-title class="text-center d-flex justify-center">{{ transcript.SemesterSession }}</v-card-title>
      <v-data-table
        :headers="headers"
        :items="transcript.Courses"
        class="elevation-1"
        hide-default-footer
      ></v-data-table>
    </v-card>
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
      headers: [
        { text: 'CourseCode', value: 'CourseCode' },
        { text: 'CourseName', value: 'CourseName' },
        { text: 'TestMark', value: 'TestMark' },
        { text: 'ExamMark', value: 'ExamMark' },
        { text: 'TotalMark', value: 'TotalMark' },
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
      .get('/api/transcript-courses')
      .then(response => {
        this.transcripts = response.data.result
        console.log('running courses', this.runnings)
        this.pageCount = response.data.result.last_page
      })
      .catch(err => {
        this.runnings = []
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

      const htmlContent = `
        <html>
          <head>
            <style>
              .semester-heading {
                text-align: center;
                margin-bottom: 5px;
              }
              table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
              }
              th, td {
                border: 1px solid #ddd;
                padding: 8px;
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
            </style>
          </head>
          <body>
            ${tableContent}
          </body>
        </html>
      `

      html2pdf().set(options).from(htmlContent).save()
    },

    generateTableContent() {
      let content = ''

      content += `
    <div class="transcript-title">
      <h1>Management Development Institute</h1>
      <h2>Student academic record</h2>
    </div>
  `

      this.transcripts.forEach(transcript => {
        content += `
          <h3 class="semester-heading">${transcript.SemesterSession}</h3>
          <table class="transcript-table">
            <thead>
              <tr>
                <th>CourseCode</th>
                <th>CourseName</th>
                <th>TestMark</th>
                <th>ExamMark</th>
                <th>TotalMark</th>
              </tr>
            </thead>
            <tbody>
        `

        transcript.Courses.forEach(course => {
          content += `
            <tr>
              <td>${course.CourseCode}</td>
              <td>${course.CourseName}</td>
              <td>${course.TestMark}</td>
              <td>${course.ExamMark}</td>
              <td>${course.TotalMark}</td>
            </tr>
          `
        })

        content += `
            </tbody>
          </table>
        `
      })

      return content
    },
  },
}
</script>

