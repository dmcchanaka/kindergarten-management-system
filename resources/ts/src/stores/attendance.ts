import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Attendance {
    id: string,
    date: string,
    time: Organization;
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

export const useAttendanceStore = defineStore("attendance", () => {
    const errors = ref({});
    const studentAttendanceList = ref<Attendance[]>([]);

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

    function fetchAttendanceList() {
        return ApiService.post("/attendance-list", {})
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

    return {
        markStudentAttendance,
        errors,
        studentAttendanceList,
        fetchAttendanceList,
        approveStudentAttendance
    }

});