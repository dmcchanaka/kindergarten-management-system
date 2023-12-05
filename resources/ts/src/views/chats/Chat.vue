<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0">{{ translate('latestClassRoomActivity') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/news-feed" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words p-2 bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <!--chat search-->
                        <input type="text" placeholder="Search" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blu-400 mb-4" />
                        <!--chat list-->
                        <div class="max-h-96 overflow-y-auto">
                            <div class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100 hover:bg-gray-100">
                                <div class="relative">
                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                    <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1 -left-3 ml-2"></div>
                                </div>
                                <div class="ml-3">
                                    <div class="font-semibold">chaminda chanaka</div>
                                    <span class="text-gray-500">Hello</span>
                                </div>
                            </div>
                            <div class="flex p-2 items-center mb-3 cursor-pointer rounded-md hover:bg-gray-100">
                                <div class="relative">
                                    <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                    <div class="absolute h-3 w-3 bg-slate-400 rounded-full -top-1 -left-3 ml-2"></div>
                                </div>
                                <div class="ml-3">
                                    <div class="font-semibold">chaminda chanaka</div>
                                    <span class="text-gray-500">Hello</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words p-2 bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div v-if="isChatOpen">
                            <!--chat header-->
                            <div>1</div>
                            <!--chat body-->
                            <div>2</div>
                            <!--chat footer-->
                            <div>3</div>
                        </div>
                        <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                            <img :src="computedLogo" class="" alt="main_logo" />
                            <div class="font-semibold">Welcome to chat room</div>
                            <span class="text-gray-500">Select a chat to start messaging</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { getAssetPath } from "@/core/helpers/assets";
import { useGalleryStore, type Gallery } from "@/stores/gallery";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";
import { useSettingsStore, type UiSettings, FormLogo } from "@/stores/settings";

export default defineComponent({
    name: "chat",
    setup() {
        const { t, te } = useI18n();
        const settingsStore = useSettingsStore();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const isChatOpen = ref("true");

        const computedLogo = computed(() => {
            const settings = settingsStore.generalSettings;
            return typeof settings?.logo != 'undefined' ? settings.logo : getAssetPath('media/logo/logo.png');
        });

        return {
            translate,
            computedLogo,
            isChatOpen
        }
    }
});
</script>