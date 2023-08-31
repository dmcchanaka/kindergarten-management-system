import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface UserRole {
    id: string;
    description: string;
}

export const useUserRoleStore = defineStore("userRole", () => {

    const errors = ref({});
    const userRole = ref<UserRole | null>(null);

    function fetchUserRoles() {
        return ApiService.get("/login")
            .then(({ data }) => {
                console.log(data);
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

    function setError(error: any) {
        errors.value = { ...error };
    }
});