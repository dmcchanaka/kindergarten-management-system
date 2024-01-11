<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0">{{ translate('latestClassRoomActivity') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-12/12 md:flex-none">
                    <div v-if="latestNewsFeed" class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="flex-auto p-4">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 lg:w-8/12 lg:flex-none">
                                    <div class="flex flex-col h-full">
                                        <h2 class="font-bold">{{ latestNewsFeed?.title }}</h2>
                                        <p class="mb-5">{{ latestNewsFeed?.description }}</p>
                                        
                                        <div class="mt-auto" v-if="latestNewsFeed?.title">
                                            <a @click.prevent="viewContent(latestNewsFeed?.id)" class="text-sm inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                            &nbsp;&nbsp;{{ translate('readMore') }}
                                            <fa icon="arrow-right" />
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="max-w-full px-3 mt-12 ml-auto text-center lg:mt-0 lg:w-4/12 lg:flex-none">
                                    <div class="h-full bg-gradient-to-tl rounded-xl">
                                        <div class="relative flex items-center justify-center h-full">
                                            <img class="relative rounded main-img" :src="latestNewsFeed?.feature_image ? latestNewsFeed?.feature_image : 'https://via.placeholder.com/300'" alt="rocket" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center">
                        <a href="#" class="block p-6 bg-white border border-gray-200 rounded-lg hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                            <div class="text-center mb-3">
                                <fa icon="face-frown" class="text-5xl text-sm"></fa>
                            </div>
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">No Any Posts</h5>
                            <p class="font-normal text-gray-700 dark:text-gray-400">Please wait till we publish the children's activity posts.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-none w-full max-w-full px-3 mt-6">
        <div class="relative flex flex-col min-w-0 mb-6 break-words bg-white border-0 shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white rounded-t-2xl">
                <h6 class="mb-1">{{ translate('previousPosts') }}</h6>
            </div>
            <div class="flex-auto p-4">
                <div class="flex flex-wrap -mx-3" v-if="remainingNewsFeedList.length">
                    <div  v-for="(item, index) in remainingNewsFeedList" :key="index" class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                        <div class="relative flex flex-col min-w-0 break-words bg-transparent border-0 shadow-none rounded-2xl bg-clip-border">
                            <div class="relative">
                                <a class="block shadow-xl rounded-2xl">
                                <img :src="item.feature_image ? item.feature_image : 'https://via.placeholder.com/300'" alt="img-blur-shadow" class="max-w-full shadow-soft-2xl rounded-2xl img" />
                                </a>
                            </div>
                            <div class="flex-auto px-1 pt-6">
                                <p class="relative z-10 mb-2 leading-normal text-transparent from-gray-900 to-slate-800 text-sm bg-clip-text">Project #2</p>
                                <a href="javascript:;">
                                    <h5>{{ item.title }}</h5>
                                </a>
                                <p class="mb-6 leading-normal text-sm">{{ truncateText(item.description) }}</p>
                                <div class="flex items-center justify-between">
                                    <button @click.prevent="viewContent(item?.id)" type="button" class="inline-block px-8 py-2 mb-0 font-bold text-center uppercase align-middle transition-all bg-transparent border border-solid rounded-lg shadow-none cursor-pointer leading-pro ease-soft-in text-xs hover:scale-102 active:shadow-soft-xs tracking-tight-soft border-fuchsia-500 text-fuchsia-500 hover:border-fuchsia-500 hover:bg-transparent hover:text-fuchsia-500 hover:opacity-75 hover:shadow-none active:bg-fuchsia-500 active:text-white active:hover:bg-transparent active:hover:text-fuchsia-500">Read More</button>
                                </div>
                            </div>
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
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { getAssetPath } from "@/core/helpers/assets";
import { useGalleryStore, type Gallery } from "@/stores/gallery";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";

export default defineComponent({
    name: "news-feed",
    setup() {
        const store = useGalleryStore();
        const { t, te } = useI18n();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const newsFeedList = ref<Array<Gallery>>([]);

        onMounted(async () => {
            await fetchNewsFeedContents();
        });

        const fetchNewsFeedContents = async () => {
            await store.fetchStudentContent();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                newsFeedList.value.splice(0, newsFeedList.value.length, ...store.contentList);
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    store.errors = {};
                })
            }
        }

        const latestNewsFeed = computed(() => {
            if (newsFeedList.value.length > 0) {
                return newsFeedList.value[0];
            }
            return null;
        });

        const remainingNewsFeedList = computed(() => {
            if (newsFeedList.value.length > 1) {
                // Creating a copy of newsFeedList to avoid mutations
                const copyNewsFeedList = [...newsFeedList.value];
                // Remove the first item (latest) from the array
                copyNewsFeedList.shift();
                return copyNewsFeedList;
            }
            return [];
        });

        const truncateText = (text) => {
            if (text && text.length > 250) {
                return text.slice(0, 250) + "...";
            }
            return text;
        };

        const viewContent = (contentId) => {
            console.log(contentId);
            store.saveContentId(contentId);
            router.push({ name: "news-feed-content" });
        }

        return {
            translate,
            getAssetPath,
            newsFeedList,
            latestNewsFeed,
            remainingNewsFeedList,
            truncateText,
            viewContent
        }
    }
});
</script>
<style scoped>
.mt-auto {
    margin-top: auto;
}
.mb-0 {
    margin-bottom: 0px;
}
.leading-normal {
    line-height: 1.5;
}
.font-semibold {
    font-weight: 600;
}
.mb-12 {
    margin-bottom: 3rem;
}
.pt-2 {
    padding-top: 0.5rem;
}
.flex-wrap {
    flex-wrap: wrap;
}

.flex {
    display: flex;
}
.-mx-3 {
    margin-left: -0.75rem;
    margin-right: -0.75rem;
}
.justify-center {
    justify-content: center;
}
.items-center {
    align-items: center;
}
.rounded {
    border-radius: 20px;
}
.img {
    height: 350px;
    width: 100%;
}
.main-img {
    height: 250px;
    width: 100%;
}
</style>