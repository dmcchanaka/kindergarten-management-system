<template>
    <div class="ease-soft-in-out relative h-full max-h-screen transition-all duration-200">
        <nav class="absolute z-20 flex flex-wrap items-center justify-between w-full px-6 py-2 text-white transition-all shadow-none duration-250 ease-soft-in lg:flex-nowrap lg:justify-start"
            navbar-profile navbar-scroll="true">
            <div class="flex items-center justify-between w-full px-6 py-1 mx-auto flex-wrap-inherit">
                <nav>
                    <!-- breadcrumb -->
                    <h6 class="mb-2 ml-2 font-bold text-white capitalize">Attendance</h6>
                </nav>
                <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                    <div class="flex items-center md:ml-auto md:pr-4">
                        <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                            <span
                                class="text-sm ease-soft leading-5.6 absolute z-50 -ml-px flex h-full items-center whitespace-nowrap rounded-lg rounded-tr-none rounded-br-none border border-r-0 border-transparent bg-transparent py-2 px-2.5 text-center font-normal text-slate-500 transition-all">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </span>
                        </div>
                    </div>
                    <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                        <li class="flex items-center">
                            <router-link  to="/sign-in"
                                class="block px-0 py-2 font-semibold text-white transition-all ease-soft-in-out text-sm">
                                <i class="fa fa-user sm:mr-1" aria-hidden="true"></i>
                                <span class="hidden sm:inline">Sign In</span>
                            </router-link>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="w-full px-6 mx-auto">
        <div class="relative flex items-center p-0 mt-6 overflow-hidden bg-center bg-cover min-h-75 rounded-2xl" :style="`background-image: url('/media/backgrounds/curved.jpg')`" style="background-position-y: 50%">
          <!-- <span class="absolute inset-y-0 w-full h-full bg-center bg-cover bg-gradient-to-tl from-purple-700 to-pink-500 opacity-60"></span> -->
          <h1 class="w-full text-center mb-2 ml-2 font-bold text-white capitalize">Mark Your Attendance</h1>
        </div>
        <div class="relative flex flex-col flex-auto min-w-0 p-4 mx-6 -mt-16 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
            <div class="flex flex-wrap -mx-3">
                <router-view></router-view>
            </div>
        </div>
    </div>
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