import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface UserRole {
    role_id: string;
    description: string;
    permissions: Permission[];
}

export interface Permission {
    id: number;
    name: string;
}

export interface SaveUserRole {
    userRole: string;
    permissions: any[];
}

export const useUserRoleStore = defineStore("userRole", () => {

    const errors = ref({});
    const userRoleList = ref<UserRole[]>([]);
    const permissionsList = ref<Permission[]>([]);

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

    //Fetch permission list
    function fetchPermissions() {
        return ApiService.post("/permission-list", {})
            .then(({ data }) => {
                console.log(data);
                setPermissions(data.permissions);
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

    function setPermissions(permissions: Permission[]) {
        permissionsList.value = permissions;
        errors.value = {};
    }

    function saveUserRole(saveUserRole: SaveUserRole) {
        return ApiService.post("/user-role-save", saveUserRole)
            .then(({ data }) => {
                // setAuth(data.userInfo);
                console.log(data);
            })
            .catch(({ response }) => {
                if (response.status === 404 || response.status === 500) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    return {
        fetchUserRoles,
        userRoleList,
        errors,
        fetchPermissions,
        permissionsList,
        saveUserRole,
    }
});