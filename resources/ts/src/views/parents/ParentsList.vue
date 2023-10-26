<template>
    <div
        class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                <h6 class="mb-0">{{ translate('parentsList') }}</h6>
            </div>
            <div class="flex-none w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <input type="text" v-model="search" @input="searchItems()"
                    class="flex-grow bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Search Parents" />
                <router-link v-if="isPermittedRoute('add-parent')" to="/add-parent"
                    class="ml-3 inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                    <fa icon="plus" />&nbsp;&nbsp;{{ translate('addParent') }}
                </router-link>
            </div>
        </div>
        <Datatable key="userId" @on-sort="sort" @on-items-select="onItemSelect" :data="tableData" :header="tableHeader"
            :enable-items-per-page-dropdown="true" :checkbox-enabled="false" checkbox-label="id">
            <template v-slot:id="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.id }}</a>
            </template>
            <template v-slot:first_name="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.first_name }}</a>
            </template>
            <template v-slot:last_name="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.last_name }}</a>
            </template>
            <template v-slot:email="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.email }}</a>
            </template>
            <template v-slot:telephone="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.phone_number }}</a>
            </template>
            <template v-slot:user-role="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.user_role }}</a>
            </template>
            <template v-slot:address="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.address }}</a>
            </template>
            <template v-slot:actions="{ row: users }">
                <a
                    class="mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <a
                    class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                    <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                </a>
            </template>
        </Datatable>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";

import { useParentStore, type Parent } from "@/stores/parents";
import Datatable from "@/components/table/Datatable.vue";
export default defineComponent({
    name: "parents-list",
    components: {
        Datatable
    },
    setup() {
        const { t, te } = useI18n();
        const store = useParentStore();
        const authStore = useAuthStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const selectedIds = ref<Array<number>>([]);
        const parentList = ref<Array<Parent>>([]);
        const tableData = ref<Array<Parent>>([]);

        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: "First Name",
                columnLabel: "first_name",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: "Last Name",
                columnLabel: "last_name",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: "Email",
                columnLabel: "email",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: "Telephone",
                columnLabel: "telephone",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: "User Role",
                columnLabel: "user-role",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: "Address",
                columnLabel: "address",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: "Actions",
                columnLabel: "actions",
                sortEnabled: false,
                columnWidth: 50,
                textAlign: "text-center",
            },
        ]);

        const isPermittedRoute = (currentRoute) => {
            if (authStore.userPermissions.length > 0) {
                return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }

        onMounted(async () => {
            await fetchParentList();
        });

        const fetchParentList = async () => {
            await store.fetchParentList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                parentList.value.splice(0, parentList.value.length, ...store.parentList);
                tableData.value.splice(0, tableData.value.length, ...store.parentList);
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

        const search = ref<string>("");
        const searchItems = () => {
            tableData.value.splice(0, tableData.value.length, ...parentList.value);
            if (search.value !== "") {
                const regex = new RegExp(search.value, 'i');
                let results = parentList.value.filter((item) => {
                    for (const key in item) {
                        if (Object.prototype.hasOwnProperty.call(item, key)) {
                            if (regex.test(item[key])) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
                tableData.value.splice(0, tableData.value.length, ...results);
            }
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
            translate,
            isPermittedRoute,
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
            search
        }
    }
});
</script>