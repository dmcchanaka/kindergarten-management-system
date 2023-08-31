<template>
    <Aside></Aside>
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 mt-4">
      <Topbar></Topbar>

      <div class="w-full px-6 py-6 mx-auto">
        <router-view></router-view>
      </div>
      <!-- Footer -->
      <Footer></Footer>
      <!-- Footer -->
    </main>
    <!--  -->
</template>
<style lang="scss">
</style>

<script lang="ts">
import { defineComponent } from "vue";

import Aside from "@/layouts/main-layout/aside/Aside.vue";
import Topbar from "@/layouts/main-layout/header/TopBar.vue";
import Footer from "@/layouts/main-layout/footer/Footer.vue";

export default defineComponent({
  name: "main-layout",
  components: {
    Aside,
    Topbar,
    Footer
  },
  setup() {
  },
});
</script>