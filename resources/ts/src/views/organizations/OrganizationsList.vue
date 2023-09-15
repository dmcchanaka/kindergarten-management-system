<template>
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Organizations</h2>
            <div class="flex flex-col mt-1 sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">


            </div>
        </div>
        <div class="flex mt-5 lg:ml-4 lg:mt-0">
            <span class="sm:ml-3">
                <button type="button"
                    @click="this.$router.push({ name: 'create-organization' });"
                    class="inline-flex items-center px-3 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Add New Organization
                </button>
            </span>
        </div>
    </div>

    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
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