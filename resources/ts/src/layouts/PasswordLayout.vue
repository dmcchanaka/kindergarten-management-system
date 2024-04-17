<template>
    <section class="border-red-500 bg-gray-200 min-h-screen flex items-center justify-center bg-cover" :style="`background-image: url('/media/backgrounds/bg-03.png')`">
        <router-view></router-view>
    </section>
</template>

<style lang="scss">
</style>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore } from "@/stores/auth";
import { useRoute, useRouter } from "vue-router";

export default defineComponent({
  name: "password-layout",
  setup() {
    const store = useAuthStore();
    const router = useRouter();

    return {
        getAssetPath
    }
  },
});
</script>