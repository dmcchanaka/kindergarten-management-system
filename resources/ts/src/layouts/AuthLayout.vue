<template>
    <section class="border-red-500 bg-gray-200 min-h-screen flex items-center justify-center bg-cover" :style="`background-image: url('/media/backgrounds/bg-01.jpg')`">
        <div class="bg-gray-100 p-5 flex rounded-2xl shadow-lg max-w-3xl">
        <div class="md:w-1/2 px-5">
            <router-view></router-view>
        </div>

        <div class="w-1/2 md:block hidden ">
            <img :src="getAssetPath('media/backgrounds/bg-02.jpg')" class="rounded-2xl" alt="page img">
        </div>

        </div>
    </section>
</template>

<style lang="scss">
</style>

<script lang="ts">
import { defineComponent } from "vue";
import { getAssetPath } from "@/core/helpers/assets";

export default defineComponent({
  name: "auth-layout",
  setup() {
    return {
        getAssetPath
    }
  },
});
</script>