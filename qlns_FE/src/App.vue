<template>
  <component :is="layout" />
</template>

<script>
<<<<<<< HEAD
import authService from "./services/authService";

=======
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
const default_layout = "default";
export default {
  computed: {
    layout() {
<<<<<<< HEAD
      const layoutName = this.$route.meta.layout || authService.getLayoutByRole() || default_layout;
      return layoutName + "-layout";
=======
      return (this.$route.meta.layout || default_layout) + "-layout";
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
    },
  },
};
</script>
<<<<<<< HEAD
<style></style>
=======
<style></style>
>>>>>>> bd6a448a20c0da39ab6ee7709dfe60e1a3097dbe
