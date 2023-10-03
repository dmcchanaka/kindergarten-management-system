<template>
    <!-- sidenav  -->
    <aside
        class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-2 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-[hsla(0,0%,100%,0.7)]">
        <div class="h-19.5">
            <fa icon="times" class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden" @click.prevent="close"></fa>
            <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
                <img :src="computedLogo" 
                    class="inline h-full max-w-full transition-all duration-200 ease-nav-brand max-h-8" alt="main_logo" />&nbsp;
                <span class="ml-1 font-semibold transition-all duration-200 ease-nav-brand">Kindergarten</span>
            </a>
        </div>

        <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
        <!-- aside menu -->
        <AsideMenu />
        <!-- end aside menu -->
    </aside>
    <!-- end sidenav -->
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent, computed } from "vue";
import { getAssetPath } from "@/core/helpers/assets";

import AsideMenu from "@/layouts/main-layout/aside/Menu.vue";
import { useSettingsStore, type UiSettings, FormLogo } from "@/stores/settings";

export default defineComponent({
  name: "aside-layer",
  props: {},
  components: {
    AsideMenu
  },
  setup(props, {emit}) {
    const settingsStore = useSettingsStore();

    const computedLogo = computed(() => {
      const settings = settingsStore.generalSettings;
      return typeof settings?.logo != 'undefined' ? settings.logo : getAssetPath('media/logo/logo.png');
    });

    const close = () => {
      emit('close-sidebar')
    }
    return {
      getAssetPath,
      close,
      computedLogo
    }
  },
});
</script>