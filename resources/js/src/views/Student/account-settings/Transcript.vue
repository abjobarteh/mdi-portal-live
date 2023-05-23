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
    <v-btn @click="generatePDF">Generate PDF</v-btn>
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
      transcripts: [
        {
          SemesterSession: '5th semester - 2023',
          Courses: [
            {
              CourseCode: 'CPS 121',
              CourseName: 'Introduction to Computer Science',
              TestMark: 40,
              ExamMark: 40,
              TotalMark: 80,
            },
            {
              CourseCode: 'CPS 122',
              CourseName: 'IT Hardware & Systems',
              TestMark: 30,
              ExamMark: 40,
              TotalMark: 70,
            },
            {
              CourseCode: 'CPS 123',
              CourseName: 'Computer Security',
              TestMark: 30,
              ExamMark: 30,
              TotalMark: 60,
            },
            {
              CourseCode: 'CPS 124',
              CourseName: 'Programming Logic and Design',
              TestMark: 40,
              ExamMark: 25,
              TotalMark: 55,
            },
            {
              CourseCode: 'CPS 211',
              CourseName: 'Networking I',
              TestMark: 35,
              ExamMark: 35,
              TotalMark: 70,
            },
          ],
        },
        {
          SemesterSession: '6th semester - 2023',
          Courses: [
            {
              CourseCode: 'CPS 213',
              CourseName: 'Web programming I',
              TestMark: 50,
              ExamMark: 50,
              TotalMark: 100,
            },
            {
              CourseCode: 'CPS 214',
              CourseName: 'Computer programming I',
              TestMark: 30,
              ExamMark: 40,
              TotalMark: 70,
            },
            {
              CourseCode: 'CPS 212',
              CourseName: 'Database I',
              TestMark: 45,
              ExamMark: 35,
              TotalMark: 80,
            },
          ],
        },
        {
          SemesterSession: '6th semester - 2023',
          Courses: [
            {
              CourseCode: 'CPS 121',
              CourseName: 'Introduction to Computer Science',
              TestMark: 40,
              ExamMark: 40,
              TotalMark: 80,
            },
            {
              CourseCode: 'CPS 122',
              CourseName: 'IT Hardware & Systems',
              TestMark: 30,
              ExamMark: 40,
              TotalMark: 70,
            },
            {
              CourseCode: 'CPS 123',
              CourseName: 'Computer Security',
              TestMark: 30,
              ExamMark: 30,
              TotalMark: 60,
            },
            {
              CourseCode: 'CPS 124',
              CourseName: 'Programming Logic and Design',
              TestMark: 40,
              ExamMark: 25,
              TotalMark: 55,
            },
            {
              CourseCode: 'CPS 211',
              CourseName: 'Networking I',
              TestMark: 35,
              ExamMark: 35,
              TotalMark: 70,
            },
          ],
        },

        // Add more transcript items here
      ],
    }
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

