import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export const useAttendanceStore = defineStore("attendance", () => {
    const errors = ref({});

    function markStudentAttendance(input){
        return ApiService.post("/mark-student-attendance", input)
            .then(({ data }) => {
                return data;
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

    function setError(error: any) {
        errors.value = { ...error };
    }

    return {
        markStudentAttendance,
        errors
    }

});