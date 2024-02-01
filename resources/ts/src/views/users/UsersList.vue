<template>
    <div
        class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 sm:mb-0">
                <h6 class="mb-0 text-sub-header">{{ translate('userList') }}</h6>
            </div>
            <div class="flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <input type="text" v-model="search" @input="searchItems()"
                    class="flex-grow max-w-xs bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    :placeholder="translate('searchUsers')" />
                <router-link v-if="isPermittedRoute('add-user')" to="/add-user"
                    class="ml-3 inline-block px-6 py-3 text-lg font-bold text-center text-white uppercase align-middle rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                    <fa icon="plus" />&nbsp;&nbsp;{{ translate('addUser') }}
                </router-link>
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
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.contact_number }}</a>
            </template>
            <template v-slot:user-role="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.user_role }}</a>
            </template>
            <template v-slot:address="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.address }}</a>
            </template>
            <template v-slot:actions="{ row: users }">
                <a v-if="isPermittedRoute('edit-user')" @click="editUser(users.id)" class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <span v-else>&nbsp;</span>
            </template>
        </Datatable>
    </div>
</template>
<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useUserStore, type Users } from "@/stores/users";
import { useAuthStore } from "@/stores/auth";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

import Datatable from "@/components/table/Datatable.vue";
export default defineComponent({
    name: "view-users-list",
    components: {
        Datatable
    },
    setup() {
        const { t, te } = useI18n();
        const authStore = useAuthStore();
        const store = useUserStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

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
                columnName: computed(()=>{ return translate('firstName') }),
                columnLabel: "first_name",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('lastName') }),
                columnLabel: "last_name",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('email') }),
                columnLabel: "email",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('telephone') }),
                columnLabel: "telephone",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('userRole') }),
                columnLabel: "user-role",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: computed(()=>{ return translate('address') }),
                columnLabel: "address",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('actions') }),
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
                const regex = new RegExp(search.value, 'i');
                let results = userList.value.filter((item) => {
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

        const isPermittedRoute = (currentRoute) => {
            if(authStore.userPermissions.length > 0){
              return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }

        const editUser = (userId) => {
            store.saveUserId(userId);
            router.push({ name: "edit-user" });
        }

        return {
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
            isPermittedRoute,
            search,
            editUser,
            translate
        }
    }
});
</script>