<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div
            class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0">User Role & Permissions</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/user-roles" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                            <ArrowLeftIcon class="h-5 w-5 text-white-600 hover:text-gray-500 hover:scale-110" />&nbsp;&nbsp;Back
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <h6 class="mb-0">Basic Information</h6>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="mb-6">
                                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User Role</label>
                                                <input type="text"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    placeholder="Enter User Role" required>
                                            </div>
                                            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Submit</button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div
                        class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0">Permission Information</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <div class="overflow-x-auto">
                                <table class="w-full mb-0 align-top border-gray-200 text-slate-500 dark:border-white/40">
                                    <thead class="align-bottom">
                                        <tr>
                                            <th colspan="4"
                                                class="px-6 py-3 pl-1 font-semibold capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap border-b-gray-200 dark:border-white/40 dark:text-white">
                                                <p class="mb-0 dark:text-white/60 text-left">Activity</p>
                                            </th>
                                            <th
                                                class="px-6 py-3 font-semibold text-center capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap border-b-gray-200 dark:border-white/40 dark:text-white">
                                                <p class="mb-0 dark:text-white/60">Add</p>
                                            </th>
                                            <th
                                                class="px-6 py-3 font-semibold text-center capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap border-b-gray-200 dark:border-white/40 dark:text-white">
                                                <p class="mb-0 dark:text-white/60">View</p>
                                            </th>
                                            <th
                                                class="px-6 py-3 font-semibold text-center capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap border-b-gray-200 dark:border-white/40 dark:text-white">
                                                <p class="mb-0 dark:text-white/60">Edit</p>
                                            </th>
                                            <th
                                                class="px-6 py-3 font-semibold text-center capitalize align-middle bg-transparent border-b border-solid shadow-none tracking-none whitespace-nowrap border-b-gray-200 dark:border-white/40 dark:text-white">
                                                <p class="mb-0 dark:text-white/60">Delete</p>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-2 pl-1 align-top border-b whitespace-nowrap dark:border-white/40 dark:text-white"
                                                colspan="4">
                                                <div class="my-auto">
                                                    <span
                                                        class="block leading-normal text-sm text-slate-700 dark:text-white">Mentions</span>
                                                </div>
                                            </td>
                                            <td class="p-2 align-top bg-transparent border-b whitespace-nowrap dark:border-white/40 dark:text-white text-center">
                                                <div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" value="" class="sr-only peer" checked>
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="p-2 align-top bg-transparent border-b whitespace-nowrap dark:border-white/40 dark:text-white text-center">
                                                <div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" value="" class="sr-only peer" checked>
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="p-2 align-top bg-transparent border-b whitespace-nowrap dark:border-white/40 dark:text-white text-center">
                                                <div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" value="" class="sr-only peer" checked>
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                            <td class="p-2 align-top bg-transparent border-b whitespace-nowrap dark:border-white/40 dark:text-white text-center">
                                                <div>
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input type="checkbox" value="" class="sr-only peer" checked>
                                                        <div
                                                            class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent } from "vue";

import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
export default defineComponent({
    name: "add-user-role",
    props: {
    },
    components: {
        ArrowLeftIcon
    },
    setup() {
    },
});
</script>