import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface Organization {
    id: string;
    name: string;
    address: string;
    contact_num: string;
    principal_id: null | number;
    principal_name: null | string;
    email: string;
    created_at: string;
}

export interface OrganizationFormData {
    name: string;
    address: string;
    contact_num: string;
    email: string;
    principal_id: number;
}

export interface OrganizationFormDataErrors {
    name: Array<string>;
    address: Array<string>;
    contact_num: Array<string>;
    email: Array<string>;
    principal_id: Array<string>;
}

export const useOrganizationsStore = defineStore("Organization", () => {
    const errors = ref({});
    const OrganizationList = ref<Organization[]>([]);
    const organizationId = ref("");

    const formDataErrors = ref({});

    const formData = ref<OrganizationFormData>({
        name: "",
        address: "",
        contact_num: "",
        email: "",
        principal_id: NaN,
    });

    function setOrganizationFormData(data: OrganizationFormData) {
        if (![undefined, null, ""].includes(data?.name)) {
            formData.value.name = data.name;
        }

        if (![undefined, null, ""].includes(data?.address)) {
            formData.value.address = data.address;
        }

        if (![undefined, null, "", "0", 0].includes(data?.contact_num)) {
            formData.value.contact_num = data.contact_num;
        }

        if (![undefined, null, ""].includes(data?.email)) {
            formData.value.email = data.email;
        }

        if (![NaN].includes(data?.principal_id)) {
            formData.value.principal_id = data.principal_id;
        }
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    async function fetchOrganizations() {
        return await ApiService.get("/organization/list")
            .then(({ data }) => {
                setOrganizations(data.organizations);
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message: response.data.errors,
                        status: response.status,
                    };
                    setError(error);
                }
            });
    }

    async function fetchOrganization() {
        return await ApiService.get(
            `/organization/find/${organizationId.value}`
        )
            .then(({ data }) => {
                formData.value.name = data.organization.name;
                formData.value.address = data.organization.address;
                formData.value.contact_num = data.organization.contact_num;
                formData.value.email = data.organization.email;
                formData.value.principal_id = data.organization.principal_id;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message: response.data.errors,
                        status: response.status,
                    };
                    setError(error);
                }
            });
    }

    async function saveOrganization(formData: OrganizationFormData) {
        return await ApiService.post("/organization/create", formData)
            .then(({ data }) => {
                return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    let errorMsg = '';
                    if (typeof response.data.errors === 'object') {
                        errorMsg = 'Some fields are missing';
                    } else {
                        errorMsg = response.data.errors;
                    }
                    const error = {
                        message : errorMsg,
                        status : response.status,
                    }
                    setError(error);
                    setFormDataErrors(response.data.errors);
                }
            });
    }

    async function updateOrganization(formData: OrganizationFormData) {
        return await ApiService.post(
            `/organization/update/${organizationId.value}`,
            formData
        )
        .then(({ data }) => {
            return data;
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                let errorMsg = '';
                if (typeof response.data.errors === 'object') {
                    errorMsg = 'Some fields are missing';
                } else {
                    errorMsg = response.data.errors;
                }
                const error = {
                    message : errorMsg,
                    status : response.status,
                }
                setError(error);
                setFormDataErrors(response.data.errors);
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

    function saveOrganizationId(id: string) {
        organizationId.value = id;
    }

    return {
        fetchOrganizations,
        OrganizationList,
        errors,
        setOrganizationFormData,
        setFormDataErrors,
        formData,
        formDataErrors,
        saveOrganization,
        saveOrganizationId,
        fetchOrganization,
        updateOrganization,
        organizationId
    };
});
