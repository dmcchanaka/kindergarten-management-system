<template>
  <!-- Navbar -->
  <div class="flex items-center justify-between w-full px-4 py-1 mx-auto">
      <div class="flex items-center">
        <div class="flex items-center md:ml-auto md:pr-4">
          <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
            
          </div>
        </div>
      </div>
      <div>
        <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
          <li class="flex items-center pl-4 xl:hidden">
            <a href="javascript:;" class="block p-0 transition-all ease-nav-brand text-slate-500" sidenav-trigger
              @click.prevent="toggleSideBar">
              <div class="w-4.5 overflow-hidden">
                <Bars3Icon
                  class="w-5 text-sm mb-1 ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all" />
                <Bars3Icon
                  class="w-5 text-sm mb-1 ease-soft mb-0.75 relative block h-0.5 rounded-sm bg-slate-500 transition-all" />
                <Bars3Icon
                  class="w-5 text-sm mb-1 ease-soft relative block h-0.5 rounded-sm bg-slate-500 transition-all" />
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  <!-- end Navbar -->
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent, computed, ref } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { MagnifyingGlassIcon, UserCircleIcon, HomeIcon, Bars3Icon } from '@heroicons/vue/24/solid';
import { useRoute, useRouter } from "vue-router";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { useAuthStore } from "@/stores/auth";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "top-bar",
  props: {},
  components: {
    UserCircleIcon,
    MagnifyingGlassIcon,
    HomeIcon,
    Bars3Icon,
    Menu,
    MenuButton,
    MenuItems,
    MenuItem
  },
  setup(props, { emit }) {
    const route = useRoute();
    const router = useRouter();
    const store = useAuthStore();
    const i18n = useI18n();

    const showSubMenu = ref(false);

    //languages with country flags
    const countries = {
      en: {
        flag: "media/flags/united-states.svg",
        name: "English",
      },
      de: {
        flag: "media/flags/germany.svg",
        name: "German",
      },
    };

    i18n.locale.value = localStorage.getItem("lang")
      ? (localStorage.getItem("lang") as string)
      : "en";

    const signOut = () => {
      store.logout();
      router.push({ name: "sign-in" });
    };

    const pageTitle = computed(() => {
      return route.meta.pageTitle;
    });

    const breadcrumbs = computed(() => {
      return route.meta.breadcrumbs;
    });

    const currentUser = computed(() => store.user?.name || "-");
    const userRole = computed(() => store.user?.userRole || "-");
    const currentOrganization = computed(() => store.organization?.name || "");
    const userProfileImage = computed(() => store.user?.logo || '/media/avatar/avatar.png');

    const toggleSideBar = () => {
      emit('sidebarToggle')
    }

    //show language sub menu
    const toggleSubMenu = () => {
      showSubMenu.value = !showSubMenu.value;
    };

    //select induvidual language
    const selectLanguage = (lang) => {
      showSubMenu.value = false;
      localStorage.setItem("lang", lang);
      i18n.locale.value = lang;
    };

    //get current language object 
    const currentLangugeLocale = computed(() => {
      const locale = countries[i18n.locale.value];

      if (!locale) {
          localStorage.setItem("lang", "en");
          i18n.locale.value = "en";
          return countries['en'];
      }
      return locale;
    });

    return {
      getAssetPath,
      pageTitle,
      breadcrumbs,
      currentUser,
      userRole,
      signOut,
      toggleSideBar,
      currentOrganization,
      countries,
      toggleSubMenu,
      showSubMenu,
      selectLanguage,
      currentLangugeLocale,
      i18n,
      userProfileImage
    }
  },
});
</script>