import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'


Vue.use(VueRouter)

const routes = [
  // {
  //   path: '/',
  //   redirect: 'registrar-dashboard',
  // },
  {
    path: '/',
    beforeEnter: (to, from, next) => {
      const currentUser = store.getters.currentUser; // get the current user object
      if (currentUser === null) {
        next('/login'); // redirect to login if currentUser is null
      } else {
        const userRole = currentUser.role_id; // get the user's role
        if (userRole === undefined) {
          next('/login'); // redirect to login if user role is undefined
        } else if (userRole === 1) {
          next('/admin-dashboard'); // redirect to admin dashboard for role 1
        } else if (userRole === 2) {
          next('/registrar-dashboard'); // redirect to registrar dashboard for other roles
        }
        else if (userRole === 3) {
          next('/lecturer-dashboard'); // redirect to registrar dashboard for other roles
        } else if (userRole === 6) {
          next('/password-reset'); // redirect to registrar dashboard for other roles
        }
      }
    }
  },
  {
    path: '/admin-dashboard',
    name: 'admin-dashboard',
    component: () => import('@/views/dashboard/admin/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [1],
    }
  },

  {
    path: '/registrar-dashboard',
    name: 'registrar-dashboard',
    component: () => import('@/views/dashboard/registrar/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [2,8],
    }
  },

  {
    path: '/hod-dashboard',
    name: 'hod-dashboard',
    component: () => import('@/views/dashboard/HOD/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }
  },
  {
    path: '/compliance-dashboard',
    name: 'compliance-dashboard',
    component: () => import('@/views/dashboard/compliance/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },
  {
    path: '/lecturer-dashboard',
    name: 'lecturer-dashboard',
    component: () => import('@/views/dashboard/lecturer/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [3],
    }
  },


  {
    path: '/student',
    name: 'student',
    component: () => import('@/views/Student/home/Home.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/student-detail/:id',
    name: 'student-detail',
    component: () => import('@/views/Registrar/students/student-detail.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1,8],
    }
  },

  
  {
    path: '/student-detail-hod/:id',
    name: 'student-detail',
    component: () => import('@/views/HOD/students/student-detail.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }
  },

  {
    path: '/finance-dashboard',
    name: 'finance-dashboard',
    component: () => import('@/views/dashboard/finance/Dashboard.vue'),
    meta: {
      requiresAuth: true,
      roles: [5],
    }
  },

  {
    path: '/applicant-personal-info',
    name: 'applicant-personal-info',
    component: () => import('@/components/student/ApplicantPersonalInfoForm.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/applicant-academic-info',
    name: 'applicant-academic-info',
    component: () => import('@/components/student/ApplicantAcademicInfoForm.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/applicant-certificate-info',
    name: 'applicant-certificate-info',
    component: () => import('@/components/student/ApplicantCertificateInfoForm.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/applicant-declaration',
    name: 'applicant-declaration',
    component: () => import('@/components/student/ApplicantDeclarationAndPreview.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/applicant-applied-department',
    name: 'applicant-applied-department',
    component: () => import('@/components/student/ApplicantAppliedDepartmentForm.vue'),
    meta: {
      requiresAuth: true,
      roles: [4],
    }
  },

  {
    path: '/typography',
    name: 'typography',
    component: () => import('@/views/typography/Typography.vue'),
  },
  {
    path: '/icons',
    name: 'icons',
    component: () => import('@/views/icons/Icons.vue'),
  },
  {
    path: '/cards',
    name: 'cards',
    component: () => import('@/views/cards/Card.vue'),
  },
  {
    path: '/simple-table',
    name: 'simple-table',
    component: () => import('@/views/simple-table/SimpleTable.vue'),
  },
  {
    path: '/form-layouts',
    name: 'form-layouts',
    component: () => import('@/views/form-layouts/FormLayouts.vue'),
  },
  {
    path: '/pages/account-settings',
    name: 'pages-account-settings',
    component: () => import('@/views/pages/account-settings/AccountSettings.vue'),
  },
  {
    path: '/add-employee',
    name: 'add-employee',
    component: () => import('@/views/Registrar/employees/AddEmployee.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },
  {
    path: '/view-employees',
    name: 'view-employees',
    component: () => import('@/views/Registrar/employees/ViewEmployees.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },
  {
    path: '/add-grading',
    name: 'add-grading',
    component: () => import('@/views/Registrar/gradings/AddGrading.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },
  {
    path: '/view-gradings',
    name: 'view-gradings',
    component: () => import('@/views/Registrar/gradings/ViewGradings.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },
  {
    path: '/view-compliance-gradings',
    name: 'view-compliance-gradings',
    component: () => import('@/views/Compliance/ViewGradings.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-departments',
    name: 'view-departments',
    component: () => import('@/views/Registrar/departments/ViewDepartments.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 5, 1],
    }

  },
  {
    path: '/view-compliance-departments',
    name: 'view-compliance-departments',
    component: () => import('@/views/Compliance/ViewDepartments.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-program-durations',
    name: 'view-program-durations',
    component: () => import('@/views/Registrar/program-duration/ViewProgramDurations.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 5],
    }

  },
  {
    path: '/view-program-compliance-durations',
    name: 'view-program-compliance-durations',
    component: () => import('@/views/Compliance/ViewProgramDurations.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },

  {
    path: '/view-programs',
    name: 'view-programs',
    component: () => import('@/views/Registrar/programs/ViewPrograms.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 5, 1],
    }
  },
  {
    path: '/view-compliance-programs',
    name: 'view-compliance-programs',
    component: () => import('@/views/Compliance/ViewPrograms.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },
  {
    path: '/view-courses',
    name: 'view-courses',
    component: () => import('@/views/Registrar/courses/ViewCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }

  },
  {
    path: '/view-compliance-courses',
    name: 'view-compliance-courses',
    component: () => import('@/views/Compliance/ViewCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },
  {
    path: '/approve-marks',
    name: 'approve-marks',
    component: () => import('@/views/Registrar/approve-marks/ApproveMarks.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }

  },

  // approve-marks

  {
    path: '/view-admission-codes-locations',
    name: 'view-admission-codes-locations',
    component: () => import('@/views/Registrar/admission-codes-locations/AdmissionCodesLocations.vue'),
    meta: {
      requiresAuth: true,
      roles: [5, 6, 1, 2,8],
    }
  },

  {
    path: '/view-compliance-admission-codes-locations',
    name: 'view-compliance-admission-codes-locations',
    component: () => import('@/views/Compliance/AdmissionCodesLocations.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-admission-codes/:id',
    name: 'view-admission-codes',
    component: () => import('@/views/Registrar/admission-codes/AdmissionCodes.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 6, 1,8],
    }
  },

  {
    path: '/view-sessions',
    name: 'view-sessions',
    component: () => import('@/views/Registrar/sessions/ViewSessions.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },
  {
    path: '/view-compliance-sessions',
    name: 'view-compliance-sessions',
    component: () => import('@/views/Compliance/ViewSessions.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },
  {
    path: '/view-semesters',
    name: 'view-semesters',
    component: () => import('@/views/Registrar/semesters/ViewSemesters.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1,8],
    }
  },
  {
    path: '/view-locations',
    name: 'view-locations',
    component: () => import('@/views/Registrar/Locations/ViewLocations.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },
  {
    path: '/view-compliance-locations',
    name: 'view-compliance-locations',
    component: () => import('@/views/Compliance/ViewLocations.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },
  {
    path: '/view-compliance-semesters',
    name: 'view-compliance-semesters',
    component: () => import('@/views/Compliance/ViewSemesters.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-lecturers',
    name: 'view-lecturers',
    component: () => import('@/views/Registrar/lecturers/ViewLecturers.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1,8],
    }
  },

  {
    path: '/view-compliance-lecturers',
    name: 'view-compliance-lecturers',
    component: () => import('@/views/Compliance/ViewLecturers.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-deferments',
    name: 'view-deferments',
    component: () => import('@/views/Registrar/deferments/ViewDeferments.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },

  {
    path: '/view-users',
    name: 'view-users',
    component: () => import('@/views/Admin/users/ViewUsers.vue'),
    meta: {
      requiresAuth: true,
      roles: [1],
    }

  },


  {
    path: '/view-compliance-users',
    name: 'view-compliance-users',
    component: () => import('@/views/Compliance/ViewUsers.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },

  {
    path: '/view-activities',
    name: 'view-activities',
    component: () => import('@/views/Admin/users/ViewActivities.vue'),
    meta: {
      requiresAuth: true,
      roles: [1],
    }

  },

  {
    path: '/view-compliance-activities',
    name: 'view-compliance-activities',
    component: () => import('@/views/Compliance/ViewActivities.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },


  {
    path: '/view-incoming-applications',
    name: 'view-incoming-applications',
    component: () => import('@/views/Registrar/applications/IncomingApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1, 7],
    }
  },

  {
    path: '/view-compliance-incoming-applications',
    name: 'view-compliance-incoming-applications',
    component: () => import('@/views/Compliance/IncomingApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-accepted-applications',
    name: 'view-accepted-applications',
    component: () => import('@/views/Registrar/applications/AcceptedApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1, 7,8],
    }

  },

  {
    path: '/view-compliance-accepted-applications',
    name: 'view-compliance-accepted-applications',
    component: () => import('@/views/Compliance/AcceptedApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },

  {
    path: '/view-rejected-applications',
    name: 'view-rejected-applications',
    component: () => import('@/views/Registrar/applications/RejectedApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1, 7,8],
    }
  },
  {
    path: '/view-compliance-rejected-applications',
    name: 'view-compliance-rejected-applications',
    component: () => import('@/views/Compliance/RejectedApplications.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1, 7,8],
    }
  },

  {
    path: '/view-application-preview/:id',
    name: 'view-application-preview',
    component: () => import('@/views/Registrar/student/ApplicantDeclarationAndPreview.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1, 7],
    }

  },

  {
    path: '/view-compliance-application-preview/:id',
    name: 'view-compliance-application-preview',
    component: () => import('@/views/Compliance/ApplicantDeclarationAndPreview.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },

  {
    path: '/registration-status',
    name: 'registration-status',
    component: () => import('@/views/Registrar/settings/RegistrationStatus.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },
  {
    path: '/admission-status',
    name: 'admission-status',
    component: () => import('@/views/Registrar/settings/AdmissionStatus.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },

  {
    path: '/view-students',
    name: 'view-students',
    component: () => import('@/views/Registrar/students/ViewStudents.vue'),
    meta: {
      requiresAuth: true,
      roles: [2, 1],
    }
  },

  {
    path: '/view-compliance-students',
    name: 'view-compliance-students',
    component: () => import('@/views/Compliance/ViewStudents.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }
  },

  {
    path: '/view-student-fees',
    name: 'view-student-fees',
    component: () => import('@/views/Finance/studentsFees/ViewStudentsFees.vue'),
    meta: {
      requiresAuth: true,
      roles: [5, 1],
    }

  },
  {
    path: '/view-compliance-student-fees',
    name: 'view-compliance-student-fees',
    component: () => import('@/views/Finance/studentsFees/ViewStudentFeesCompliance.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },
  {
    path: '/password-reset',
    name: 'password-reset',
    component: () => import('@/views/Finance/ResetPassword.vue'),
    meta: {
      requiresAuth: true,
      roles: [6],
    }

  },

  {
    path: '/view-agents',
    name: 'view-agents',
    component: () => import('@/views/Finance/ViewAgents.vue'),
    meta: {
      requiresAuth: true,
      roles: [5, 1],
    }

  },
  {
    path: '/view-compliance-agents',
    name: 'view-compliance-agents',
    component: () => import('@/views/Compliance/ViewAgents.vue'),
    meta: {
      requiresAuth: true,
      roles: [8],
    }

  },
  {
    path: '/manage-student-marks',
    name: 'manage-student-marks',
    component: () => import('@/views/Lecturer/ManageStudentMarks.vue'),
    meta: {
      requiresAuth: true,
      roles: [3],
    }

  },

  {
    path: '/lecturer-courses',
    name: 'lecturer-courses',
    component: () => import('@/views/Lecturer/MyCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [3],
    }

  },

  {
    path: '/courses/:id',
    name: 'course-details',
    component: () => import('@/views/Lecturer/CourseDetails.vue'),
    meta: {
      requiresAuth: true,
      roles: [3, 4],
    }

  },

  // hod
  {
    path: '/view-hod-courses',
    name: 'view-hod-courses',
    component: () => import('@/views/HOD/courses/ViewCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }

  },

  {
    path: '/approve-marks-hod',
    name: 'approve-marks-hod',
    component: () => import('@/views/HOD/approve-marks/ApproveMarks.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }

  },

  {
    path: '/view-hod-lecturers',
    name: 'view-hod-lecturers',
    component: () => import('@/views/HOD/lecturers/ViewLecturers.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }
  },

  {
    path: '/view-hod-students',
    name: 'view-hod-students',
    component: () => import('@/views/HOD/students/ViewStudents.vue'),
    meta: {
      requiresAuth: true,
      roles: [7],
    }
  },



  {
    path: '/login',
    name: 'pages-login',
    component: () => import('@/views/pages/Login.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '/register',
    name: 'pages-register',
    component: () => import('@/views/pages/Register.vue'),
    meta: {
      layout: 'blank',
    },
  },
  // {
  //   path: '/api/reset-password/:token',
  //   name: 'reset-password',
  //   component: () => import('@/views/pages/ResetPassword.vue'),
  //   meta: {
  //     layout: 'blank',
  //   },
  // },
  {
    path: '/api/reset-user-password',
    name: 'reset-user-password',
    component: () => import('@/views/pages/ResetPassword.vue'),
    props: route => ({
      token: route.query.token,
      email: route.query.email
    }),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '/forgot-password',
    name: 'forgot-password',
    component: () => import('@/views/pages/ForgotPassword.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '/error-404',
    name: 'error-404',
    component: () => import('@/views/Error.vue'),
    meta: {
      layout: 'blank',
    },
  },
  {
    path: '*',
    redirect: 'error-404',
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes,
})

router.beforeEach(async (to, from, next) => {
  const isLoggedIn = await store.dispatch('isLoggedIn')
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)

  const currentUser = store.getters.currentUser
  console.log("current user ", store.getters.currentUser);
  const allowedRoles = to.meta.roles


  if (requiresAuth && !isLoggedIn) {
    next({ name: 'pages-login' })
  } else if (isLoggedIn && (to.name === 'pages-login' || to.name === 'pages-register')) {
    // next({ name: 'dashboard' })
    const userRole = currentUser.role_id; // get the user's role
    if (userRole === undefined) {
      next('/login'); // redirect to login if user role is undefined
    } else if (userRole === 1) {
      next('/admin-dashboard'); // redirect to admin dashboard for role 1
    } else if (userRole === 2) {
      next('/registrar-dashboard'); // redirect to registrar for role 2
    } else if (userRole === 3) {
      next('/lecturer-dashboard'); // redirect to registrar for role 2
    }
    else if (userRole === 4) {
      next('/student'); // redirect to student dashboard for role 4
    } else if (userRole === 5) {
      next('/finance-dashboard'); // redirect to registrar for role 2
    } else if (userRole === 6) {
      next('/password-reset')
    } else if (userRole == 7) {
      next('/hod-dashboard')
    }else if(userRole===8){
      next('/compliance-dashboard');
    }
  }
  else if (allowedRoles && !allowedRoles.includes(currentUser.role_id)) { // will check this

    next('/error-404')
  }
  else {
    next()
  }
})

export default router
