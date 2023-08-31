<template>
    <div>
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        #
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Description
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in userRoleList" :key="index" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ item.role_id }}
                    </th>
                    <td class="px-6 py-4">
                        {{ item.description }}
                    </td>
                    <td class="flex items-center px-6 py-4 space-x-3">
                        <a href="#" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Edit</a>
                        <a href="#" class="font-medium text-red-600 dark:text-red-500 hover:underline">Remove</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    name: "table-user-roles",
    props: {
        userRoleList: { type: Array as () => Array<any>, required: true },
    },
    components: {
    },
    setup() {
    },
});
</script>