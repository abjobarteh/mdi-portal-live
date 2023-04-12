import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '@/store'


Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    redirect: 'admin-dashboard',
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
    component: () => import('@/views/employees/AddEmployee.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },
  {
    path: '/view-employees',
    name: 'view-employees',
    component: () => import('@/views/employees/ViewEmployees.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },
  {
    path: '/add-grading',
    name: 'add-grading',
    component: () => import('@/views/gradings/AddGrading.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },
  {
    path: '/view-gradings',
    name: 'view-gradings',
    component: () => import('@/views/gradings/ViewGradings.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }
  },

  {
    path: '/view-departments',
    name: 'view-departments',
    component: () => import('@/views/departments/ViewDepartments.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-program-durations',
    name: 'view-program-durations',
    component: () => import('@/views/program-duration/ViewProgramDurations.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-programs',
    name: 'view-programs',
    component: () => import('@/views/programs/ViewPrograms.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-courses',
    name: 'view-courses',
    component: () => import('@/views/courses/ViewCourses.vue'),
    meta: {
      requiresAuth: true,
      roles: [2],
    }

  },

  {
    path: '/view-users',
    name: 'view-users',
    component: () => import('@/views/users/ViewUsers.vue'),
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
    next({ name: 'dashboard' })
  }
  else if (allowedRoles && !allowedRoles.includes(currentUser.role_id)) {
    next('/error-404')
  }
  else {
    next()
  }
})

export default router
