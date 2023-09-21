<template>
  <v-menu offset-y left nudge-bottom="14" min-width="200" content-class="user-profile-menu-content">
    <template v-slot:activator="{ on, attrs }">
      <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
        <v-avatar size="40px" v-bind="attrs" v-on="on">
          <v-img :src="getImageUrl(user.picture)"></v-img>
        </v-avatar>
      </v-badge>
    </template>
    <v-list>
      <div class="pb-3 pt-2">
        <v-badge bottom color="success" overlap offset-x="12" offset-y="12" class="ms-4" dot>
          <v-avatar size="40px">
            <v-img :src="getImageUrl(user.picture)"></v-img>
          </v-avatar>
        </v-badge>
        <div class="d-inline-flex flex-column justify-center ms-3" style="vertical-align: middle">
          <span class="text--primary font-weight-semibold mb-n1">
            {{ user.firstname + ' ' + user.lastname }}
          </span>
          <small class="text--disabled text-capitalize">Admin</small>
        </div>
      </div>

      <v-divider class="my-2"></v-divider>

      <!-- Logout -->
      <v-list-item link @click="logout">
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            {{ icons.mdiLogoutVariant }}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
import {
  mdiAccountOutline,
  mdiEmailOutline,
  mdiCheckboxMarkedOutline,
  mdiChatOutline,
  mdiCogOutline,
  mdiCurrencyUsd,
  mdiHelpCircleOutline,
  mdiLogoutVariant,
} from '@mdi/js'
import store from '@/store'
import apiBaseURL from '../../../api-config'

export default {
  data() {
    return {
      user: '',
      icons: {
        mdiAccountOutline,
        mdiEmailOutline,
        mdiCheckboxMarkedOutline,
        mdiChatOutline,
        mdiCogOutline,
        mdiCurrencyUsd,
        mdiHelpCircleOutline,
        mdiLogoutVariant,
      },
    }
  },
  methods: {
    getImageUrl(filename) {
      // Use Laravel's asset function to generate the URL path
      return apiBaseURL + 'images/avatars/' + filename
    },
    async logout() {
      console.log('created')
      try {
        await this.$store.dispatch('logout')
        // Redirect to login page after logout
        window.location.href = '/login'
      } catch (error) {
        console.error(error)
      }
    },
  },

  watch: {
    getUserProfile: function () {
      this.user = this.getUserProfile
      console.log('user ', this.user)
    },
  },

  mounted() {
    this.$store.dispatch('userProfile')
  },
  computed: {
    getUserProfile() {
      //final output from here
      return this.$store.getters.getUserProfile
    },
  },
}
</script>

<style lang="scss">
.user-profile-menu-content {
  .v-list-item {
    min-height: 2.5rem !important;
  }
}
</style>
