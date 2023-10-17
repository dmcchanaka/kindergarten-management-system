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
        </Datatable>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useAuthStore } from "@/stores/auth";

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

        const selectedIds = ref<Array<number>>([]);
        const tableHeader = ref([]);
        const tableData = ref<Array<any>>([]);

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