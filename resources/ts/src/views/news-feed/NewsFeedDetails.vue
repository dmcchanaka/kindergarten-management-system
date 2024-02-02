<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0 text-header">{{ translate('latestClassRoomActivity') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/news-feed" class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <img class="relative rounded" style="width: 100%" :src="newsFeedInfo.feature_image ? newsFeedInfo.feature_image : 'https://via.placeholder.com/300'" alt="rocket" />
                            <div class="mt-5">
                                <div class="sm:flex justify-between items-center space-y-[15px] sm:space-y-[0]">
                                    <div>
                                        <ul class="list-none space-x-[15px]">
                                            <li class="inline-block">
                                                <fa icon="school"></fa>
                                            </li>
                                            <li class="inline-block">
                                                <a href="#" class="font-bold text-13px text-sm">{{ newsFeedInfo.class_room }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="inline-flex mt-15 sm:mt-0 sm:flex justify-center items-center">
                                        <p class="text-sm mr-[10px] font-bold">{{ newsFeedInfo.added_date }}</p>
                                    </div>
                                </div>
                                <h2 class="font-bold">{{ newsFeedInfo.title }}</h2>
                                <p class="mb-5">{{ newsFeedInfo.description }}</p>
                            </div>
                            <div class="grid gap-4">
                                <div class="grid grid-cols-2 gap-2">
                                    <div  v-for="(item, index) in newsFeedInfo.content_images" :key="index">
                                        <img class="h-auto max-w-full rounded-lg" :src="item ? item : 'https://via.placeholder.com/300'" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0">{{ translate('previousPosts') }}</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <div v-if="latestFiveNewsFeed?.length">
                                <div v-for="(item, index) in latestFiveNewsFeed" :key="index" class="flex items-center space-x-[15px] mb-5">
                                    <div class="shrink-0">
                                        <a href="" @click.prevent="viewContent(item.id)">
                                            <img class="rounded width" :src="item.feature_image ? item.feature_image : 'https://via.placeholder.com/300'">
                                        </a>
                                    </div>
                                    <div>
                                        <p class="text text-sm mb-2 leading-none">{{ item.added_date }}</p>
                                        <h5 class="text-[15px] sm:text leading-6 font-semibold text-black">
                                            <a>{{ item.title }}</a>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center">
                                <a href="#" class="block p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                                    <div class="text-center mb-3">
                                        <fa icon="face-frown" class="text-5xl text-sm"></fa>
                                    </div>
                                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">No Related Posts</h5>
                                    <p class="font-normal text-gray-700 dark:text-gray-400">Please wait till we publish the children's activity posts.</p>
                                </a>
                            </div>
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
import { useRouter } from "vue-router";

export default defineComponent({
    name: "news-feed",
    setup() {
        const { t, te } = useI18n();
        const store = useGalleryStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const contentInfo = ref<Array<Gallery>>([]);
        const contentLatestFive = ref<Array<Gallery>>([]);

        const newsFeedInfo = ref({
            id: "",
            title: "",
            description: "",
            feature_image: "",
            content_images: [] as string[],
            class_room: "",
            added_date: "",
        });

        onMounted(async () => {
            await getContentInfo();
        });

        const getContentInfo = async () => {
            if (store.idContent) {
                contentInfo.value.splice(0, contentInfo.value.length, ...store.contentList);
                let results = contentInfo.value.filter((item) => {
                    return item.id.toString() == store.idContent.toString();
                });
                contentInfo.value.splice(0, contentInfo.value.length, ...results);

                newsFeedInfo.value.id = contentInfo?.value[0].id.toString() || "";
                newsFeedInfo.value.title = contentInfo?.value[0].title || "";
                newsFeedInfo.value.description = contentInfo?.value[0].description || "";
                newsFeedInfo.value.feature_image = contentInfo?.value[0].feature_image || "";
                newsFeedInfo.value.added_date = contentInfo?.value[0].added_date || "";
                newsFeedInfo.value.class_room = contentInfo?.value[0].class_room.name || "";

                const contentImages = contentInfo?.value[0].content_images.map(img => img.image_url);
                newsFeedInfo.value.content_images = contentImages
            } else {
                router.go(-1);
            }
        }

        const latestFiveNewsFeed = computed(() => {
            if (store.contentList.length > 0) {
                const filteredList = store.contentList.filter(item => item.id.toString() !== store.idContent.toString());
                let results = filteredList.slice(0, 5);
                contentLatestFive.value.splice(0, contentLatestFive.value.length, ...results);
                return contentLatestFive.value;
            }
            return null;
        });

        const viewContent = async (contentId) => {
            store.saveContentId(contentId);
            await getContentInfo()
        }

        return {
            translate,
            getAssetPath,
            newsFeedInfo,
            latestFiveNewsFeed,
            viewContent
        }
    }
});
</script>
<style scoped>
    .rounded {
        border-radius: 20px;
    }
    .width {
        width: 120px
    }
    .leading-none {
        line-height: 1;
    }
    .text {
        font-size: 14px
    }
    .leading-6 {
        line-height: 1.5rem;
    }
    .font-semibold {
        font-weight: 600;
    }
</style>