<template>
    <div>
        <table
            class="w-full text-sm text-left text-gray-500 dark:text-gray-400"
        >
            <thead
                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400"
            >
                <tr>
                    <th scope="col" class="px-6 py-3">#</th>
                    <th scope="col" class="px-6 py-3">{{ translate('name') }}</th>
                    <th scope="col" class="px-6 py-3">{{ translate('address') }}</th>
                    <th scope="col" class="px-6 py-3">{{ translate('phoneNumber') }}</th>
                    <th scope="col" class="px-6 py-3">{{ translate('email') }}</th>
                    <th scope="col" class="px-6 py-3">{{ translate('registeredAt') }}</th>
                    <th scope="col" class="px-6 py-3">{{ translate('action') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="(item, index) in organizationList"
                    :key="index"
                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                >
                    <th
                        scope="row"
                        class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                    >
                        {{ ++index }}
                    </th>
                    <td class="px-6 py-4">
                        {{ item.name }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.address }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.contact_num }}
                    </td>
                    <td class="px-6 py-4">
                        {{ item.email }}
                    </td>
                    <td class="px-6 py-4">
                        {{
                            item.created_at
                                .split("T")[0]
                                .split("-")
                                .reverse()
                                .join(" / ")
                        }}
                    </td>
                    <td class="flex items-center px-6 py-4 space-x-3">
                        <!-- <button
                            href="#"
                            @click="editOrganization(item.e_id);"
                            class="font-medium text-blue-600 dark:text-blue-500 hover:underline"
                            >Edit</button>
                        <button
                            href="#"
                            @click="deleteOrganization(item.e_id)"
                            class="font-medium text-red-600 dark:text-red-500 hover:underline"
                            >Remove</button> -->

                        <a href="#" @click="editOrganization(item.e_id);"  class="mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                            <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                        </a>
                        <a href="#" @click="deleteOrganization(item.e_id)" class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                            <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                        </a>


                    </td>
                </tr>
                <tr v-if="organizationList.length == 0">
                    <td colspan="7">
                        <span
                            class="flex justify-center mt-5 font-bold text-red-600 text-md"
                            >No organizations found!</span
                        >
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";
import ApiService from "@/core/services/ApiService";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";

export default defineComponent({
    name: "table-organizations",
    props: {
        organizationList: { type: Array as () => Array<any>, required: true },
    },
    components: {},
    setup(props, { emit }) {
        const { t, te } = useI18n();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const deleteOrganization = async (id: string) => {
            await Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result: any) => {
                if (result.isConfirmed) {
                    return ApiService.delete(`/organization/delete/${id}`)
                        .then(({ data }) => {
                            Swal.fire({
                                title: "Success",
                                text: data.message,
                                icon: "success",
                                confirmButtonColor: "#3085d6",
                                confirmButtonText: "Close",
                            });
                            refreshTable();
                        })
                        .catch(({ response }) => {
                            if (response.status !== 200) {
                                Swal.fire({
                                    title: "Oops...",
                                    text: response.data.message,
                                    icon: "error",
                                    confirmButtonColor: "#3085d6",
                                    confirmButtonText: "Try again!",
                                });
                            }
                        });
                }
            });
        };

        const refreshTable = () => {
            emit("fetch-rows");
        };

        const editOrganization = (id: number) => {
            emit("edit-organization", id);
        }

        return {
            deleteOrganization,
            editOrganization,
            translate
        };
    },
});
</script>
