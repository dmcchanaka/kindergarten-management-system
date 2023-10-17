<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0">Class Rooms List</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link v-if="isPermittedRoute('add-class-room')" to="/add-class-room" class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                        <fa icon="plus" />&nbsp;&nbsp;Add Class Room
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
            <template v-slot:id="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ cls.id }}</a>
            </template>
            <template v-slot:name="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ cls.name }}</a>
            </template>
            <template v-slot:email="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ cls.email }}</a>
            </template>
            <template v-slot:phone_number="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ cls.phone_number }}</a>
            </template>
            <template v-slot:created_at="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ cls.created_at }}</a>
            </template>
            <template v-slot:teachers="{ row: cls }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6" v-for="(item, index) in cls.teachers">
                    <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500">{{ item.name }}</span>
                </a>
            </template>
            <template v-slot:actions="{ row: cls }">
                <a class="mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <a class="text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                    <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                </a>
            </template>
        </Datatable>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useClassRoomStore, type Teacher, ClassRoomForm, ClassRoom } from "@/stores/classRoom";

import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";

import Datatable from "@/components/table/Datatable.vue";

export default defineComponent({
    name: "view-class-room-list",
    components: {
        Datatable
    },
    setup(){
        const authStore = useAuthStore();
        const store = useClassRoomStore();

        const selectedIds = ref<Array<number>>([]);
        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: "Name",
                columnLabel: "name",
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
                columnLabel: "phone_number",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: "Registered at",
                columnLabel: "created_at",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: "Teachers",
                columnLabel: "teachers",
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
        const classRoomsList = ref<Array<ClassRoom>>([]);
        const tableData = ref<Array<ClassRoom>>([]);

        onMounted(async () => {
            await fetchClassRoomList();
        });

        const fetchClassRoomList = async() => {
            await store.fetchClassRoomList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                classRoomsList.value.splice(0, classRoomsList.value.length, ...store.classRoomList);
                tableData.value.splice(0, tableData.value.length, ...store.classRoomList);
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

        const isPermittedRoute = (currentRoute) => {
            if(authStore.userPermissions.length > 0){
              return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }

        return {
            tableHeader,
            tableData,
            sort,
            onItemSelect,
            isPermittedRoute
        }
    }
});
</script>