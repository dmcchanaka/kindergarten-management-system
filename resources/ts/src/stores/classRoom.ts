import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface ClassRoomForm {
    org_id: number | null;
    name: string;
    phone_number: string;
    email: string;
    teachers: any[];
}

export interface Teacher {
    id: number;
    description: string;
}

export interface Organization {
    id: number;
    name: string;
}

export interface ClassRoom {
    id: number;
    name: string;
    phone_number: string;
    email: string;
    created_at: string;
    teachers: Teacher[];
    organization: Organization;
}

export const useClassRoomStore = defineStore("classRoom", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const organizationsList = ref<Organization[]>([]);
    const teachersList = ref<Teacher[]>([]);
    const classRoomList = ref<ClassRoom[]>([]);
    const idClassRoom = ref(0);

    //Fetch organization list
    function fetchOrganizations() {
        return ApiService.post("/organization-list", {})
            .then(({ data }) => {
                setOrganizations(data.organizations);
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

    function setOrganizations(organizations: Organization[]) {
        organizationsList.value = organizations;
        errors.value = {};
    }

    //Fetch teachers list
    function fetchTeachers() {
        return ApiService.post("/teachers-list", {})
            .then(({ data }) => {
                setTeachers(data.teachers);
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

    function setTeachers(teachers: Teacher[]) {
        teachersList.value = teachers;
        errors.value = {};
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function classRoomRegistration(classRoomForm: ClassRoomForm) {
        return ApiService.post("/class-room-registration", classRoomForm)
            .then(({ data }) => {
               
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

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    function fetchClassRoomList(){
        return ApiService.post("/class-room-list", {})
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

    function saveClassRoomId(classRoomId) {
        idClassRoom.value = classRoomId;
    }

    function classRoomModification(classRoomForm: ClassRoomForm){
        return ApiService.post("/class-room-update", classRoomForm)
        .then(({ data }) => {
           
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

    function removeClassRoom(input){
        return ApiService.post("/class-room-remove", input)
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

    return {
        fetchOrganizations,
        fetchTeachers,
        teachersList,
        organizationsList,
        classRoomRegistration,
        errors,
        formDataErrors,
        classRoomList,
        fetchClassRoomList,
        saveClassRoomId,
        idClassRoom,
        classRoomModification,
        removeClassRoom
    }
});