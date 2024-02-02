<template>
  <!-- cards row -->
  <div class="flex flex-wrap my-6 -mx-3">
    <div class="w-full h-full max-w-full px-3 lg:w-4/12 lg:flex-none">
      <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mb-5">
        <div class="relative z-10 flex flex-col flex-auto h-full p-4">
          <div class="flex items-center space-x-4">
            <img class="w-20 h-20 rounded-lg" :src="settings.logo" alt="" >
            <div class="font-medium dark:text-white">
                <div>{{ currentOrganization }}</div>
                <div class="mt-3">
                  <label
                    for="logoInput"
                    class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl bg-lime-500 hover:bg-lime-400 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"
                  >
                    <input
                      id="logoInput"
                      type="file"
                      accept="image/*"
                      class="hidden"
                      @change="changelogo"
                    />
                    <fa icon="cloud-arrow-up" class="h-4 w-5 text-white" /> {{ translate('uploadLogo') }}
                  </label>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mb-5">
        <div class="relative z-10 flex flex-col flex-auto h-full p-4">
          <label for="small" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('selectLanguage') }}</label>
          <select id="small" @click="selectLanguage" v-model="i18n.locale.value" class="block w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>{{ translate('chooseLanguage') }}</option>
            <option v-for="(country, code) in countries" :key="code" :value="code">
              {{ country.name.value }}
            </option>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full h-full max-w-full px-3 lg:w-8/12 lg:flex-none">
      <div
        class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
        <form class="space-y-6" @submit.prevent="onSubmitSettings">
          <h5 class="mb-0 font-bold capitalize text-sub-header">{{ translate('uiConfigurations') }}</h5>
          <div>
            <label for="email" class="block mb-2 text-sm font-medium">{{ translate('backgroundColor') }}</label>
            <input type="color" v-model="settings.backgroundColor"
              :style="{ backgroundColor: settings.backgroundColor, borderColor: settings.backgroundColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" placeholder="name@company.com"
              required>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium">{{ translate('headingColor') }}</label>
            <input type="color" v-model="settings.headerColor"
              :style="{ backgroundColor: settings.headerColor, borderColor: settings.headerColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium">{{ translate('textColor') }}</label>
            <input type="color" v-model="settings.textColor"
              :style="{ backgroundColor: settings.textColor, borderColor: settings.textColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
          </div>
          <button type="reset"
            class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl bg-red-600 hover:bg-red-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">Reset</button>
          <button ref="submitButton" type="submit" :disabled="loading"
            class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl bg-lime-500 hover:bg-lime-400 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
            <span v-if="!loading">{{ translate('submit') }}</span>
            <span v-if="loading">
              {{ translate('pleaseWait') }}...
            </span>
          </button>
        </form>
      </div>
    </div>
  </div>
  <!-- end cards row -->
</template>
  
<style lang="scss"></style>
  
<script lang="ts">
import { defineComponent, ref, watch, onMounted, computed } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore } from "@/stores/auth";
import { useSettingsStore, type UiSettings, FormLogo } from "@/stores/settings";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";

import { CloudArrowUpIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
  name: "ui-settings",
  components: {
    CloudArrowUpIcon
  },
  setup() {
    const authStore = useAuthStore();
    const store = useSettingsStore();
    const i18n = useI18n();
    const { t, te } = useI18n();

    const translate = (text: string) => {
        if (te(text)) {
            return t(text);
        } else {
            return text;
        }
    };

    //languages with country flags
    const countries = {
      en: {
        flag: "media/flags/united-states.svg",
        name: computed(()=>{ return translate('english') }),
      },
      de: {
        flag: "media/flags/germany.svg",
        name: computed(()=>{ return translate('german') }),
      },
    };

    i18n.locale.value = localStorage.getItem("lang")
      ? (localStorage.getItem("lang") as string)
      : "en";

    //select induvidual language
    const selectLanguage = (event) => {
      localStorage.setItem("lang", event.target.value);
      i18n.locale.value = event.target.value;
    };

    const submitButton = ref<HTMLButtonElement | null>(null);
    const loading = ref(false);

    const currentOrganizationId = computed(() => {
      const organization = authStore.organization;
      return typeof organization?.id != 'undefined' ? organization?.id : "0";
    });

    const currentOrganization = computed(() => {
      const organization = authStore.organization;
      return typeof organization?.name != 'undefined' ? organization?.name : "-";
    });

    const userId = computed(() => {
      const user = authStore.user;
      return typeof user?.userId != 'undefined' ? user?.userId : "0";
    });

    const settings = ref({
      logo: "",
      selectedLogo: "",
      backgroundColor: "", //gray
      headerColor: "", //blue
      textColor: "", //green
    });

    watch(
      [() => settings.value.backgroundColor, () => settings.value.headerColor, () => settings.value.textColor],
      ([newBgColor, newHeaderColor, newTextColor]) => {
        document.documentElement.style.setProperty('--custom-background-color', newBgColor);
        document.documentElement.style.setProperty('--custom-header-color', newHeaderColor);
        document.documentElement.style.setProperty('--custom-text-color', newTextColor);
      }
    );

    onMounted(async() => {
      await fetchGeneralSettings();
    });

    //fetch all previous settings with page load
    const fetchGeneralSettings = async() => {
      let inputs = {
        organizationId: currentOrganizationId.value,
        userId: userId.value
      }
      await store.fetchUiSettings(inputs);
      const error = Object.values(store.errors);
      if (error.length === 0) {
        const logo = store.generalSettings?.logo;
        const backgroundColor = store.generalSettings?.backgroundColor;
        const headerColor = store.generalSettings?.headerColor;
        const textColor = store.generalSettings?.textColor;

        if (backgroundColor && headerColor && textColor && logo) {
          settings.value.backgroundColor = backgroundColor;
          settings.value.headerColor = headerColor;
          settings.value.textColor = textColor;
          settings.value.logo = logo;
        }
      } else {
        Swal.fire({
          title: translate('opps') + '...',
          text: translate(error[0] as string),
          icon: 'error',
          confirmButtonColor: '#3085d6',
          confirmButtonText: translate('tryAgain') + '!'
        }).then((result) => {
          store.errors = {};
        })
      }
    };

    const onSubmitSettings = async (values: any) => {
      if (submitButton.value) {
        submitButton.value!.disabled = true;
      }
      let inputs = {
        organizationId: currentOrganizationId.value,
        userId: userId.value,
        backgroundColor: settings.value.backgroundColor,
        headerColor: settings.value.headerColor,
        textColor: settings.value.textColor,
      }
      await store.saveUiSettings(inputs);
      const error = Object.values(store.errors);
      if (error.length === 0) {
        Swal.fire({
          title: translate('goodJob') + '!',
          text: translate('recordHasBeenSuccesfullyAdded'),
          icon: 'success',
          confirmButtonColor: '#3085d6',
          confirmButtonText: translate('okGotIt') + '!'
        }).then(() => {
          const logo = store.generalSettings?.logo;
          const backgroundColor = store.generalSettings?.backgroundColor;
          const headerColor = store.generalSettings?.headerColor;
          const textColor = store.generalSettings?.textColor;

          if (backgroundColor && headerColor && textColor && logo) {
            settings.value.backgroundColor = backgroundColor;
            settings.value.headerColor = headerColor;
            settings.value.textColor = textColor;
            settings.value.logo = logo;
          }
        });
      } else {
        Swal.fire({
          title: translate('opps') + '...',
          text: translate(error[0] as string),
          icon: 'error',
          confirmButtonColor: '#3085d6',
          confirmButtonText: translate('tryAgain') + '!'
        }).then((result) => {
          store.errors = {};
        })
      }
      submitButton.value!.disabled = false;
      loading.value = false;
    };

    const changelogo = async(event) => {
      const createUrl = URL.createObjectURL(event.target.files[0]);
      URL.revokeObjectURL(event.target.files[0]);
      settings.value.logo = createUrl;
      settings.value.selectedLogo = event.target.files[0];

      const formData = new FormData();
      formData.append('image',settings.value.selectedLogo);
      formData.append('organizationId', currentOrganizationId.value);
      formData.append('userId', userId.value);

      await store.saveLogo(formData);
      const error = Object.values(store.errors);
      if (error.length === 0) {
        settings.value.logo = store.logo;
      } else {
          Swal.fire({
            title: translate('opps') + '...',
            text: translate(error[0] as string),
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: translate('tryAgain') + '!'
          }).then((result) => {
              store.errors = {};
          })
      }
    }

    return {
      getAssetPath,
      settings,
      loading,
      submitButton,
      onSubmitSettings,
      changelogo,
      currentOrganization,
      countries,
      i18n,
      selectLanguage,
      translate
    }
  },
});
</script>