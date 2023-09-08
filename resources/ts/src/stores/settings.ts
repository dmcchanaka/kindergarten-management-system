import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface UiSettings {
    backgroundColor: string;
    headerColor: string;
    textColor: string;
}

export const useSettingsStore = defineStore("settings", () => {
    const errors = ref({});
    const settings = ref<UiSettings | null>(null);
    const backgroundColor =  ref(JwtService.getBackgroundColor());

    function setError(error: any) {
        errors.value = { ...error };
    }

    function saveUiSettings(settings: UiSettings) {
        setUiSettings(settings);
        // return ApiService.post("/save-ui-settings", settings)
        //     .then(({ data }) => {
        //         console.log(data);
        //     })
        //     .catch(({ response }) => {
        //         console.log(response);
        //         if (response.status === 404) {
        //             const error = {
        //                 message : response.data.errors,
        //                 status : response.status,
        //             }
        //             setError(error);
        //         }
        //     });
    }

    function setUiSettings(settings: UiSettings) {
        errors.value = {};
        console.log(settings);
        JwtService.saveBackgroundColor(settings.backgroundColor);
    }

    return {
        settings,
        errors,
        saveUiSettings,
        backgroundColor
    }
});