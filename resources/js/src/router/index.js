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
      roles: [2],
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
      roles: [2],
    }
  },
  {
    path: '/view-gradings',
    name: 'view-gradings',
    component: () => import('@/views/Registrar/gradings/ViewGradings.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-departments',
    name: 'view-departments',
    component: () => import('@/views/Registrar/departments/ViewDepartments.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-program-durations',
    name: 'view-program-durations',
    component: () => import('@/views/Registrar/program-duration/ViewProgramDurations.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-programs',
    name: 'view-programs',
    component: () => import('@/views/Registrar/programs/ViewPrograms.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-courses',
    name: 'view-courses',
    component: () => import('@/views/Registrar/courses/ViewCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-admission-codes-locations',
    name: 'view-admission-codes-locations',
    component: () => import('@/views/Registrar/admission-codes-locations/AdmissionCodesLocations.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-admission-codes/:id',
    name: 'view-admission-codes',
    component: () => import('@/views/Registrar/admission-codes/AdmissionCodes.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-sessions',
    name: 'view-sessions',
    component: () => import('@/views/Registrar/sessions/ViewSessions.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-semesters',
    name: 'view-semesters',
    component: () => import('@/views/Registrar/semesters/ViewSemesters.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
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
      next('/registrar-dashboard'); // redirect to registrar dashboard for other roles
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
