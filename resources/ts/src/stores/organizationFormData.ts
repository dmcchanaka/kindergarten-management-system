import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface OrganizationFormData {
    oName: string;
    oAddress: string;
    oContact: number;
    oEmail: string;
    pId: string | null | undefined;
    pName: string;
    pContact: number;
    pEmail: string;
    pPassword: string;
}

export interface OrganizationFormDataErrors {
    oName: Array<string>;
    oAddress: Array<string>;
    oContact: Array<string>;
    oEmail: Array<string>;
    pName: Array<string>;
    pContact: Array<string>;
    pEmail: Array<string>;
    pPassword: Array<string>;
}

/**
 * This store build to use in fllowing features
 *  - Create new organization
 *  - Update existing organization
 */

export const useOrganizationFormDataStore = defineStore(
    "CreateOrganization",
    () => {
        const formDataErrors = ref<OrganizationFormDataErrors>({
            oName: [],
            oAddress: [],
            oContact: [],
            oEmail: [],
            pName: [],
            pContact: [],
            pEmail: [],
            pPassword: [],
        });

        const formData = ref<OrganizationFormData>({
            oName: "",
            oAddress: "",
            oContact: 0,
            oEmail: "",
            pId: "",
            pName: "",
            pContact: 0,
            pEmail: "",
            pPassword: "",
        });

        function setOrganizationFormData(data: OrganizationFormData) {
            if (![undefined, null, ""].includes(data?.oName)) {
                formData.value.oName = data.oName;
            }

            if (![undefined, null, ""].includes(data?.oAddress)) {
                formData.value.oAddress = data.oAddress;
            }

            if (![undefined, null, "", "0", 0].includes(data?.oContact)) {
                formData.value.oContact = data.oContact;
            }

            if (![undefined, null, ""].includes(data?.oEmail)) {
                formData.value.oEmail = data.oEmail;
            }

            if (![undefined, null, ""].includes(data?.pId)) {
                formData.value.pId = data.pId;
            }

            if (![undefined, null, ""].includes(data?.pName)) {
                formData.value.pName = data.pName;
            }

            if (![undefined, null, "0", 0].includes(data?.pContact)) {
                formData.value.pContact = data.pContact;
            }

            if (![undefined, null, ""].includes(data?.pEmail)) {
                formData.value.pEmail = data.pEmail;
            }

            if (![undefined, null, ""].includes(data?.pPassword)) {
                formData.value.pPassword = data.pPassword;
            }
        }

        function setOrganizationFormDataErrors(errors: any) {
            formDataErrors.value.oName = errors?.oName;
            formDataErrors.value.oAddress = errors?.oAddress;
            formDataErrors.value.oContact = errors?.oContact;
            formDataErrors.value.oEmail = errors?.oEmail;
            formDataErrors.value.pName = errors?.pName;
            formDataErrors.value.pContact = errors?.pContact;
            formDataErrors.value.pEmail = errors?.pEmail;
            formDataErrors.value.pPassword = errors?.pPassword;
        }

        return {
            setOrganizationFormData,
            setOrganizationFormDataErrors,
            formData,
            formDataErrors,
        };
    }
);
