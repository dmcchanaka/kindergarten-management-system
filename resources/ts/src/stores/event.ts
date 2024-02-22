import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface EventForm {
    description: string,
    date: string,
    org_id: string,
    class_room_id: string,
}

export interface Event {
    id: string;
    description: string,
    event_date: string,
    class_room: ClassRoom,
    organization: Organization;
    added_date: string,
}

export interface ClassRoom {
    id: number;
    name: string;
}

export interface Organization {
    id: number;
    name: string;
}

export const useEventStore = defineStore("event", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const eventList = ref<Event[]>([]);
    const idEvent = ref(0);

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

    function fetchEventList() {
        return ApiService.post("/event-list", {})
        .then(({ data }) => {
            setEventList(data.eventList);
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

    function setEventList(event: Event[]){
        eventList.value = event;
    }

    function saveEventId(eventId){
        idEvent.value = eventId;
    }

    function eventModification(formData: FormData) {
        return ApiService.post("/event-update", formData)
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

    function removeEvent(input){
        return ApiService.post("/event-remove", input)
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

    return {
        errors,
        formDataErrors,
        eventRegistration,
        fetchEventList,
        eventList,
        saveEventId,
        idEvent,
        eventModification,
        removeEvent
    }

});