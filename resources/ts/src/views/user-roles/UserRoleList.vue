<template>
    <div
        class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0 text-header">{{ translate('userRoleList') }}</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link to="/add-user-role" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                        <fa icon="plus" />&nbsp;&nbsp;{{ translate('addUserRole') }}
                    </router-link>
                </div>
            </div>
        </div>
        <Datatable
            key="role_id"
            @on-sort="sort"
            @on-items-select="onItemSelect"
            :data="tableData"
            :header="tableHeader"
            :enable-items-per-page-dropdown="true"
            :checkbox-enabled="false"
            checkbox-label="id"
        >
            <template v-slot:id="{ row: userRole }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ userRole.role_id }}</a>
            </template>
            <template v-slot:description="{ row: userRole }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ userRole.description }}</a>
            </template>
            <template v-slot:actions="{ row: userRole }">
                <a @click="editUserType(userRole.role_id)" class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <a class="cursor-pointer text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                    <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                </a>
            </template>
        </Datatable>
    </div>
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useUserRoleStore, type UserRole } from "@/stores/userRole";
import { useRouter } from "vue-router";

import UserRoleList from "@/components/user-roles/table/UserRoleList.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";
import { useI18n } from "vue-i18n";

import Datatable from "@/components/table/Datatable.vue";

export default defineComponent({
    name: "view-user-roles",
    components: {
        Datatable
    },
    setup() {
        const { t, te } = useI18n();
        const store = useUserRoleStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const selectedIds = ref<Array<number>>([]);
        const userRoleList = ref<Array<UserRole>>([]);
        const tableData = ref<Array<UserRole>>([]);

        const tableHeader = ref([
        {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("description") }),
                columnLabel: "description",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("actions") }),
                columnLabel: "actions",
                sortEnabled: false,
                columnWidth: 50,
                textAlign: "text-center",
            },
        ]);

        onMounted(async () => {
            await fetchUserRoleList();
        });

        const fetchUserRoleList = async () => {
            await store.fetchUserRoles();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userRoleList.value.splice(0, userRoleList.value.length, ...store.userRoleList);
                tableData.value.splice(0, tableData.value.length, ...store.userRoleList);
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

        const search = ref<string>("");
        const searchItems = () => {
            tableData.value.splice(0, tableData.value.length, ...userRoleList.value);
            if (search.value !== "") {
            let results: Array<UserRole> = [];
            for (let j = 0; j < tableData.value.length; j++) {
                if (searchingFunc(tableData.value[j], search.value.toLowerCase())) {
                results.push(tableData.value[j]);
                }
            }
            tableData.value.splice(0, tableData.value.length, ...results);
            }
        };
    
        const searchingFunc = (obj: any, value: string): boolean => {
            for (let key in obj) {
            if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
                if (obj[key].indexOf(value) != -1) {
                return true;
                }
            }
            }
            return false;
        };
    
        const sort = (sort: Sort) => {
            const reverse: boolean = sort.order === "asc";
            if (sort.label) {
            arraySort(tableData.value, sort.label, { reverse });
            }
        };
        const onItemSelect = (selectedItems: Array<number>) => {
            selectedIds.value = selectedItems;
        };

        return {
            editUserType,
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
            translate
        }
    },
});
</script>