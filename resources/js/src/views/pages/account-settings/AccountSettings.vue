<template>
  <v-card id="account-setting-card">
    <!-- tabs -->
    <v-tabs v-model="tab" show-arrows>
      <v-tab v-for="tab in tabs" :key="tab.icon">
        <v-icon size="20" class="me-3">
          {{ tab.icon }}
        </v-icon>
        <span>{{ tab.title }}</span>
      </v-tab>
    </v-tabs>

    <!-- tabs item -->
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <account-settings-account :account-data="accountSettingData.account"></account-settings-account>
      </v-tab-item>

      <v-tab-item>
        <account-settings-security></account-settings-security>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
import { mdiAccountOutline, mdiLockOpenOutline } from '@mdi/js'
import { ref, computed } from '@vue/composition-api'

// demos
import AccountSettingsAccount from './AccountSettingsAccount.vue'
import AccountSettingsSecurity from './AccountSettingsSecurity.vue'
import 'vuetify/dist/vuetify.min.css'

import store from '@/store'

export default {
  components: {
    AccountSettingsAccount,
    AccountSettingsSecurity,
  },
  setup() {
    const currentUser = computed(() => store.getters.currentUser)

    const tab = ref('')

    // tabs
    const tabs = [
      { title: 'Account', icon: mdiAccountOutline },
      { title: 'Security', icon: mdiLockOpenOutline },
    ]

    // account settings data
    const accountSettingData = {
      account: {
        // avatarImg: require(`@/assets/images/avatars/${currentUser.value.picture}`).default,
        // username: 'johnDoe',
        // firstname: 'john Doe',
        // lastname: 'john Doe',
        // email: 'johnDoe@example.com',
        id: currentUser.value.id, // this id enables us to update the user account
        username: currentUser.value.username,
        firstname: currentUser.value.firstname,
        lastname: currentUser.value.lastname,
        email: currentUser.value.email,
      },
    }

    return {
      currentUser,
      tab,
      tabs,
      accountSettingData,
      icons: {
        mdiAccountOutline,
        mdiLockOpenOutline,
      },
    }
  },
}
</script>
