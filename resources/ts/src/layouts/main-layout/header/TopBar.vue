<template>
  <!-- Navbar -->
  <nav
    class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start sticky top-[1%] backdrop-saturate-[200%] backdrop-blur-[30px] bg-[hsla(0,0%,100%,0.8)] shadow-blur z-110"
    navbar-main navbar-scroll="true">
    <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
      <nav>
        <!-- breadcrumb -->
        <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
          <li class="leading-normal text-sm">
            <router-link to="/" class="opacity-50 text-slate-700">Home</router-link>
          </li>
          <template v-for="(item, i) in breadcrumbs" :key="i">
            <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">{{ item }}</li>
          </template>
        </ol>
        <h5 class="mb-0 font-bold capitalize">{{ pageTitle }}</h5>
      </nav>

      <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
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
        <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
          <li class="flex items-center">
            <!-- <a href="../pages/sign-in.html"
              class="block px-0 py-2 font-semibold transition-all ease-nav-brand text-sm text-slate-500">
              <UserCircleIcon class="h-6 w-6 text-black-500" />&nbsp;
              <span class="hidden sm:inline">Chanaka</span>
            </a> -->
            <Menu as="div" class="relative ml-3">
              <div>
                <MenuButton class="relative flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                  <span class="absolute -inset-1.5" />
                  <span class="sr-only">Open user menu</span>
                  <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                  <div class="font-medium dark:text-white text-left pl-4">
                    <div>{{ currentUser }}</div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ userRole }}</div>
                </div>
                </MenuButton>
              </div>
              <transition enter-active-class="transition ease-out duration-100" enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100" leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
                <MenuItems class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Your Profile</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Settings</a>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <a @click="signOut()" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Sign out</a>
                  </MenuItem>
                </MenuItems>
              </transition>
            </Menu>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- end Navbar -->
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { MagnifyingGlassIcon, UserCircleIcon } from '@heroicons/vue/24/solid';
import { useRoute, useRouter } from "vue-router";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { useAuthStore } from "@/stores/auth";

export default defineComponent({
  name: "top-bar",
  components: {
    UserCircleIcon,
    MagnifyingGlassIcon,
    Menu,
    MenuButton,
    MenuItems,
    MenuItem
  },
  setup() {
    const route = useRoute();
    const router = useRouter();
    const store = useAuthStore();

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

    return {
      getAssetPath,
      pageTitle,
      breadcrumbs,
      currentUser,
      userRole,
      signOut
    }
  },
});
</script>