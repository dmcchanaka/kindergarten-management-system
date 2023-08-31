<template>
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="min-w-0 flex-1">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">User Roles</h2>
            <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">


            </div>
        </div>
        <div class="mt-5 flex lg:ml-4 lg:mt-0">
            <span class="hidden sm:block">
                <button type="button"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">

                    Edit
                </button>
            </span>

            <span class="ml-3 hidden sm:block">
                <button type="button"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">

                    View
                </button>
            </span>

            <span class="sm:ml-3">
                <button type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">

                    Publish
                </button>
            </span>

            <!-- Dropdown -->
            <Menu as="div" class="relative ml-3 sm:hidden">
                <MenuButton
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:ring-gray-400">
                    More

                </MenuButton>

                <transition enter-active-class="transition ease-out duration-200"
                    enter-from-class="transform opacity-0 scale-95" enter-to-class="transform opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-75" leave-from-class="transform opacity-100 scale-100"
                    leave-to-class="transform opacity-0 scale-95">
                    <MenuItems
                        class="absolute right-0 z-10 -mr-1 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                        <MenuItem v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">Edit</a>
                        </MenuItem>
                        <MenuItem v-slot="{ active }">
                        <a href="#" :class="[active ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']">View</a>
                        </MenuItem>
                    </MenuItems>
                </transition>
            </Menu>
        </div>
    </div>

    <div class="mt-5 w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <UserRoleList></UserRoleList>
    </div>
    
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted } from "vue";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';

import UserRoleList from "@/components/user-roles/table/UserRoleList.vue"

export default defineComponent({
    name: "view-user-roles",
    components: {
        Menu, 
        MenuButton, 
        MenuItem, 
        MenuItems,
        UserRoleList
    },
    setup() {

        onMounted(async() => {
            await fetchUserRoleList();
        });

        const fetchUserRoleList = async() => {
            
        }

        return {

        }
    },
});
</script>