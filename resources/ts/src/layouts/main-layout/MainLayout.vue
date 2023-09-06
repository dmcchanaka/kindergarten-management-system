<template>
  <div :style="{ backgroundColor: backgroundColor }" class="min-h-screen m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500" style="margin-top: -20px;">
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
  </div>
</template>
<style lang="scss">
</style>

<script lang="ts">
import { defineComponent, computed } from "vue";

import Aside from "@/layouts/main-layout/aside/Aside.vue";
import Topbar from "@/layouts/main-layout/header/TopBar.vue";
import Footer from "@/layouts/main-layout/footer/Footer.vue";

import { useSettingsStore, type UiSettings } from "@/stores/settings";

export default defineComponent({
  name: "main-layout",
  components: {
    Aside,
    Topbar,
    Footer
  },
  setup() {
    const store = useSettingsStore();

    const backgroundColor = computed(()=> {
      return store.backgroundColor;
    });

    return {
      backgroundColor
    }
  },
});
</script>