<template>
    <div
        class="mt-5 w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0">User List</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link to="/add-user" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                        <fa icon="plus" />&nbsp;&nbsp;Add User
                    </router-link>
                </div>
            </div>
        </div>
        <Datatable
            key="userId"
            @on-sort="sort"
            @on-items-select="onItemSelect"
            :data="tableData"
            :header="tableHeader"
            :enable-items-per-page-dropdown="true"
            :checkbox-enabled="false"
            checkbox-label="id"
        >
            <template v-slot:id="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.id }}</a>
            </template>
            <template v-slot:first_name="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.first_name }}</a>
            </template>
            <template v-slot:last_name="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.last_name }}</a>
            </template>
            <template v-slot:email="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.email }}</a>
            </template>
            <template v-slot:telephone="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.contact_number }}</a>
            </template>
            <template v-slot:user-role="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.user_role }}</a>
            </template>
            <template v-slot:address="{ row: users }">
                <a class="text-gray-800 fw-bold text-hover-primary mb-1 fs-6">{{ users.address }}</a>
            </template>
            <template v-slot:actions="{ row: users }">
                <a class="mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500">
                    <fa icon="pen-to-square" class="text-purple-700 hover:text-white"></fa>
                </a>
                <a class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500">
                    <fa icon="trash-can" class="text-red-700 hover:text-white"></fa>
                </a>
            </template>
        </Datatable>
    </div>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useUserStore, type Users } from "@/stores/users";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";

import Datatable from "@/components/table/Datatable.vue";
export default defineComponent({
    name: "view-users-list",
    components: {
        Datatable
    },
    setup() {
        const store = useUserStore();

        const selectedIds = ref<Array<number>>([]);
        const userList = ref<Array<Users>>([]);
        const tableData = ref<Array<Users>>([]);

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
                textAlign: "text-center",
            },
            {
                columnName: "Actions",
                columnLabel: "actions",
                sortEnabled: false,
                columnWidth: 50,
                textAlign: "text-center",
            },
        ]);

        const search = ref<string>("");
        const searchItems = () => {
            tableData.value.splice(0, tableData.value.length, ...userList.value);
            if (search.value !== "") {
            let results: Array<Users> = [];
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

        onMounted(async () => {
            await fetchUserList();
        });

        const fetchUserList = async() => {
            await store.fetchUserList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                userList.value.splice(0, userList.value.length, ...store.userList);
                tableData.value.splice(0, tableData.value.length, ...store.userList);
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

        return {
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
        }
    }
});
</script>