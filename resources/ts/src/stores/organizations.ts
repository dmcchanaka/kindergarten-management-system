import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";


export interface Organization {
    id: string,
    name: string,
    address: string,
    contact_num: string,
    email: string,
    created_at: string
}

export const useOrganizationsStore = defineStore("Organization", () => {

    const errors = ref({});
    const OrganizationList = ref<Organization[]>([]);

    async function fetchOrganizations () {
        return await ApiService.get("/organization/list")
        .then(({data}) => {
            setOrganizations(data.organizations);
        })
        .catch(({ response }) => {
            console.log(response);
            if (response.status !== 200) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setOrganizations(organizations: Organization[]) {
        OrganizationList.value = organizations;
        errors.value = {};
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    return {
        fetchOrganizations,
        OrganizationList,
        errors
    }

});