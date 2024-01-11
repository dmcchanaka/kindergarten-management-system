import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface ClassRoom {
    id: number;
    description: string;
}

export interface Parent {
    value: string;
    label: string;
}

export interface StudentForm {
    first_name: string,
    last_name: string,
    date_of_birth: string,
    age: string,
    gender: string,
    address: string,
    special_notice: string,
    org_id: string,
    class_room_id: string,
    parent_id: string,
}

export interface Student {
    id: string,
    first_name: string,
    last_name: string,
    date_of_birth: string,
    age: string,
    gender: string,
    address: string,
    special_notice: string,
    organization: Organization;
    class_room: ClassRoom;
    guardian: Guardian;
}

export interface Organization {
    id: number;
    description: string;
}

export interface Guardian {
    id: number;
    description: string;
}

export const useStudentStore = defineStore("student", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const classRoomList = ref<ClassRoom[]>([]);
    const parentsList = ref<Parent[]>([]);
    const studentList = ref<Student[]>([]);
    const idStudent = ref(0);


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

    function lookupParents(input){
        return ApiService.post("/parents-lookup", input)
            .then(({ data }) => {
                setParents(data.parentList);
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

    function setParents(parent: Parent[]) {
        parentsList.value = parent;
        errors.value = {};
    }

    function studentRegistration(studentForm: StudentForm) {
        return ApiService.post("/student-registration", studentForm)
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                console.log(response);
                
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
                    console.log(error);
                    setError(error);
                    setFormDataErrors(response.data.errors);
                }
            });
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    function fetchStudentList() {
        return ApiService.post("/students-list", {})
            .then(({ data }) => {
                setStudents(data.students);
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

    function setStudents(student: Student[]) {
        studentList.value = student;
        errors.value = {};
    }

    function saveStudentId(studentId) {
        idStudent.value = studentId;
    }

    function studentModification(studentForm: StudentForm){
        return ApiService.post("/update-student", studentForm)
            .then(({ data }) => {
               return data;
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

    function removeStudent(input){
        return ApiService.post("/student-remove", input)
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

    function fetchAllStudentList() {
        return ApiService.post("/all-students-list", {})
            .then(({ data }) => {
                setStudents(data.students);
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

    return {
        lookupClassRooms,
        classRoomList,
        errors,
        lookupParents,
        parentsList,
        studentRegistration,
        formDataErrors,
        fetchStudentList,
        studentList,
        saveStudentId,
        idStudent,
        studentModification,
        removeStudent,
        fetchAllStudentList
    }
});