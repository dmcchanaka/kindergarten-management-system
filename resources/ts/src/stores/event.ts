import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface EventForm {
    description: string,
    date: string,
    org_id: string,
    class_room_id: string,
}

export const useEventStore = defineStore("event", () => {
    const errors = ref({});
    const formDataErrors = ref({});

    function eventRegistration(formData: EventForm) {
        return ApiService.post("/event-registration", formData)
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    let errorMsg = '';
                    if (typeof response.data.errors === 'object') {
                        errorMsg = 'someFieldsAreMissing';
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

    function setError(error: any) {
        errors.value = { ...error };
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    return {
        errors,
        formDataErrors,
        eventRegistration,
    }

});