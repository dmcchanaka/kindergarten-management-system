<template>
    <footer class="text-white p-4 sticky bottom-0 z-40 bg-white dark:bg-gray-900">
      <div class="flex flex-wrap items-center -mx-3 lg:justify-between">
        <div class="w-full max-w-full px-3 mt-0 mb-6 shrink-0 lg:mb-0 lg:w-1/2 lg:flex-none">
          &nbsp;
        </div>
        <div class="w-full max-w-full px-3 mt-0 shrink-0 text-center lg:w-1/2 lg:flex-none sm:text-end lg:text-end mb-5 lg:mb-0 md:mb-0">
          <span class="block px-4 pt-0 pb-1 pr-0 text-sm font-normal transition-colors ease-soft-in-out text-slate-500">Powered by</span>
          <img src="/media/logo/logo.png" class="inline max-h-20 max-w-full transition-all duration-200 ease-nav-brand pr-3" alt="main_logo" />
    
          <!-- Sub Menu Before Span -->
          <ul class="sub-menu flex flex-row text-slate-500 hover:text-black absolute bottom-0 text-lg gap-8 sm:gap-6 lg:static lg:text-center lg:mx-auto">
            <li><a href="https://www.paulinoo.de/impressum/" target="_blank">{{ translate('imprint') }}</a></li>
            <li><a href="https://www.paulinoo.de/datenschutz/" target="_blank">{{ translate('privacyNotice') }}</a></li>
            <li><a href="https://www.paulinoo.de/hilfe/" target="_blank">{{ translate('help') }}</a></li>
          </ul>
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