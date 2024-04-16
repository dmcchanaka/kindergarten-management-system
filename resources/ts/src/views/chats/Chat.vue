<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <!-- <h6 class="mb-0 text-header">{{ translate('chatRoom') }}</h6> -->
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/news-feed" class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
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
                        <!-- <input type="text" :placeholder="translate('search')" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blu-400 mb-4" readonly /> -->
                        <div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
                            <ul class="flex flex-wrap -mb-px mb-1">
                                <li class="me-2">
                                    <a @click="selectTab('private')" :class="{ 'active bg-lime-500 hover:bg-lime-400 text-white hover:text-white': chatApp.activeTab === 'private' }" class="relative inline-block p-4 cursor-pointer rounded-lg border border-lime-200 hover:bg-lime-100 text-sm font-poppins">
                                        {{ translate('private') }}
                                        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900" v-if="computedPrivateChatUnseenMsg">{{ computedPrivateChatUnseenMsg }}</div>
                                    </a>
                                </li>
                                <li class="me-2">
                                    <a @click="selectTab('group')" :class="{ 'active bg-lime-500 hover:bg-lime-400 text-white hover:text-white': chatApp.activeTab === 'group' }" class="relative inline-block p-4 cursor-pointer rounded-lg border border-lime-200 hover:bg-lime-100 text-sm font-poppins" aria-current="page">
                                        {{ translate('group') }}
                                        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -end-2 dark:border-gray-900" v-if="computedChatRoomUnseenMsg">{{ computedChatRoomUnseenMsg }}</div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!--chat list-->
                        <div class="tab-content">
                            <div v-if="chatApp.activeTab === 'private'">
                                <div class="max-h-96 overflow-y-auto py-2">
                                    <div v-for="(item, index) in computedUsers" :key="item.id" @click="openChat(item.id)" :class="{ 'bg-gray-700 hover:bg-gray-800': isSelectedUser(item.id) }" class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100 hover:bg-gray-100">
                                        <div class="relative">
                                            <img class="h-8 w-8 rounded-full" src="https://cdn-icons-png.freepik.com/512/4564/4564089.png" alt="" />
                                            <span class="absolute -top-3.5 -left-3 inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-300 rounded-full" v-if="item.unseen_messages">{{ item.unseen_messages }}</span>
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-semibold">{{ item.first_name }} {{ item.last_name }}</div>
                                            <span class="text-gray-500">{{ item.user_role }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="chatApp.activeTab === 'group'">
                                <div class="py-2">
                                    <a v-if="!chatApp.groupChatCreateStatus" @click="createGroupChat"  class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                        <fa icon="plus" />
                                        &nbsp;&nbsp;{{ translate('createNewGroup') }}
                                    </a>
                                    <div v-if="chatApp.groupChatCreateStatus">
                                        <div class="grid gap-2 mb-2 md:grid-cols-1">
                                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('chatRoomName') }}</label>
                                            <input type="text" v-model="chatApp.chatRoomName" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('chatRoomName')" required>
                                            <ErrorLabel v-if="formErrors.name" :error="formErrors.name"></ErrorLabel>
                                        </div>
                                        <div class="grid gap-2 mb-2 md:grid-cols-1">
                                            <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('users') }}</label>
                                            <Multiselect 
                                                v-model="chatApp.chatRoomUsers"
                                                :placeholder="translate('chooseUsers')" 
                                                mode="tags" 
                                                :close-on-select="false"
                                                :searchable="true" 
                                                :create-option="true" 
                                                :options="convertedUserList" />
                                            <ErrorLabel v-if="formErrors.users" :error="formErrors.users.toString()"></ErrorLabel>
                                        </div>
                                        <button 
                                                type="button" 
                                                @click="backToChatList"
                                                class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl bg-red-600 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                                {{ translate('back') }}
                                            </button>
                                        <button 
                                            ref="createGroupButton" 
                                            type="submit" 
                                            @click.prevent="submitChatRoom"
                                            :disabled="chatApp.loading"
                                            class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                            <span v-if="!chatApp.loading">{{ translate('submit') }}</span>
                                            <span v-if="chatApp.loading">
                                                {{ translate('pleaseWait') }}...
                                            </span>
                                        </button>
                                    </div>
                                    <div v-else class="max-h-96 overflow-y-auto py-2">
                                        <div v-for="(item, index) in computedChatRooms" :key="item.id" @click="openGroupChat(item.id)" :class="{ 'bg-gray-700 hover:bg-gray-800': isSelectedGroup(item.id) }" class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100 hover:bg-gray-100">
                                            <div class="relative">
                                                <img class="h-10 w-10 rounded-full" src="https://cdn-icons-png.flaticon.com/512/3437/3437284.png" alt="" />
                                                <span class="absolute -top-3.5 -left-3 inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-300 rounded-full" v-if="item.unseen_messages">{{ item.unseen_messages }}</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="font-semibold">{{ item.name }}</div>
                                                <span class="text-gray-500">&nbsp;</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words p-2 bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div v-if="chatApp.isChatOpen">
                            <!--chat header-->
                            <div class="flex items-center justify-between mb-4 bg-slate-200 px-4 pb-2 pt-2 rounded-tl-md rounded-tr-md">
                                <div class="flex items-center">
                                    <img class="h-12 w-12 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                    <div class="ml-3 pt-1">
                                        <div class="font-semibold">{{ chatApp.user }}</div>
                                        <span class="text-gray-500">{{ chatApp.role }}</span>
                                    </div>
                                </div>
                                <div class="relative inline-block text-left group">
                                    <fa icon="ellipsis-vertical" class="w-6 h-6 cursor-pointer" />
                                    <div class="origin-top-right absolute right-0 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 hidden group-hover:block">
                                        <div class="py-1">
                                            <a @click="closeChat" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 cursor-pointer">{{ translate('closeChat') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--chat body-->
                            <div class="overflow-y-auto max-h-64 min-h-[19rem] px-4" ref="chatContentRef" @scroll="scrollMessages" @click="updateMessageSeenStatus">
                                <template v-for="(item, index) in userMessagesList" v-if="chatApp.activeTab == 'private'">
                                    <div class="flex items-center mb-2" v-if="item.sender_id != currentUser">
                                        <img class="h-6 w-6 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                        <div class="text-sm py-2 px-4 shadow bg-sky-100 rounded-md max-w-xs">
                                            <span class="flex text-xs items-center">
                                                <span class="bg-sky-300 text-sky-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">{{ chatApp.user }}</span>
                                                <fa icon="clock" class="w-4 h-4 cursor-pointer pr-1" /> 
                                                <span>{{ item.time_ago }}</span>
                                            </span>
                                            {{ item.message }}
                                        </div>
                                        <fa icon="ellipsis-vertical" class="w-4 h-4 cursor-pointer" />
                                    </div>
                                    <div class="flex items-center mb-2 justify-end" v-else>
                                        <img class="h-6 w-6 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                        <div class="text-sm py-2 px-4 shadow bg-indigo-100 rounded-md max-w-xs">
                                            <span class="flex text-xs items-center">
                                                <span class="bg-indigo-300 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">You</span>
                                                <fa icon="clock" class="w-4 h-4 cursor-pointer pr-1" /> 
                                                <span>{{ item.time_ago }}</span>
                                            </span>
                                            {{ item.message }}
                                        </div>
                                        <fa icon="ellipsis-vertical" class="w-4 h-4 cursor-pointer" />
                                    </div>
                                </template>
                                <template v-for="(item, index) in userMessagesList" v-if="chatApp.activeTab == 'group'">
                                    <div class="flex items-center mb-2" v-if="item.sender_id != currentUser">
                                        <img class="h-6 w-6 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                        <div class="text-sm py-2 px-4 shadow bg-sky-100 rounded-md max-w-xs">
                                            <span class="flex text-xs items-center">
                                                <span class="bg-sky-300 text-sky-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">{{ item.sender.name }}</span>
                                                <fa icon="clock" class="w-4 h-4 cursor-pointer pr-1" /> 
                                                <span>{{ item.time_ago }}</span>
                                            </span>
                                            {{ item.message }}
                                        </div>
                                        <fa icon="ellipsis-vertical" class="w-4 h-4 cursor-pointer" />
                                    </div>
                                    <div class="flex items-center mb-2 justify-end" v-else>
                                        <img class="h-6 w-6 rounded-full border-2 border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                                        <div class="text-sm py-2 px-4 shadow bg-indigo-100 rounded-md max-w-xs">
                                            <span class="flex text-xs items-center">
                                                <span class="bg-indigo-300 text-indigo-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">You</span>
                                                <fa icon="clock" class="w-4 h-4 cursor-pointer pr-1" /> 
                                                <span>{{ item.time_ago }}</span>
                                            </span>
                                            {{ item.message }}
                                        </div>
                                        <fa icon="ellipsis-vertical" class="w-4 h-4 cursor-pointer" />
                                    </div>
                                </template>
                            </div>
                            
                            <!--chat footer-->
                            <div class="flex items-center bg-white rounded-bl-md rounded-br-md">
                                <input type="text" v-model="chatApp.message" class="w-full p-2 rounded-md border border-gray-300 focus:outline-none focus:ring focus:border-blue-400" @keydown.enter.prevent="send" />
                                <button ref="submitButton" class="ml-2 inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins" @click.prevent="send" :disabled="chatApp.loading">
                                    <span v-if="!chatApp.loading">Send</span>
                                    <span v-if="chatApp.loading">
                                        <div class="text-center">
                                            <div role="status">
                                                <svg aria-hidden="true" class="inline w-8 h-8 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                                                    <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                                                </svg>
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </div>
                                    </span>
                                </button>
                            </div>
                        </div>
                        <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                            <img :src="computedLogo" class="max-h-80 max-w-full h-auto" alt="main_logo" />
                            <div class="font-semibold">{{ translate('welcomeChat') }}</div>
                            <span class="text-gray-500">{{ translate('selectChatMessage') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed, inject, watch } from "vue";
import { useI18n } from "vue-i18n";
import { getAssetPath } from "@/core/helpers/assets";
import { useGalleryStore, type Gallery } from "@/stores/gallery";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";
import { useSettingsStore, type UiSettings, FormLogo } from "@/stores/settings";
import { useChatStore, type Users, Groups } from "@/stores/chat";
import { useAuthStore } from "@/stores/auth";

import Multiselect from '@vueform/multiselect';
import ErrorLabel from "@/components/global/ErrorLabel.vue";

export default defineComponent({
    name: "chat",
    components: {
        Multiselect,
        ErrorLabel
    },
    setup() {
        const { t, te } = useI18n();
        const authStore = useAuthStore();
        const store = useChatStore();
        const settingsStore = useSettingsStore();
        const pusher = inject('pusher');

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);
        const chatContentRef = ref<HTMLButtonElement | null>(null);
        const createGroupButton = ref<HTMLButtonElement | null>(null);

        const chatApp = ref({
            isChatOpen: false,
            userId: "",
            user: "",
            role: "",
            loading: false,
            message: "",
            messages: [],
            scrollPoint: 0,
            activeTab: 'private',
            groupId: "",
            groupChatCreateStatus: false,
            chatRoomName: "",
            chatRoomUsers: [],
        });

        const formErrors = ref<any>({
            chatRoomName: "",
            chatRoomUsers: [],
        });

        const userList = ref<Array<Users>>([]);
        const convertedUserList = ref<Array<any>>([]);
        const groupList = ref<Array<Groups>>([]);
        const userMessagesList = ref<Array<any>>([]);
        const userOldMessagesList = ref<Array<any>>([]);

        const groupChatMessagesList = ref<Array<any>>([]);
        const groupChatOldMessagesList = ref<Array<any>>([]);

        onMounted(() => {
            fetchUserList();
            fetchGroupList();
        });

        const selectTab = (tab) => {
            chatApp.value.activeTab = tab;
            chatApp.value.isChatOpen = false;
        };

        const computedLogo = computed(() => {
            const settings = settingsStore.generalSettings;
            return typeof settings?.logo != 'undefined' ? settings.logo : getAssetPath('media/logo/logo.png');
        });

        const currentUser = computed(() => {
            const user = authStore.user;
            return typeof user?.userId != 'undefined' ? user?.userId : "-";
        });

        const selectedUser = computed(() => {
            return chatApp.value.userId;
        });

        const isSelectedUser = (userId) => {
            return String(selectedUser.value) === String(userId);
        };

        const selectedGroup = computed(() => {
            return chatApp.value.groupId;
        });

        const isSelectedGroup = (groupId) => {
            return String(selectedGroup.value) === String(groupId);
        }

        watch(selectedUser, (newVal, oldVal) => {
            // Force a re-render by triggering a change in the reactive property
            chatApp.value = { ...chatApp.value };
        });

        //open user chat
        const openChat = async (userId) => {
            store.subscribeToChannel(userId, currentUser.value);

            const filteredUserList = userList.value.filter(item => item.id === userId);
            chatApp.value.userId = userId;
            chatApp.value.user = filteredUserList[0].first_name + ' ' + filteredUserList[0].last_name;
            chatApp.value.role = filteredUserList[0].user_role;
            chatApp.value.isChatOpen = true;

            await fetchUserChatList(userId);
            await updateMessageSeenStatus();
        }

        //fetch selected user related chats
        const fetchUserChatList = async (userId) => {
            const inputs = {
                userId: userId,
            };
            await store.fetchUserMessages(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userMessagesList.value.splice(0, userMessagesList.value.length, ...store.userMessagesList);
                userOldMessagesList.value.splice(0, userOldMessagesList.value.length, ...store.userMessagesList);
                await scrollToChatBottom();
            }
        }

        // const userMessages = computed(() => {
        //     return store.userMessagesList;
        // });

        watch(() => store.userMessagesList, async (newUserMessagesList, oldUserMessagesList) => {
            // User messages list is updated, add new messages to the existing arrays without duplicates
            if (store.userMessagesList.length > 0) {
                const existingIds = new Set(userMessagesList.value.map((message) => message.id));
                
                // Filter out duplicates and add to userMessagesList
                const uniqueNewMessages = store.userMessagesList.filter((newMessage) => {
                    return !existingIds.has(newMessage.id);
                });
                userMessagesList.value.push(...uniqueNewMessages);

                // Filter out duplicates and add to userOldMessagesList
                const uniqueNewMessagesForOld = store.userMessagesList.filter((newMessage) => {
                    return !existingIds.has(newMessage.id);
                });
                userOldMessagesList.value.push(...uniqueNewMessagesForOld);
            }

            await scrollToChatBottom();
        }, { deep: true });

        //close chat screen
        const closeChat = async () => {
            chatApp.value.isChatOpen = false;
        }

        //send message
        const send = async () => {
            chatApp.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }

            let userId = "";
            let chatType = 0;
            if(chatApp.value.activeTab == 'private'){
                userId = chatApp.value.userId;
                chatType = 0;
            } else {
                userId = chatApp.value.groupId;
                chatType = 1;
            }

            const inputs = {
                userId: userId,
                message: chatApp.value.message,
                chatType: chatType
            };
            
            const response = await store.sendMessage(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                chatApp.value.message = "";
                await scrollToChatBottom();
            }
            submitButton.value!.disabled = false;
            chatApp.value.loading = false;
        }

        

        const fetchUserList = async () => {
            await store.fetchMyUsers();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userList.value.splice(0, userList.value.length, ...store.userList);

                const mappedList = store.userList.map(user => ({
                    value: user.id,
                    label: `${user.first_name} ${user.last_name}`
                }));
                convertedUserList.value.splice(0, convertedUserList.value.length, ...mappedList);
                globalChannelConnection();
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

        const fetchGroupList = async () => {
            await store.fetchMyChatGroups();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                groupList.value.splice(0, groupList.value.length, ...store.chatGroupList);
                globalGroupChatChannelConnection(groupList.value);
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

        const computedChatRooms = computed(()=> {
            return store.chatGroupList;
        });

        const computedChatRoomUnseenMsg = computed(()=> {
            return store.chatGroupList.reduce((sum, chatGroup) => sum + chatGroup.unseen_messages, 0);
        });

        const scrollToChatBottom = async () => {
            setTimeout(()=>{
                if(chatContentRef && chatContentRef.value){
                    chatContentRef.value.scrollTop = chatContentRef.value.scrollHeight;

                    chatApp.value.scrollPoint = chatContentRef.value.scrollTop;
                }
            },300);
        }

        let scrollTimer;
        let prevScrollHeight = 0;
        const scrollMessages = async (e) => {
            clearTimeout(scrollTimer);

            scrollTimer = setTimeout(async () => {
                // Check if the user has scrolled to the exact top
                if (e.target.scrollTop === 0) {
                    if (userMessagesList.value.length > 0) {
                        const oldMessage = userMessagesList.value[0];

                        const inputs = {
                            userId: chatApp.value.userId,
                            earlierDate: oldMessage.created_at
                        };

                        await store.fetchUserOldMessages(inputs);
                        const error = Object.values(store.errors);

                        if (error.length === 0) {
                            const newMessages = store.userOldMessagesList;

                            if (newMessages.length > 0) {
                                // Assuming created_at is a timestamp indicating the message's position
                                const lastFetchedMessage = newMessages[newMessages.length - 1].created_at;
                                const indexToStop = userMessagesList.value.findIndex(
                                    (message) => message.created_at === lastFetchedMessage
                                );

                                if (indexToStop >= 0) {
                                    // Stop loading more messages after a certain point
                                    userMessagesList.value.splice(indexToStop + 1);
                                }

                                userMessagesList.value = [
                                ...newMessages,
                                ...userOldMessagesList.value,
                                ].sort((a, b) => a.id - b.id);
                                userOldMessagesList.value = [...newMessages, ...userOldMessagesList.value];
                                
                                // Scroll to the position where the new messages start
                                if (chatContentRef.value) {
                                    chatContentRef.value.scrollTop = chatContentRef.value.scrollHeight - prevScrollHeight;
                                    prevScrollHeight = chatContentRef.value.scrollHeight;
                                }
                            }
                        }
                    }
                }
            }, 300);
        };

        const updateMessageSeenStatus = async () => {
            if(chatApp.value.activeTab == 'private'){
                const inputs = {
                    userId: chatApp.value.userId
                };
                await store.updateUserMessageSeenStatus(inputs);
                const error = Object.values(store.errors);

                if (error.length === 0) {

                }
            } else {
                await updateGroupMessageSeenStatus();
            }
            
        }

        const computedUnseenMessages = (userId) => {
            return computed(() => {
                const user = store.userList.find(user => user.id === userId);
                return user ? user.unseen_messages : 0;
            });
        };

        const computedUsers = computed(()=> {
            return store.userList;
        });

        const computedPrivateChatUnseenMsg = computed(()=> {
            return store.userList.reduce((sum, userList) => sum + userList.unseen_messages, 0);
        });

        const globalChannelConnection = async () => {
            await store.setGlobalChannelConnection(currentUser.value);
            const error = Object.values(store.errors);
        }

        const globalGroupChatChannelConnection = async(groupList) => {
            for (const group of groupList) {
                await store.setGlobalGroupChatChannelConnection(group.id, currentUser.value);
                const error = Object.values(store.errors);
            }
        }

        const openGroupChat = async(groupId) => {
            console.log('selected group is ' + groupId);
            const filteredGroupList = groupList.value.filter(item => item.id === groupId);
            chatApp.value.user = filteredGroupList[0].name;
            chatApp.value.isChatOpen = true;
            chatApp.value.groupId = groupId;

            store.saveGroupChatId(groupId);
            store.subscribeGroupChannel(groupId, currentUser.value);

            await fetchGroupChatList(groupId);
            await updateGroupMessageSeenStatus();
        }

        //fetch selected user related chats
        const fetchGroupChatList = async (groupId) => {
            const inputs = {
                groupId: groupId,
            };
            await store.fetchUserGroupMessages(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userMessagesList.value.splice(0, userMessagesList.value.length, ...store.userMessagesList);
                userOldMessagesList.value.splice(0, userOldMessagesList.value.length, ...store.userMessagesList);
                await scrollToChatBottom();
            }
        }

        const updateGroupMessageSeenStatus = async() => {
            const inputs = {
                groupId: chatApp.value.groupId
            };
            await store.updateGroupMessageSeenStatus(inputs);
            const error = Object.values(store.errors);

            if (error.length === 0) {

            }
        }

        const createGroupChat = () => {
            chatApp.value.groupChatCreateStatus = true;
        }

        const submitChatRoom = async() => {
            const inputs = {
                name: chatApp.value.chatRoomName,
                users: chatApp.value.chatRoomUsers,
            };
            chatApp.value.loading = true;
            if (createGroupButton.value) { 
                createGroupButton.value!.disabled = true;
            }
            await store.chatRoomRegistration(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: translate('goodJob') + '!',
                    text: translate('recordHasBeenSuccesfullyAdded'),
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('okGotIt') + '!'
                }).then(() => {
                    chatApp.value.chatRoomName = "";
                    chatApp.value.chatRoomUsers = [];
                    chatApp.value.groupChatCreateStatus = false;
                    chatApp.value.loading = false;

                    fetchGroupList();
                });
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: translate(error[0] as string),
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    store.errors = {};
                    store.formDataErrors = {};
                })
            }
            createGroupButton.value!.disabled = false;
            chatApp.value.loading = false;
        }

        const backToChatList = () => {
            chatApp.value.chatRoomName = "";
            chatApp.value.chatRoomUsers = [];
            chatApp.value.groupChatCreateStatus = false;
        }

        return {
            translate,
            submitButton,
            computedLogo,
            chatApp,
            send,
            computedUsers,
            userMessagesList,
            openChat,
            closeChat,
            currentUser,
            chatContentRef,
            scrollMessages,
            isSelectedUser,
            isSelectedGroup,
            updateMessageSeenStatus,
            computedUnseenMessages,
            selectTab,
            groupList,
            computedChatRooms,
            openGroupChat,
            groupChatMessagesList,
            createGroupChat,
            convertedUserList,
            createGroupButton,
            submitChatRoom,
            formErrors,
            backToChatList,
            computedChatRoomUnseenMsg,
            computedPrivateChatUnseenMsg
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>