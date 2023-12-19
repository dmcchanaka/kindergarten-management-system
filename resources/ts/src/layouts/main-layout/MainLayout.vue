<template>
  <div :style="{ backgroundColor: backgroundColor }" class="min-h-screen m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
    <Aside @close-sidebar="closeSideBar" :class="{ 'translate-x-0': isSidebarOpen, 'shadow-soft-xl': isSidebarOpen }" :style="{ 'margin-left': isSidebarOpen ? null : '0rem' }"></Aside>
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full min-h-screen rounded-xl transition-all duration-200 pt-2">
      <Topbar @sidebarToggle="sidebarToggle"></Topbar>

      <div class="w-full px-6 py-6 mx-auto">
        <div class="lg:flex lg:items-center lg:justify-between">
          <div class="flex-1 min-w-0">
            <nav>
              <!-- breadcrumb -->
              <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                <li class="leading-normal text-sm">
                  <router-link to="/dashboard" class="text-slate-700 text-sm"><fa icon="home" class="h-3 w-4 text-sm mb-0.5" />&nbsp;{{ translate('home') }}</router-link>
                </li>
                <template v-for="(item, i) in breadcrumbs" :key="i">
                  <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">{{ translate(item as string) }}</li>
                </template>
              </ol>
              <h5 class="text-header mb-0 font-bold capitalize">{{ translate(pageTitle as string) }}</h5>
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
import { defineComponent, computed, ref, onMounted, watch, watchEffect } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useI18n } from "vue-i18n";

import Aside from "@/layouts/main-layout/aside/Aside.vue";
import Topbar from "@/layouts/main-layout/header/TopBar.vue";
import Footer from "@/layouts/main-layout/footer/Footer.vue";

import { useSettingsStore, type UiSettings } from "@/stores/settings";
import { useAuthStore } from "@/stores/auth";

export default defineComponent({
  name: "main-layout",
  components: {
    Aside,
    Topbar,
    Footer
  },
  setup() {
    const { t, te } = useI18n();
    const route = useRoute();
    const router = useRouter();
    const store = useSettingsStore();
    const authStore = useAuthStore();

    const translate = (text: string) => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    const currentRoute = ref('');

    const backgroundColor = computed(()=> {
      return store.generalSettings?.backgroundColor || '';
    });

    //apply background color to page body
    onMounted(() => {
      document.documentElement.style.backgroundColor = backgroundColor.value;
      document.body.style.backgroundColor = backgroundColor.value;
    });

    const computedUserPermisions = computed(()=> {
      return authStore.userPermissions || {};
    });

    watchEffect(() => {
      currentRoute.value = route.name as string;
      const userPermissions = computedUserPermisions.value;

      // Check if the user has permission to access the current route
      if (!userHasPermission(userPermissions, currentRoute.value)) {
        router.push({ name: 'dashboard' });
      }
      
    });

    const computedTextColor = computed(()=> {
      return store.generalSettings?.textColor || '';
    });
    document.documentElement.style.setProperty('--custom-text-color', computedTextColor.value);

    const computedHeaderColor = computed(()=> {
      return store.generalSettings?.headerColor || '';
    });
    document.documentElement.style.setProperty('--custom-header-color', computedHeaderColor.value);

    watch(
      [() => computedHeaderColor.value, () => computedTextColor.value],
      ([newHeaderColor, newTextColor]) => {
        document.documentElement.style.setProperty('--custom-header-color', newHeaderColor);
        document.documentElement.style.setProperty('--custom-text-color', newTextColor);
      }
    );

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

    function userHasPermission(userPermissions, requiredPermission) {
        return userPermissions.some(permission => permission.name === requiredPermission);
    }

    return {
      backgroundColor,
      sidebarToggle,
      isSidebarOpen,
      pageTitle,
      breadcrumbs,
      closeSideBar,
      translate
    }
  },
});
</script>