<template>
  <v-list-item class="vertical-nav-menu-link" v-bind="$attrs" active-class="bg-gradient-primary white--text">
    <v-list-item-icon>
      <v-icon :class="{ 'alternate-icon-small': !icon }" class="mx-auto">
        {{ icon || alternateIcon }}
      </v-icon>
    </v-list-item-icon>

    <v-list-item-title>
      {{ title }}
    </v-list-item-title>
  </v-list-item>
</template>

<script>
import { mdiCheckboxBlankCircleOutline } from '@mdi/js'

export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      default: undefined,
    },
  },
  setup() {
    return {
      alternateIcon: mdiCheckboxBlankCircleOutline,
    }
  },
}
</script>

<style lang="scss" scoped>
@import '@resources/sass/preset/mixins.scss';
.alternate-icon-small {
  font-size: 10px;
  height: 10px;
  width: 10px;
}

.vertical-nav-menu-link {
  &.v-list-item--active {
    box-shadow: 0 5px 10px -4px rgba(94, 86, 105, 0.42);
    @include elevationTransition();
  }
}
</style>
