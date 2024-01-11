<template>
    <div class="overflow-x-auto">
        <table class="min-w-max w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal border">
                    <th class="py-3 px-6 text-left">#</th>
                    <th class="py-3 px-6 text-left">Description</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                <tr v-for="(item, index) in userRoleList" :key="index" class="border border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="font-medium">{{ item.role_id }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-left">
                        <div class="flex items-center">
                            <span>{{ item.description }}</span>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <span class="bg-green-200 text-green-600 py-1 px-3 rounded-full text-xs">Active</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center">
                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <PencilSquareIcon @click="editUserType(item.role_id)" class="h-5 w-5 text-gray-600 hover:text-green-500 hover:scale-110" />
                            </div>
                            <div class="w-4 mr-2 transform hover:text-purple-500 hover:scale-110">
                                <TrashIcon class="h-5 w-5 text-gray-600 hover:text-red-500 hover:scale-110" />
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";
import { EyeIcon,PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/solid';

export default defineComponent({
    name: "table-user-roles",
    props: {
        userRoleList: { type: Array as () => Array<any>, required: true },
    },
    emits: [
        "edit-user-type",
    ],
    components: {
        EyeIcon,
        PencilSquareIcon,
        TrashIcon,
    },
    setup(props, { emit }) {
        
        const editUserType = (userTypeId) => {
            emit("edit-user-type", userTypeId);
        }

        return {
            editUserType
        }
    },
});
</script>