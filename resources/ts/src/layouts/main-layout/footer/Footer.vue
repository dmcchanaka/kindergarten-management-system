<template>
    <footer class="text-white py-2 px-4 sticky bottom-0 z-40 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800">
      <div class="flex items-center justify-between mx-auto max-w-7xl">
        <div class="text-sm text-slate-500">
          &nbsp;
        </div>
        <div class="flex items-center gap-2">
          <span class="text-xs font-normal text-slate-400">Powered by</span>
          <img src="/media/logo/logo.png" class="max-h-8 w-auto transition-all duration-200" alt="main_logo" />
        </div>
      </div>
    </footer>
  </template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";
import { useI18n } from "vue-i18n";

export default defineComponent({
    name: "footer-layer",
    setup() {
      const { t, te } = useI18n();
      const translate = (text: string) => {
          if (te(text)) {
              return t(text);
          } else {
              return text;
          }
      };

      return {
        translate
      }
    },
});
</script>