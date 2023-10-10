<template>
  <!-- Navbar -->
  <nav
    class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start sticky top-[1%] backdrop-saturate-[200%] backdrop-blur-[30px] bg-[hsla(0,0%,100%,0.8)] shadow-blur z-110"
    navbar-main navbar-scroll="true">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto">
      <div class="flex items-center">
        <div class="flex items-center md:ml-auto md:pr-4">
          <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
            <span
              class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
              <MagnifyingGlassIcon class="h-4 w-5 text-gray-500" />
            </span>
            <input type="text"
              class="pl-8.75 text-sm focus:shadow-soft-primary-outline ease-soft w-1/100 leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-fuchsia-300 focus:outline-none focus:transition-shadow"
              placeholder="Type here..." />
          </div>
        </div>
      </div>
      <div>
        <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
          <li class="flex items-center">
            <Menu as="div" class="relative ml-3">
              <div>
                <MenuButton
                  class="relative flex rounded-full text-sm focus:outline-none focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">Open user menu</span>
                  <div class="font-medium dark:text-white text-left pl-4 pr-3">
                    <div>{{ currentUser }} <span v-if="currentOrganization"
                        class="bg-blue-100 text-blue-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">({{
                          currentOrganization }})</span></div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ userRole }}</div>
                  </div>
                  <img class="h-8 w-8 rounded-full"
                    src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                    alt="" />
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
                <MenuItems
                  class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                  <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Your
                    Profile</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                  <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }" @click.prevent="toggleSubMenu">
                  <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">
                    {{ currentLangugeLocale.name }}
                    <img :src="currentLangugeLocale.flag" class="w-4 h-4 inline-block mr-2" alt="">
                    <span class="ml-2">&#9660;</span></a>
                  </MenuItem>
                  <div v-if="showSubMenu"
                    class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                    <ul>
                      <li v-for="(country, code) in countries" :key="code" :class="{ 'bg-gray-100': i18n.locale.value === code }">
                        <a @click="selectLanguage(code)" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">
                          <img :src="country.flag" class="w-4 h-4 inline-block mr-2" alt="">
                          {{ country.name }}
                        </a>
                      </li>
                    </ul>
                  </div>
                  <MenuItem v-slot="{ active }">
                  <a @click="signOut()"
                    :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </li>
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
  </nav>
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
      fr: {
        flag: "media/flags/france.svg",
        name: "French",
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

    const currentUser = computed(() => {
      const user = store.user;
      return typeof user?.name != 'undefined' ? user?.name : "-";
    });

    const userRole = computed(() => {
      const user = store.user;
      return typeof user?.userRole != 'undefined' ? user?.userRole : "-";
    });

    const currentOrganization = computed(() => {
      const organization = store.organization;
      return typeof organization?.name != 'undefined' ? organization?.name : "-";
    });

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
      return countries[i18n.locale.value];
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
      i18n
    }
  },
});
</script>