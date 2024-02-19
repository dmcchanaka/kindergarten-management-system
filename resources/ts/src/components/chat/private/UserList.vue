<template>
    <div class="max-h-96 overflow-y-auto py-2">
        <div v-for="(item, index) in computedUsers" :key="item.id" @click="openChat(item.id)" :class="{ 'bg-gray-700 hover:bg-gray-800': isSelectedUser(item.id) }" class="flex p-2 items-center mb-3 cursor-pointer rounded-md bg-gray-100 hover:bg-gray-100">
            <div class="relative">
                <img class="h-8 w-8 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                <span class="absolute -top-3.5 -left-3 inline-flex items-center justify-center w-4 h-4 ms-2 text-xs font-semibold text-blue-800 bg-blue-300 rounded-full" v-if="item.unseen_messages">{{ item.unseen_messages }}</span>
            </div>
            <div class="ml-3">
                <div class="font-semibold">{{ item.first_name }} {{ item.last_name }} {{ isSelectedUser(item.id) }}</div>
                <span class="text-gray-500">{{ item.user_role }}</span>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useChatStore, type Users } from "@/stores/chat";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
    name: "user-list",
    props: {
        userList: { type: Array as () => Array<Users>, required: true },
    },
    setup(props, { emit }) {
        const store = useChatStore();
        const authStore = useAuthStore();

        const chatApp = ref({
            userId: "",
        });

        const computedUsers = computed(()=> {
            return props.userList;
        });

        const selectedUser = computed(() => {
            return chatApp.value.userId;
        });

        const isSelectedUser = (userId) => {
            // return String(selectedUser.value) === String(userId);
            emit("select-chat-user", userId);
        };

        const openChat = (userId) => {
            emit("open-chat", userId);
        }

        return {
            chatApp,
            computedUsers,
            isSelectedUser,
            openChat
        }
    }
});
</script>