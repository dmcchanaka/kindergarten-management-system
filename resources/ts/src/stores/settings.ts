import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface UiSettings {
    logo: string;
    backgroundColor: string;
    headerColor: string;
    textColor: string;
}

export interface FormLogo {
    image: string;
    organizationId: string;
    userId: string;
}

export interface FormSettings {
    organizationId: string;
    userId: string;
    backgroundColor: string;
    headerColor: string;
    textColor: string;
}

export interface SearchField {
    organizationId: string;
    userId: string;
}

export const useSettingsStore = defineStore("settings", () => {
    const errors = ref({});
    const generalSettings = ref<UiSettings | null>(null);
    const logo = ref("/media/logo/logo.png");
    const backgroundColor =  ref(JwtService.getBackgroundColor());

    function setError(error: any) {
        errors.value = { ...error };
    }

    function saveUiSettings(settingsForm: FormSettings) {
        // setUiSettings(settings);
        return ApiService.post("/save-ui-settings", settingsForm)
            .then(({ data }) => {
                setUiSettings(data.settings);
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

    function setUiSettings(allSettings: UiSettings) {
        errors.value = {};
        generalSettings.value = allSettings;
    }

    function fetchUiSettings(search: SearchField){
        return ApiService.post("/fetch-general-settings", search)
            .then(({ data }) => {
                console.log(data);
                setUiSettings(data.settings);
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

    function saveLogo(logo: FormLogo) {
        return ApiService.post("/save-logo", logo)
            .then(({ data }) => {
                console.log(data);
                setOrganizationLogo(data);
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

    function setOrganizationLogo(result: any) {
        errors.value = {};
        logo.value = result.logo_url;
    }

    return {
        generalSettings,
        errors,
        fetchUiSettings,
        saveUiSettings,
        backgroundColor,
        saveLogo,
        logo,
        setUiSettings
    }
});