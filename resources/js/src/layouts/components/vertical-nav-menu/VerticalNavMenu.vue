<template>
  <v-navigation-drawer :value="isDrawerOpen" app floating width="260" height="100%" class="app-navigation-menu"
    :right="$vuetify.rtl" @input="val => $emit('update:is-drawer-open', val)">
    <!-- Navigation Header -->
    <div class="vertical-nav-header d-flex items-center ps-10 pe-5 pt-5 pb-2">
      <router-link to="#" class="d-flex align-center text-decoration-none">
        <v-img :src="require('@/assets/images/logos/mdi_logo_square.png').default" max-height="165px" max-width="165px"
          alt="logo" contain eager class="app-logo me-3"
          style="border-radius: 50%; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); transform: translateY(-2px)"></v-img>
      </router-link>
    </div>

    <!-- Navigation Items -->
    <v-list expand shaped class="vertical-nav-menu-items pr-5">
      <nav-menu-link v-if="currentUser && currentUser.role_id == '1'" title="Dashboard"
        :to="{ name: 'admin-dashboard' }" :icon="icons.mdiHomeOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '2'" title="Dashboard"
        :to="{ name: 'registrar-dashboard' }" :icon="icons.mdiHomeOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '3'" title="Dashboard"
        :to="{ name: 'lecturer-dashboard' }" :icon="icons.mdiHomeOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '5'" title="Dashboard"
        :to="{ name: 'finance-dashboard' }" :icon="icons.mdiHomeOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '7'" title="Dashboard" :to="{ name: 'hod-dashboard' }"
        :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '8'" title="Dashboard"
        :to="{ name: 'compliance-dashboard' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
      <nav-menu-link v-if="
        currentUser &&
        (currentUser.role_id == '1' ||
          currentUser.role_id == '8' ||
          currentUser.role_id == '2' ||
          currentUser.role_id == '3' ||
          currentUser.role_id == '4' ||
          currentUser.role_id == '5' ||
          currentUser.role_id == '7' ||
          (currentUser.role_id == '6' && userInfo.password_reset == 1))
      " title="Account Settings" :to="{ name: 'pages-account-settings' }"
        :icon="icons.mdiAccountCogOutline"></nav-menu-link>
      <nav-menu-group v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Gradings"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Add Grading" :to="{ name: 'add-grading' }"></nav-menu-link>
        <nav-menu-link title="View Gradings" :to="{ name: 'view-gradings' }"></nav-menu-link>
      </nav-menu-group>
      <nav-menu-group v-if="currentUser && (currentUser.role_id == '8')" title="Gradings"
        :icon="icons.mdiFileOutline">
     
        <nav-menu-link title="View Gradings" :to="{ name: 'view-compliance-gradings' }"></nav-menu-link>
      </nav-menu-group>
      <nav-menu-group v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Programs"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Departments" :to="{ name: 'view-departments' }"></nav-menu-link>
        <nav-menu-link title="Program Durations" :to="{ name: 'view-program-durations' }"></nav-menu-link>
        <nav-menu-link title="Programs" :to="{ name: 'view-programs' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-group v-if="currentUser && (currentUser.role_id == '8')" title="Programs"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Departments" :to="{ name: 'view-compliance-departments' }"></nav-menu-link>
        <nav-menu-link title="Program Durations" :to="{ name: 'view-program-compliance-durations' }"></nav-menu-link>
        <nav-menu-link title="Programs" :to="{ name: 'view-compliance-programs' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')"
        title="Course List" :to="{ name: 'view-courses' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        
      <nav-menu-link v-if="currentUser && (currentUser.role_id == '8' )"
        title="Course List" :to="{ name: 'view-compliance-courses' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')"
        title="Approve Marks" :to="{ name: 'approve-marks' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <!-- ///////////////////// ADMIN ROUTES /////////////////////////// -->
      <nav-menu-group v-if="currentUser && currentUser.role_id == '1' " title="Manage Users"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Users" :to="{ name: 'view-users' }"></nav-menu-link>
        <nav-menu-link title="Logs" :to="{ name: 'view-activities' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-group v-if="currentUser &&  currentUser.role_id == '8'  " title="Manage Users"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Users" :to="{ name: 'view-compliance-users' }"></nav-menu-link>
        <nav-menu-link title="Logs" :to="{ name: 'view-compliance-activities' }"></nav-menu-link>
      </nav-menu-group>
      
      <nav-menu-link
        v-if="currentUser && (currentUser.role_id == '5' || currentUser.role_id == '2' || currentUser.role_id == '1' )"
        title="Admission Codes" :to="{ name: 'view-admission-codes-locations' }"
        :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link
        v-if="currentUser && (currentUser.role_id == '8' )"
        title="Admission Codes" :to="{ name: 'view-compliance-admission-codes-locations' }"
        :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link
        v-if="currentUser && (currentUser.role_id == '6' || currentUser.role_id == '1') && userInfo.password_reset == 1"
        title="Admission Codes" :to="{ name: 'view-admission-codes-locations' }"
        :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
        
      <nav-menu-link v-if="currentUser && currentUser.role_id == '6' && userInfo.password_reset == 0"
        title="Reset Password" :to="{ name: 'password-reset' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Sessions"
        :to="{ name: 'view-sessions' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        

        <nav-menu-link v-if="currentUser && ( currentUser.role_id == '8' )" title="Sessions"
        :to="{ name: 'view-compliance-sessions' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        
      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Semesters"
        :to="{ name: 'view-semesters' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Locations"
        :to="{ name: 'view-locations' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && (currentUser.role_id == '8')" title="Locations"
        :to="{ name: 'view-compliance-locations' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && ( currentUser.role_id == '8' )" title="Semesters"
        :to="{ name: 'view-compliance-semesters' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1' )" title="Lecturers"
        :to="{ name: 'view-lecturers' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && (currentUser.role_id == '8')" title="Lecturers"
        :to="{ name: 'view-compliance-lecturers' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
        
      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Students"
        :to="{ name: 'view-students' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && (currentUser.role_id == '8')" title="Students"
        :to="{ name: 'view-compliance-students' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
        
      <nav-menu-group v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1'  )"
        title="Applications" :icon="icons.mdiFileOutline">
        <nav-menu-link title="Incoming Applications" :to="{ name: 'view-incoming-applications' }"></nav-menu-link>
        <nav-menu-link title="Accepted Applications" :to="{ name: 'view-accepted-applications' }"></nav-menu-link>
        <nav-menu-link title="Rejected Applications" :to="{ name: 'view-rejected-applications' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-group v-if="currentUser && (currentUser.role_id == '8' )"
        title="Applications" :icon="icons.mdiFileOutline">
        <nav-menu-link title="Incoming Applications" :to="{ name: 'view-compliance-incoming-applications' }"></nav-menu-link>
        <nav-menu-link title="Accepted Applications" :to="{ name: 'view-compliance-accepted-applications' }"></nav-menu-link>
        <nav-menu-link title="Rejected Applications" :to="{ name: 'view-compliance-rejected-applications' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '4' && userInfo.is_applicant == 0" title="Student"
        :to="{ name: 'student' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
      <nav-menu-link v-if="currentUser && currentUser.role_id == '4' && userInfo.is_applicant == 1" title="Applicant"
        :to="{ name: 'student' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-group v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Settings"
        :icon="icons.mdiFileOutline">
        <nav-menu-link title="Admissions" :to="{ name: 'admission-status' }"></nav-menu-link>
        <nav-menu-link title="Registrations" :to="{ name: 'registration-status' }"></nav-menu-link>
        <nav-menu-link title="Matriculation Modifications" :to="{ name: 'matriculation-status' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '2' || currentUser.role_id == '1')" title="Deferments"
        :to="{ name: 'view-deferments' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && (currentUser.role_id == '5' || currentUser.role_id == '1' )"
        title="Student Fees" :to="{ name: 'view-student-fees' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && ( currentUser.role_id == '8')"
        title="Student Fees" :to="{ name: 'view-compliance-student-fees' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
      
      <nav-menu-link v-if="currentUser && (currentUser.role_id == '5' || currentUser.role_id == '1')" title="Agents"
        :to="{ name: 'view-agents' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

        <nav-menu-link v-if="currentUser && ( currentUser.role_id == '8')" title="Agents"
        :to="{ name: 'view-compliance-agents' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '3'" title="Student Marks"
        :to="{ name: 'manage-student-marks' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '3'" title="Courses" :to="{ name: 'lecturer-courses' }"
        :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <!-- // hod menus // -->

      <nav-menu-link v-if="currentUser && currentUser.role_id == '7'" title="Course List"
        :to="{ name: 'view-hod-courses' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '7'" title="Approve Marks"
        :to="{ name: 'approve-marks-hod' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '7' " title="Lecturers"
        :to="{ name: 'view-hod-lecturers' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <nav-menu-group v-if="currentUser && currentUser.role_id == '7'" title="Applications"
        :icon="icons.mdiFileOutline">
        <nav-menu-link v-if="currentUser && currentUser.role_id != '8' " title="Incoming Applications" :to="{ name: 'view-incoming-applications' }"></nav-menu-link>
        <nav-menu-link title="Accepted Applications" :to="{ name: 'view-accepted-applications' }"></nav-menu-link>
        <nav-menu-link title="Rejected Applications" :to="{ name: 'view-rejected-applications' }"></nav-menu-link>
      </nav-menu-group>

      <nav-menu-link v-if="currentUser && currentUser.role_id == '7'" title="Students"
        :to="{ name: 'view-hod-students' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>

      <!-- <nav-menu-section-title title="USER INTERFACE"></nav-menu-section-title> -->
      <!-- <nav-menu-link title="Typography" :to="{ name: 'typography' }" :icon="icons.mdiAlphaTBoxOutline"></nav-menu-link>
      <nav-menu-link title="Icons" :to="{ name: 'icons' }" :icon="icons.mdiEyeOutline"></nav-menu-link>
      <nav-menu-link title="Cards" :to="{ name: 'cards' }" :icon="icons.mdiCreditCardOutline"></nav-menu-link>
      <nav-menu-link title="Tables" :to="{ name: 'simple-table' }" :icon="icons.mdiTable"></nav-menu-link>
      <nav-menu-link title="Form Layouts" :to="{ name: 'form-layouts' }" :icon="icons.mdiFormSelect"></nav-menu-link> -->
    </v-list>
  </v-navigation-drawer>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import {
  mdiHomeOutline,
  mdiAlphaTBoxOutline,
  mdiEyeOutline,
  mdiCreditCardOutline,
  mdiTable,
  mdiFileOutline,
  mdiFormSelect,
  mdiAccountCogOutline,
} from '@mdi/js'
import NavMenuSectionTitle from './components/NavMenuSectionTitle.vue'
import NavMenuGroup from './components/NavMenuGroup.vue'
import NavMenuLink from './components/NavMenuLink.vue'
import { mapGetters } from 'vuex'
import store from '@/store'

