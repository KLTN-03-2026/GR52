<template>
  <component :is="layout" />
</template>

<script>
import authService from "./services/authService";

const default_layout = "default";
export default {
  computed: {
    layout() {
      const layoutName = this.$route.meta.layout || authService.getLayoutByRole() || default_layout;
      return layoutName + "-layout";
    },
  },
};
</script>
<style></style>
