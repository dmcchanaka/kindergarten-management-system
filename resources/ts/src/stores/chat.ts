import { ref, inject } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import Pusher from 'pusher-js';

export interface Users {
    id: number;
    first_name: string;
    last_name: string;
    user_role: string;
    unseen_messages: number;
}

export interface Groups {
    id: number;
    name: string;
    unseen_messages: number;
}

export interface ChatRoomForm {
    name: string;
    users: any[];
}

export const useChatStore = defineStore("chat", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const userList = ref<Users[]>([]);
    const chatGroupList = ref<Groups[]>([]);
    const userMessagesList = ref<any[]>([]);
    const userOldMessagesList = ref<any[]>([]);

    const groupChatMessagesList = ref<any[]>([]);
    const groupChatOldMessagesList = ref<any[]>([]);

    const pusher = inject('pusher');

    function setGlobalChannelConnection(currentUser) {
        const channel = pusher.subscribe(`paulino-channel-${currentUser}`);
        channel.bind('paulino-event', function(data) {

            const newMessage = data.message;
            // Check if the message already exists in userMessagesList
            const isDuplicate = userMessagesList.value.some(message => message.id === newMessage.id);

            if (!isDuplicate) {
                // If not a duplicate, add the new message to userMessagesList
                userMessagesList.value.push(newMessage);

                const userIndex = userList.value.findIndex(user => user.id === data.message.sender_id);
                if (userIndex !== -1) {
                    userList.value[userIndex].unseen_messages += 1;
                }
            }
        });
    }

    function setGlobalGroupChatChannelConnection(groupId, currentUser) {
        const channel = pusher.subscribe(`paulino-group-chat-channel-${groupId}`);
        channel.bind('paulino-group-chat-event', function(data) {

            const newMessage = data.message;
            // Check if the message already exists in userMessagesList
            const isDuplicate = userMessagesList.value.some(message => message.id === newMessage.id);

            if (!isDuplicate) {
                // If not a duplicate, add the new message to userMessagesList
                userMessagesList.value.push(newMessage);

                const userIndex = chatGroupList.value.findIndex(group => group.id === data.message.group_id && currentUser !== data.message.sender_id);
                if (userIndex !== -1) {
                    chatGroupList.value[userIndex].unseen_messages += 1;
                }
            }
        });
    }

    function subscribeToChannel(userId, currentUser) {
        const channel = pusher.subscribe(`paulino-channel-${currentUser}`);
        channel.bind('paulino-event', function(data) {

            const newMessage = data.message;
            // Check if the message already exists in userMessagesList
            const isDuplicate = userMessagesList.value.some(message => message.id === newMessage.id);

            if (!isDuplicate) {
                // If not a duplicate, add the new message to userMessagesList
                userMessagesList.value.push(newMessage);
            }
            
        });
    }

    function subscribeGroupChannel(groupId, currentUser) {
        const channel = pusher.subscribe(`paulino-group-chat-channel-${groupId}`);
        channel.bind('paulino-group-chat-event', function(data) {

            const newMessage = data.message;
            // Check if the message already exists in userMessagesList
            const isDuplicate = userMessagesList.value.some(message => message.id === newMessage.id);

            if (!isDuplicate) {
                // If not a duplicate, add the new message to userMessagesList
                userMessagesList.value.push(newMessage);
            }
        });
    }

    function fetchMyUsers(){
        return ApiService.post("/chat-user-list", {})
        .then(({ data }) => {
            setUsers(data.userList);
        })
        .catch(({ response }) => {
            if (response.status === 404) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setUsers(users: Users[]) {
        userList.value = users;
        errors.value = {};
    }

    function fetchMyChatGroups(){
        return ApiService.post("/chat-groups-list", {})
        .then(({ data }) => {
            setChatGroups(data.chatGroupList);
        })
        .catch(({ response }) => {
            if (response.status === 404) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setChatGroups(groups: Groups[]) {
        chatGroupList.value = groups;
        errors.value = {};
    }

    function fetchUserMessages(input){
        return ApiService.post("/user-messages", input)
        .then(({ data }) => {
            setUserMessages(data.messageList);
        })
        .catch(({ response }) => {
            if (response.status === 404) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function fetchUserOldMessages(input){
        return ApiService.post("/user-old-messages", input)
        .then(({ data }) => {
            setUserOldMessages(data.messageList);
        })
        .catch(({ response }) => {
            if (response.status === 404) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setUserOldMessages(messages: any[]) {
        userOldMessagesList.value = messages;
        errors.value = {};
    }

    function setUserMessages(messages: any[]) {
        userMessagesList.value = messages;
        errors.value = {};
    }

    function sendMessage(input){
        return ApiService.post("/send-message", input)
            .then(({ data }) => {
                userMessagesList.value.push(data.message);
                return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function updateUserMessageSeenStatus(inputs){
        return ApiService.post("/update-message-seen", inputs)
        .then(({ data }) => {
            const userIndex = userList.value.findIndex(user => user.id === data.sender);
            if (userIndex !== -1) {
                userList.value[userIndex].unseen_messages = 0;
            }
            return data;
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function fetchUserGroupMessages(input){
        return ApiService.post("/user-group-messages", input)
        .then(({ data }) => {
            setGroupChatMessages(data.groupMessageList);
        })
        .catch(({ response }) => {
            if (response.status === 404) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setGroupChatMessages(messages: any[]) {
        userMessagesList.value = messages;
        errors.value = {};
    }

    function chatRoomRegistration(chatRoom: ChatRoomForm){
        return ApiService.post("/chat-room-registration", chatRoom)
            .then(({ data }) => {
               
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    let errorMsg = '';
                    if (typeof response.data.errors === 'object') {
                        errorMsg = 'someFieldsAreMissing';
                    } else {
                        errorMsg = response.data.errors;
                    }
                    const error = {
                        message : errorMsg,
                        status : response.status,
                    }
                    setError(error);
                    setFormDataErrors(response.data.errors);
                }
            });
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    function updateGroupMessageSeenStatus(inputs){
        return ApiService.post("/update-group-message-seen", inputs)
        .then(({ data }) => {
            const userIndex = chatGroupList.value.findIndex(user => user.id === data.group);
            if (userIndex !== -1) {
                chatGroupList.value[userIndex].unseen_messages = 0;
            }
            return data;
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    return {
        fetchMyUsers,
        fetchMyChatGroups,
        fetchUserMessages,
        sendMessage,
        errors,
        subscribeToChannel,
        userList,
        chatGroupList,
        userMessagesList,
        fetchUserOldMessages,
        userOldMessagesList,
        updateUserMessageSeenStatus,
        setGlobalChannelConnection,
        subscribeGroupChannel,
        fetchUserGroupMessages,
        chatRoomRegistration,
        formDataErrors,
        updateGroupMessageSeenStatus,
        setGlobalGroupChatChannelConnection
    }
});