export default {
  computed: {
    ...mapGetters(['currentUser']),
    getUserProfile() {
      return this.$store.getters.getUserProfile
    },
  },
  data() {
    return {
      userInfo: '',
    }
  },
  components: {
    NavMenuSectionTitle,
    NavMenuGroup,
    NavMenuLink,
  },
  props: {
    isDrawerOpen: {
      type: Boolean,
      default: null,
    },
  },
  setup() {
    const currentUser = store.getters.currentUser
    console.log('current users', currentUser)

    return {
      icons: {
        mdiHomeOutline,
        mdiAlphaTBoxOutline,
        mdiEyeOutline,
        mdiCreditCardOutline,
        mdiTable,
        mdiFileOutline,
        mdiFormSelect,
        mdiAccountCogOutline,
        currentUser,
      },
    }
  },

  watch: {
    getUserProfile: function () {
      this.userInfo = this.getUserProfile
      console.log('here', this.userInfo)
      this.education = this.userInfo.education
      this.certificates = this.userInfo.certificates
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  // computed: {
  //   getUserProfile() {
  //     //final output from here
  //     return this.$store.getters.getUserProfile
  //   },
  // },
}
</script>

<style lang="scss" scoped>
@import '@resources/sass/preset/mixins.scss';

.app-title {
  font-size: 1.25rem;
  font-weight: 700;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: 0.3px;
}

// ? Adjust this `translateX` value to keep logo in center when vertical nav menu is collapsed (Value depends on your logo)
.app-logo {
  transition: all 0.18s ease-in-out;

  .v-navigation-drawer--mini-variant & {
    transform: translateX(-4px);
  }
}

@include theme(app-navigation-menu) using ($material) {
  background-color: map-deep-get($material, 'background');
}

// @include theme(app-navigation-menu) using ($material) {
//   background-color: rgb(206, 211, 213);
// }

.app-navigation-menu {
  .v-list-item {
    &.vertical-nav-menu-link {
      ::v-deep .v-list-item__icon {
        .v-icon {
          transition: none !important;
        }
      }
    }
  }
}

// You can remove below style
// Upgrade Banner
.app-navigation-menu {
  .upgrade-banner {
    position: absolute;
    bottom: 13px;
    left: 50%;
    transform: translateX(-50%);
  }
}
</style>
