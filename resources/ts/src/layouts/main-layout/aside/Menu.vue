<template>
  <div class="items-center block w-auto max-h-screen h-sidenav grow basis-full">
    <ul class="flex flex-col pl-0 mb-0">
      <li class="mt-0.5 w-full" v-for="(item, i) in mainMenu" :key="i">
        <router-link v-if="item.route"
          class="py-1.5 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors"
          :to="item.route" @click.prevent="closeSideBar">
          <div class="flex flex-col items-center justify-center w-full">
            <div>
              <fa :class="{ 'text-3xl text-blue-500': currentActive(item.route), 'text-lg': !currentActive(item.route) }"
                class="text-white text-lg" :icon="item.icon" />
            </div>
            <div><span :class="{ 'text-base text-blue-500': currentActive(item.route), '': !currentActive(item.route) }"
                class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft text-white">{{ translate(item.heading) }}</span>
            </div>
          </div>
        </router-link>
      </li>
    </ul>
    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
    <!--user profile-->
    <div class="flex flex-col items-center justify-center w-full">
      <router-link to="my-profile">
        <img class="h-12 w-12 rounded-full" :src="userProfileImage" alt="" @click.prevent="closeSideBar" />
      </router-link>
    </div>
    <div class="flex flex-col items-center justify-center w-full">
      <span class="mt-2 duration-300 opacity-100 pointer-events-none ease-soft text-white text-xl">{{ currentUser
      }}</span>
    </div>
    <div class="flex flex-col items-center justify-center w-full">
      <span class="mt-0 duration-300 opacity-100 pointer-events-none ease-soft text-white">{{ userRole }}</span>
      <span class="mt-0 duration-300 opacity-100 pointer-events-none ease-soft text-white" style="font-size: 10px;">{{ currentOrganization }}</span>
    </div>
    <div class="flex items-center justify-center w-full">
      <span class="mt-4 duration-300 opacity-100 cursor-pointer ease-soft text-white space-x-4">
        <router-link to="my-profile" @click.prevent="closeSideBar">
          <fa icon="gear" class="text-2xl" />
        </router-link>
        <a @click="signOut()">
          <fa icon="power-off" class="text-2xl" />
        </a>
      </span>

    </div>
    <div class="flex flex-col items-center justify-center w-full">
      <ul class="flex flex-row justify-end pl-0 mb-0 list-none mt-2">
        <li class="flex items-center">
          <Menu as="div" class="relative">
            <MenuItem v-slot="{ active }" @click.prevent="toggleSubMenu">
            <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700 border rounded-md']">
              {{ currentLangugeLocale.name.value }}
              <img :src="currentLangugeLocale.flag" class="w-4 h-4 inline-block mr-2" alt="">
              <span class="ml-2">&#9660;</span></a>
            </MenuItem>
            <div v-if="showSubMenu"
              class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
              <ul>
                <li v-for="(country, code) in countries" :key="code"
                  :class="{ 'bg-gray-100': i18n.locale.value === code }">
                  <a @click="selectLanguage(code)" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 cursor-pointer">
                    <img :src="country.flag" class="w-4 h-4 inline-block mr-2" alt="">
                    {{ country.name.value }}
                  </a>
                </li>
              </ul>
            </div>
          </Menu>
        </li>
      </ul>
    </div>
    <div>&nbsp;</div>
  </div>

  <div>

  </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import MainMenuConfig from "@/core/config/MainMenuConfig";
import { useRoute, useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";
import { useI18n } from "vue-i18n";

import { MagnifyingGlassIcon, UserCircleIcon, HomeIcon, Bars3Icon } from '@heroicons/vue/24/solid';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

export default defineComponent({
  name: "aside-menu",
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
  emits: ["close-azide"],
  setup(props, {emit}) {
    const store = useAuthStore();
    const route = useRoute();
    const router = useRouter();
    const i18n = useI18n();
    const { t, te } = useI18n();

    const translate = (text: string) => {
        if (te(text)) {
            return t(text);
        } else {
            return text;
        }
    };

    const showSubMenu = ref(false);

    //languages with country flags
    const countries = {
      en: {
        flag: "media/flags/united-states.svg",
        name: computed(()=> { return translate("english") }),
      },
      de: {
        flag: "media/flags/germany.svg",
        name: computed(()=> { return translate('german') }),
      },
    };

    i18n.locale.value = localStorage.getItem("lang")
      ? (localStorage.getItem("lang") as string)
      : "en";

    const currentActive = (current: string) => {
      return route.path === '/' + current;
    };

    const mainMenu = computed(() => {
      return store.navBarList;
    });

    const currentUser = computed(() => store.user?.name || "-");
    const userRole = computed(() => store.user?.userRole || "-");
    const currentOrganization = computed(() => store.organization?.name || "");
    const userProfileImage = computed(() => store.user?.logo || '/media/avatar/avatar.png');

    //show language sub menu
    const toggleSubMenu = () => {
      showSubMenu.value = !showSubMenu.value;
    };

    //select induvidual language
    const selectLanguage = (lang) => {
      showSubMenu.value = false;
      localStorage.setItem("lang", lang);
      i18n.locale.value = lang;

      closeSideBar();
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

    const signOut = () => {
      store.logout();
      router.push({ name: "sign-in" });
    };

    const closeSideBar = () => {
      emit('close-azide');
    }

    return {
      MainMenuConfig,
      currentActive,
      mainMenu,
      currentUser,
      userRole,
      currentOrganization,
      userProfileImage,
      toggleSubMenu,
      showSubMenu,
      selectLanguage,
      currentLangugeLocale,
      countries,
      signOut,
      i18n,
      translate,
      closeSideBar
    }
  }
});
</script>