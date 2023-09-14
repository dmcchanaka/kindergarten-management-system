<template>
    <!-- sidenav  -->
    <aside
        class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-2 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0 xl:bg-[hsla(0,0%,100%,0.7)]">
        <div class="h-19.5">
            <!-- <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"
                sidenav-close></i> -->
            <CloudArrowUpIcon class="h-4 w-5 text-white mb-1" />
            <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-slate-700" href="javascript:;" target="_blank">
                <img :src="getAssetPath('media/logo/logo.png')"
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
import { defineComponent } from "vue";
import { getAssetPath } from "@/core/helpers/assets";

import { CloudArrowUpIcon } from '@heroicons/vue/24/solid';

import AsideMenu from "@/layouts/main-layout/aside/Menu.vue";

export default defineComponent({
  name: "aside-layer",
  components: {
    CloudArrowUpIcon,
    AsideMenu
  },
  setup() {
    return {
      getAssetPath
    }
  },
});
</script>