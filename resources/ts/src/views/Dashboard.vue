<template>
  <div class="w-full p-4 mt-5 relative flex flex-col flex-auto min-w-0 p-4 mx-0 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
  <!-- <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"> -->
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-auto max-w-full px-3">
        <div class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
          <div class="bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-12 w-12 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
              <fa icon="building-columns" class="h-7" />
          </div>
        </div>
      </div>
      <div class="flex-none w-auto max-w-full px-0 my-auto">
        <div class="h-full">
          <h5 class="mb-1 text-header">{{ currentOrganization }}</h5>
          <p class="mb-0 font-semibold leading-normal text-sm">{{ userRole }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore } from "@/stores/auth";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "dashboard",
  setup() {
    const store = useAuthStore();
    const { t, te } = useI18n();

    const translate = (text: string) => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    const currentOrganization = computed(() => {
      const organization = store.organization;
      return typeof organization?.name != 'undefined' ? organization?.name : "-";
    });

    const userRole = computed(() => {
      const user = store.user;
      return typeof user?.userRole != 'undefined' ? user?.userRole : "-";
    });

    return {
      getAssetPath,
      currentOrganization,
      userRole,
      translate
    }
  },
});
</script>