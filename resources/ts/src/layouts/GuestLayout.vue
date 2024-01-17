<template>
    <div class="min-h-screen m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500 relative">
        <aside
            class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 ml-0 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto bg-white p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0" style="background-color: #7bcde3;">
            <div class="">
                <fa icon="times" class="absolute top-0 right-0 p-4 opacity-50 cursor-pointer fas fa-times text-slate-400 xl:hidden"></fa>
                <a class="block px-8 py-3 m-0 text-sm whitespace-nowrap text-slate-700 text-center" href="javascript:;" target="_blank">
                    <img src="/media/logo/logo.png" class="inline h-full max-h-24 max-w-full transition-all duration-200 ease-nav-brand" alt="main_logo" />
                </a>
            </div>
            <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />
            <!-- aside menu -->
            <div class="items-center block w-auto max-h-screen h-sidenav grow basis-full">
                <ul class="flex flex-col pl-0 mb-0">
                    <li class="mt-0.5 w-full">
                        <div class="flex flex-col items-center justify-center w-full">
                            <div>
                                <fa class="text-white text-lg" icon="circle-check" />
                            </div>
                            <div>
                                <span class="ml-1 duration-300 opacity-100 pointer-events-none ease-soft text-white">Anwesenheit</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <!-- end aside menu -->
        </aside>
        <main class="ease-soft-in-out xl:ml-68.5 relative h-full min-h-screen rounded-xl transition-all duration-200 pt-2">
            <div class="w-full px-6 py-1 mx-auto">
                <div class="lg:flex lg:items-center lg:justify-between">
                <div class="flex-1 min-w-0">
                    <nav>
                        <h5 class="text-header mb-0 font-bold capitalize"><fa icon="user-check" />&nbsp;Anwesenheit</h5>
                    </nav>
                </div>
                <div class="flex mt-5 lg:ml-4 lg:mt-0">&nbsp;</div>
                </div>
                <router-view></router-view>
                <!-- Footer -->
                <!-- Footer -->
            </div>
        </main>
    </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
export default defineComponent({
    name: "guest-layout",
    setup() {

        return {
            getAssetPath
        }
    }
});
</script>