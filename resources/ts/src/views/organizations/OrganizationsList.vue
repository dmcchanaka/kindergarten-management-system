<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap -mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0">Organization List</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-0 mb-2 text-right">
                    <router-link to="create-organization" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"> 
                        <fa icon="plus" />&nbsp;&nbsp;Add New Organization
                    </router-link>
                </div>
            </div>
        </div>
        <KGMS_TableOrganization :organizationList="organizationList" @fetch-rows="fetchOrganizations" @edit-organization="editOrganization"></KGMS_TableOrganization>
    </div>
    
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useOrganizationsStore, type Organization } from "@/stores/organizations";

import KGMS_TableOrganization from "@/components/organizations/table/OrganizationsTable.vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";

export default defineComponent({
    name: "view-organizations",
    components: {
        KGMS_TableOrganization
    },
    setup() {
        const store = useOrganizationsStore();
        const router = useRouter();
        const organizationList = ref<Array<Organization>>([]);

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

        const editOrganization = (id: string) => {
            store.saveOrganizationId(id);
            router.push({ name: "edit-organization" });
        }

        return {
            organizationList,
            fetchOrganizations,
            editOrganization
        }
    },
});
</script>