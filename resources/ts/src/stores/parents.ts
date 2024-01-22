import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface ClassRoom {
    id: number;
    description: string;
}

export interface ParentForm {
    first_name: string,
    last_name: string,
    email: string,
    phone_number: string,
    address: string,
    username: string,
    password: string,
    password_confirmation: string;
}

export interface Parent {
    id: string,
    first_name: string,
    last_name: string,
    email: string,
    phone_number: string,
    address: string,
    username: string;
}

export const useParentStore = defineStore("parent", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const classRoomList = ref<ClassRoom[]>([]);
    const parentList = ref<Parent[]>([]);
    const idParent = ref(0);

    function lookupClassRooms(input){
        return ApiService.post("/class-room-list-associate-with-organization", input)
            .then(({ data }) => {
                setClassRoom(data.classRoomList);
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

    function setClassRoom(classes: ClassRoom[]) {
        classRoomList.value = classes;
        errors.value = {};
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function parentRegistration(parentForm: ParentForm) {
        return ApiService.post("/parent-registration", parentForm)
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

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    function fetchParentList() {
        return ApiService.post("/parents-list", {})
            .then(({ data }) => {
                setParents(data.parents);
            })
            .catch(({ response }) => {
                if (response.status === 404) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function setParents(users: Parent[]) {
        parentList.value = users;
        errors.value = {};
    }

    function saveParentId(parentId) {
        idParent.value = parentId;
    }

    function updateParentInfo(parentForm: ParentForm){
        return ApiService.post("/update-parent", parentForm)
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

    function removeParent(input){
        return ApiService.post("/parent-remove", input)
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
        lookupClassRooms,
        classRoomList,
        parentRegistration,
        errors,
        formDataErrors,
        fetchParentList,
        parentList,
        saveParentId,
        idParent,
        updateParentInfo,
        removeParent
    }
});