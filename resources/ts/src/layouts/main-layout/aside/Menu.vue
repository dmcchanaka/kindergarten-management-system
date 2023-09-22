<template>
    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
            <ul class="flex flex-col pl-0 mb-0">
                <li class="mt-0.5 w-full" v-for="(item, i) in MainMenuConfig" :key="i">
                    <router-link v-if="item.route" :class="{
                        'py-2.7 shadow-soft-xl text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap rounded-lg bg-white px-4 font-semibold text-slate-700 transition-colors': currentActive(item.route)
                        , 'py-2.7 text-sm ease-nav-brand my-0 mx-4 flex items-center whitespace-nowrap px-4 transition-colors': !currentActive(item.route)}" :to="item.route">
                        <div class="bg-gradient-to-tl from-purple-700 to-pink-500 shadow-soft-2xl mr-2 flex h-8 w-8 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
                            <fa :icon="item.icon" />
                        </div>
                        <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft">{{ item.heading }}</span>
                    </router-link>
                </li>
            </ul>
        </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import MainMenuConfig from "@/core/config/MainMenuConfig";
import { useRoute } from "vue-router";

export default defineComponent({
  name: "aside-menu",
  components: {},
  setup() {
    const route = useRoute();

    const currentActive = (current: string) => {
      return route.path === current;
    };

    return {
        MainMenuConfig,
        currentActive
    }
  }
});
</script>