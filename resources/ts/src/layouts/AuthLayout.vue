<template>
    <section class="border-red-500 bg-gray-200 min-h-screen flex items-center justify-center bg-cover" :style="`background-image: url('/media/backgrounds/bg-01.png')`">
        <router-view></router-view>
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