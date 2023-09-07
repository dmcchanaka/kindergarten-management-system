<template>
  <!-- cards row -->
  <div class="flex flex-wrap my-6 -mx-3">
    <div class="w-full h-full max-w-full px-3 lg:w-4/12 lg:flex-none">
      <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mb-5">
        <div class="relative z-10 flex flex-col flex-auto h-full p-4">
          <div class="flex items-center space-x-4">
            <img class="w-20 h-20 rounded-lg" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="">
            <div class="font-medium dark:text-white">
                <div>Organization</div>
                <div class="mt-3">
                  <button type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    <CloudArrowUpIcon class="h-4 w-5 text-white mb-1" /> Upload Logo
                  </button>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mb-5">
        <div class="relative z-10 flex flex-col flex-auto h-full p-4">
          <label for="small" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select language</label>
          <select id="small" class="block w-full p-2 mb-6 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option selected>Choose language</option>
            <option value="US">ENGLISH</option>
            <option value="FR">FRENCH</option>
            <option value="DE">GERMEN</option>
          </select>
        </div>
      </div>
    </div>
    <div class="w-full h-full max-w-full px-3 lg:w-8/12 lg:flex-none">
      <div
        class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
        <form class="space-y-6" @submit.prevent="onSubmitLogin">
          <h5 class="mb-0 font-bold capitalize">UI Configurations</h5>
          <div>
            <label for="email" class="block mb-2 text-sm font-medium">Background Color</label>
            <input type="color" v-model="settings.backgroundColor"
              :style="{ backgroundColor: settings.backgroundColor, borderColor: settings.backgroundColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" placeholder="name@company.com"
              required>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium">Heading Color</label>
            <input type="color" v-model="settings.headerColor"
              :style="{ backgroundColor: settings.headerColor, borderColor: settings.headerColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
          </div>
          <div>
            <label for="password" class="block mb-2 text-sm font-medium">Text Color</label>
            <input type="color" v-model="settings.textColor"
              :style="{ backgroundColor: settings.textColor, borderColor: settings.textColor }"
              class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
          </div>
          <button type="reset"
            class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Reset</button>
          <button ref="submitButton" type="submit" :disabled="loading"
            class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
            <span v-if="!loading">Submit</span>
            <span v-if="loading">
              Please wait...
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
import { defineComponent, ref, watch } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useSettingsStore, type UiSettings } from "@/stores/settings";
import Swal from "sweetalert2/dist/sweetalert2.js";

import { CloudArrowUpIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
  name: "ui-settings",
  components: {
    CloudArrowUpIcon
  },
  setup() {
    const store = useSettingsStore();
    const submitButton = ref<HTMLButtonElement | null>(null);
    const loading = ref(false);

    const dynamicColor = ref('blue');

    watch(dynamicColor, (newColor) => {
      document.documentElement.style.setProperty('--custom-text-color', newColor);
    });

    const settings = ref({
      backgroundColor: store.backgroundColor ? store.backgroundColor : '#4b5563', //gray
      headerColor: '#2563eb', //blue
      textColor: '#16a34a' //green
    });

    const onSubmitLogin = async (values: any) => {
      dynamicColor.value = 'ambian';
      if (submitButton.value) {
        submitButton.value!.disabled = true;
      }
      let inputs = {
        backgroundColor: settings.value.backgroundColor,
        headerColor: settings.value.headerColor,
        textColor: settings.value.textColor,
      }
      await store.saveUiSettings(inputs);
      const error = Object.values(store.errors);
      if (error.length === 0) {
        Swal.fire({
          title: 'Good job!',
          text: 'Records has been successfully saved!',
          icon: 'success',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Ok, got it!'
        }).then(() => {
          settings.value.backgroundColor = '#4b5563';
          settings.value.headerColor = '#2563eb';
          settings.value.textColor = '#16a34a';
        });
      } else {
        Swal.fire({
          title: 'Oops...',
          text: error[0] as string,
          icon: 'error',
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Try again!'
        }).then((result) => {
          store.errors = {};
        })
      }
      submitButton.value!.disabled = false;
      loading.value = false;
    };

    return {
      getAssetPath,
      settings,
      loading,
      submitButton,
      onSubmitLogin
    }
  },
});
</script>