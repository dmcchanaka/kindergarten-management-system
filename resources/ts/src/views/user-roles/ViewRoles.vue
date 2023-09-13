<template>
    <div
        class="mt-5 w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0">User Role List</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link to="/add-user-role" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                        <PlusIcon class="h-5 w-5 text-white-600 hover:text-gray-500 hover:scale-110" />&nbsp;&nbsp;Add User Role
                    </router-link>
                </div>
            </div>
        </div>
        <UserRoleList @edit-user-type="editUserType" :userRoleList="userRoleList"></UserRoleList>
    </div>
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { useUserRoleStore, type UserRole } from "@/stores/userRole";
import { useRouter } from "vue-router";

import UserRoleList from "@/components/user-roles/table/UserRoleList.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";

import { PlusIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
    name: "view-user-roles",
    components: {
        Menu,
        MenuButton,
        MenuItem,
        MenuItems,
        UserRoleList,
        PlusIcon
    },
    setup() {
        const store = useUserRoleStore();
        const router = useRouter();

        const userRoleList = ref<Array<UserRole>>([]);

        onMounted(async () => {
            await fetchUserRoleList();
        });

        const fetchUserRoleList = async () => {
            await store.fetchUserRoles();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userRoleList.value.splice(0, userRoleList.value.length, ...store.userRoleList);
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

        const editUserType = (userTypeId) => {
            store.saveUserRoleId(userTypeId);
            router.push({ name: "edit-user-role" });
        }

        return {
            userRoleList,
            editUserType
        }
    },
});
</script>