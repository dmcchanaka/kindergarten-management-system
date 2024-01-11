<template>
    <div class="w-full h-screen flex flex-col lg:flex-row items-center justify-center space-y-16 lg:space-y-0 space-x-8 2xl:space-x-0">
        <div class="lg:h-full flex lg:items-end justify-center p-4">
            <img :src="getAssetPath('media/svg/404.svg')" style="height: 100%;width: 100%;" class="rounded-2xl" alt="page img">
        </div>
        <div class="w-full flex flex-col items-center justify-center lg:px-2 xl:px-0 text-center">
            <p class="text-7xl md:text-8xl lg:text-9xl font-bold tracking-wider text-gray-300 custom-text-header">404</p>
            <p class="text-4xl md:text-5xl lg:text-6xl font-bold tracking-wider text-gray-300 mt-2 custom-text-sub-header">Page Not Found</p>
            <p class="text-lg md:text-xl lg:text-2xl text-gray-500 my-12 custom-text">Sorry, the page you are looking for could not
                be found.</p>
            <router-link to="/dashboard" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                <fa icon="arrow-left" />&nbsp;&nbsp;Back
            </router-link>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
export default defineComponent({
  name: "system-layout",
  setup(){
    return {
        getAssetPath,
    }
  }
});
</script>