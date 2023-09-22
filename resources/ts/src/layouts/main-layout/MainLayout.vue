<template>
  <div :style="{ backgroundColor: backgroundColor }" class="min-h-screen m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
    <Aside @close-sidebar="closeSideBar" :class="{ 'translate-x-0': isSidebarOpen, 'shadow-soft-xl': isSidebarOpen }" :style="{ 'margin-left': isSidebarOpen ? null : '0rem' }"></Aside>
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 pt-2">
      <Topbar @sidebarToggle="sidebarToggle"></Topbar>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="lg:flex lg:items-center lg:justify-between">
          <div class="flex-1 min-w-0">
            <nav>
              <!-- breadcrumb -->
              <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="leading-normal text-sm">
                  <router-link to="/dashboard" class="text-slate-700 text-sm"><fa icon="home" class="h-3 w-4 text-sm mb-0.5" />&nbsp;Home</router-link>
                </li>
                <template v-for="(item, i) in breadcrumbs" :key="i">
                  <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">{{ item }}</li>
                </template>
              </ol>
              <h5 class="mb-0 font-bold capitalize">{{ pageTitle }}</h5>
            </nav>
          </div>
          <div class="flex mt-5 lg:ml-4 lg:mt-0">

          </div>
        </div>
        <router-view></router-view>
        <!-- Footer -->
        <Footer></Footer>
        <!-- Footer -->
      </div>
    </main>
  </div>
</template>
<style lang="scss">
</style>

<script lang="ts">
import { defineComponent, computed, ref, onMounted } from "vue";
import { useRoute } from "vue-router";

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
    const route = useRoute();
    const store = useSettingsStore();

    const backgroundColor = computed(()=> {
      return store.backgroundColor || '';
    });

    const isSidebarOpen = ref(false);

    const sidebarToggle = () => {
      isSidebarOpen.value = !isSidebarOpen.value;
    }

    const pageTitle = computed(() => {
      return route.meta.pageTitle;
    });

    const breadcrumbs = computed(() => {
      return route.meta.breadcrumbs;
    });

    const closeSideBar = () => {
      isSidebarOpen.value = !isSidebarOpen.value;
    }

    //apply background color to page body
    onMounted(() => {
      document.documentElement.style.backgroundColor = backgroundColor.value;
      document.body.style.backgroundColor = backgroundColor.value;
    });

    return {
      backgroundColor,
      sidebarToggle,
      isSidebarOpen,
      pageTitle,
      breadcrumbs,
      closeSideBar
    }
  },
});
</script>