import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface ClassRoomForm {
    name: string;
    phone_number: string;
    email: string;
    teachers: any[];
}

export interface Teacher {
    id: number;
    description: string;
}

export const useClassRoomStore = defineStore("classRoom", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const teachersList = ref<Teacher[]>([]);

    //Fetch permission list
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
        console.log(classRoomForm);
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
                // console.log(typeof response.data.errors);
                
                // if (response.status !== 200) {
                //     let errorMsg = '';
                //     if (typeof response.data.errors === 'object') {
                //         errorMsg = 'Some fields are missing';
                //     } else {
                //         errorMsg = response.data.errors;
                //     }
                //     const error = {
                //         message : errorMsg,
                //         status : response.status,
                //     }
                //     setError(error);
                //     setFormDataErrors(response.data.errors);
                // }
            });
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    return {
        fetchTeachers,
        teachersList,
        classRoomRegistration,
        errors,
        formDataErrors,
    }
});