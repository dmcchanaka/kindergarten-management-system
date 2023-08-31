import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface UserRole {
    role_id: string;
    description: string;
}

export const useUserRoleStore = defineStore("userRole", () => {

    const errors = ref({});
    const userRoleList = ref<UserRole[]>([]);

    function fetchUserRoles() {
        return ApiService.post("/user-roles-list", {})
            .then(({ data }) => {
                setUserRoles(data.userRoles);
            })
            .catch(({ response }) => {
                console.log(response);
                if (response.status === 404) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function setUserRoles(roles: UserRole[]) {
        userRoleList.value = roles;
        errors.value = {};
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    return {
        fetchUserRoles,
        userRoleList,
        errors
    }
});