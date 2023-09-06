<template>
  <div class="mt-5 w-full p-4 text-left bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
    <form class="space-y-6" @submit.prevent="onSubmitLogin">
        <h5 class="text-xl font-medium text-gray-900 dark:text-white">UI Configurations</h5>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Background Color</label>
            <input type="color" v-model="settings.backgroundColor" :style="{ backgroundColor: settings.backgroundColor, borderColor: settings.backgroundColor }" class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" placeholder="name@company.com" required>
        </div>
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Heading Color</label>
            <input type="color" v-model="settings.headerColor" :style="{ backgroundColor: settings.headerColor, borderColor: settings.headerColor }" class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
        </div>
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Text Color</label>
            <input type="color" v-model="settings.textColor" :style="{ backgroundColor: settings.textColor, borderColor: settings.textColor }" class="border border-gray-300 text-gray-900 text-sm rounded-lg w-full" required>
        </div>
        <button type="reset" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">Reset</button>
        <button
        ref="submitButton"
        type="submit"
        :disabled="loading"
        class="text-white bg-blue-500 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
          <span v-if="!loading">Submit</span>
          <span v-if="loading">
          Please wait...
          </span>
        </button>
    </form>
</div>
</template>
  
<style lang="scss"></style>
  
<script lang="ts">
import { defineComponent, ref } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useSettingsStore, type UiSettings } from "@/stores/settings";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
  name: "ui-settings",
  setup() {
    const store = useSettingsStore();
    const submitButton = ref<HTMLButtonElement | null>(null);
    const loading = ref(false);

    const settings = ref({
      backgroundColor: store.backgroundColor?store.backgroundColor:'#4b5563', //gray
      headerColor: '#2563eb', //blue
      textColor: '#16a34a' //green
    });

    const onSubmitLogin = async (values: any) => {
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