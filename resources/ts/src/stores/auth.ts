import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface User {
    token: string;
    userId: number;
    name: string;
    email: string;
    userAccessLevel: number;
    userRole: string;
}

export interface Credentials {
    username: string;
    password: string;
}

export const useAuthStore = defineStore("auth", () => {
    const errors = ref({});
    const user = ref<User | null>(null);
    const isAuthenticated = ref(!!JwtService.getToken() || JwtService.getToken() !== 'undefined');

    function setAuth(authUser: User) {
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = {};
        JwtService.saveToken(user.value.token);
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function purgeAuth() {
        isAuthenticated.value = false;
        user.value = {} as User;
        errors.value = {};
        JwtService.destroyToken();
    }

    function login(credentials: Credentials) {
        return ApiService.post("/login", credentials)
            .then(({ data }) => {
                setAuth(data.userInfo);
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

    function verifyAuth() {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("/verify_token", { api_token: JwtService.getToken() })
            .then(({ data }) => {
              setAuth(data.userInfo);
            })
            .catch(({ response }) => {
              setError(response.data.errors);
              purgeAuth();
            });
        } else {
          purgeAuth();
        }
    }

    function logout() {
        purgeAuth();
    }

    return {
        errors,
        user,
        isAuthenticated,
        login,
        verifyAuth,
        logout
    }
});