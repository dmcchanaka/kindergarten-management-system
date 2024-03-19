import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Attendance {
    id: string,
    date: string,
    time: string;
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

export interface ClassRoom {
    id: number;
    description: string;
}

export interface OrganizationList {
    value: string;
    label: string;
}

export interface ClassRoomList {
    value: string;
    label: string;
}

export interface StudentList {
    value: string;
    label: string;
    image: string;
}

export interface AttendanceFilters {
    orgId: string;
    classRoomId: string;
    fromDate: string;
    toDate: string;
}

export const useAttendanceStore = defineStore("attendance", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const studentAttendanceList = ref<Attendance[]>([]);
    const organizationList = ref<OrganizationList[]>([]);
    const classRoomList = ref<ClassRoomList[]>([]);
    const studentList = ref<StudentList[]>([]);

    function markStudentAttendance(input){
        return ApiService.post("/mark-student-attendance", input)
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

    function setError(error: any) {
        errors.value = { ...error };
    }

    function fetchAttendanceList(inputs: AttendanceFilters) {
        return ApiService.post("/attendance-list", inputs)
            .then(({ data }) => {
                setAttendance(data.attendance);
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

    function setAttendance(attendance: Attendance[]) {
        studentAttendanceList.value = attendance;
        errors.value = {};
    }

    function approveStudentAttendance(input){
        return ApiService.post("/approve-student-attendance", input)
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

    function fetchOrganizationList() {
        return ApiService.post("/lookup-organization-list", {})
            .then(({ data }) => {
                setOrganization(data.organizations);
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

    function setOrganization(org: OrganizationList[]) {
        organizationList.value = org;
        errors.value = {};
    }

    function lookupClassRoomList(input) {
        return ApiService.post("/lookup-class-room-list", input)
            .then(({ data }) => {
                setClassRoom(data.classRooms);
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

    function setClassRoom(classRoom: ClassRoomList[]) {
        classRoomList.value = classRoom;
        errors.value = {};
    }

    function lookupClassRoomStudents(input) {
        return ApiService.post("/lookup-class-room-student-list", input)
            .then(({ data }) => {
                setStudents(data.students);
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

    function setStudents(student: StudentList[]) {
        studentList.value = student;
        errors.value = {};
    }

    function fetchStudentAttendanceList(){
        return ApiService.post("/fetch-student-attendance-list", {})
            .then(({ data }) => {
                console.log(data);
                setAttendance(data.attendance);
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

    function exportAttendanceList(inputs: AttendanceFilters) {
        return ApiService.post("/export-attendance-list", inputs)
            .then(({ data }) => {
                return data;
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
        markStudentAttendance,
        errors,
        studentAttendanceList,
        fetchAttendanceList,
        approveStudentAttendance,
        fetchOrganizationList,
        organizationList,
        lookupClassRoomList,
        classRoomList,
        lookupClassRoomStudents,
        studentList,
        formDataErrors,
        fetchStudentAttendanceList,
        exportAttendanceList
    }

});