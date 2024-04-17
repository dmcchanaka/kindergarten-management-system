<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <!-- <h6 class="mb-0 text-sub-header">{{ translate('organizationList') }}</h6> -->
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link to="create-organization" class="ml-3 inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins"> 
                        <fa icon="plus" />&nbsp;&nbsp;{{ translate('addNewOrganization') }}
                    </router-link>
                </div>
            </div>
        </div>
        <Datatable
            key="userId"
            @on-sort="sort"
            @on-items-select="onItemSelect"
            :data="organizationList"
            :header="tableHeader"
            :enable-items-per-page-dropdown="true"
            :checkbox-enabled="false"
            checkbox-label="id"
        >
            <template v-slot:id="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.id }}</a>
            </template>
            <template v-slot:name="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.name }}</a>
            </template>
            <template v-slot:address="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.address }}</a>
            </template>
            <template v-slot:phoneNumber="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.contact_num }}</a>
            </template>
            <template v-slot:email="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.email }}</a>
            </template>
            <template v-slot:registeredAt="{ row: org }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ org.created_at.split("T")[0].split("-").reverse().join(" / ") }}</a>
            </template>
            <template v-slot:action="{ row: org }">
                <a v-if="isPermittedRoute('edit-organization')" @click="editOrganization(org.e_id);"  class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <span v-else>&nbsp;</span>
                <a v-if="isPermittedRoute('organization/delete')" @click="deleteOrganization(org.e_id)" class="cursor-pointer text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                    <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                </a>
                <span v-else>&nbsp;</span>
            </template>
        </Datatable>
    </div>
    
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useOrganizationsStore, type Organization } from "@/stores/organizations";

import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import arraySort from "array-sort";
import ApiService from "@/core/services/ApiService";
import { useAuthStore } from "@/stores/auth";

import Datatable from "@/components/table/Datatable.vue";

export default defineComponent({
    name: "view-organizations",
    components: {
        Datatable
    },
    setup() {
        const authStore = useAuthStore();
        const store = useOrganizationsStore();
        const router = useRouter();
        const { t, te } = useI18n();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const selectedIds = ref<Array<number>>([]);
        const organizationList = ref<Array<Organization>>([]);
        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('name') }),
                columnLabel: "name",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('address') }),
                columnLabel: "address",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('phoneNumber') }),
                columnLabel: "phoneNumber",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('email') }),
                columnLabel: "email",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('registeredAt') }),
                columnLabel: "registeredAt",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: computed(()=>{ return translate('action') }),
                columnLabel: "action",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
        ]);

        onMounted(async() => {
            await fetchOrganizations();
        });

        const fetchOrganizations = async() => {
            await store.fetchOrganizations();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                organizationList.value.splice(0, organizationList.value.length, ...store.OrganizationList);
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

        const editOrganization = (id: string) => {
            store.saveOrganizationId(id);
            router.push({ name: "edit-organization" });
        }

        const sort = (sort: Sort) => {
            const reverse: boolean = sort.order === "asc";
            if (sort.label) {
            arraySort(organizationList.value, sort.label, { reverse });
            }
        };
        const onItemSelect = (selectedItems: Array<number>) => {
            selectedIds.value = selectedItems;
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
                            fetchOrganizations();
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

        return {
            organizationList,
            tableHeader,
            fetchOrganizations,
            editOrganization,
            translate,
            sort,
            onItemSelect,
            deleteOrganization,
            isPermittedRoute
        }
    },
});
</script>