import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface Users {
    token: string;
    userId: number;
    name: string;
    email: string;
    userRoleId: number;
    userRole: string;
}

export interface UserForm {
    first_name: string,
    last_name: string,
    email: string,
    phone_number: string,
    address: string,
    u_tp_id: string,
    username: string,
    password: string,
}

export const useUserStore = defineStore("user", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const userList = ref<Users[]>([]);

    function setError(error: any) {
        errors.value = { ...error };
    }

    function fetchUserList() {
        return ApiService.post("/users-list", {})
            .then(({ data }) => {
                setUsers(data.userList);
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

    function setUsers(users: Users[]) {
        userList.value = users;
        errors.value = {};
    }

    function userRegistration(userForm: UserForm) {
        return ApiService.post("/user-registration", userForm)
            .then(({ data }) => {
               
            })
            .catch(({ response }) => {
                console.log(typeof response.data.errors);
                
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

    return {
        fetchUserList,
        errors,
        userList,
        userRegistration,
        formDataErrors
    }
});