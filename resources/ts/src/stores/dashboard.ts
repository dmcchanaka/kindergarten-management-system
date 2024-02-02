import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Overview {
    organizationsCount: Number,
    classRoomsCount: Number,
    teachersCount: Number,
    studentsCount: Number,
}

export const useDashboardStore = defineStore("dashboard", () => {
    const errors = ref({});
    const overview = ref<Overview>();

    //Fetch overview
    function fetchOverview() {
        return ApiService.post("/overview", {})
            .then(({ data }) => {
                setOverview(data.overview);
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function setOverview(newOverview: Overview) {
        overview.value = newOverview;
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    return {
        fetchOverview,
        errors,
        overview
    }

});