import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

import { useSettingsStore } from "./settings"; 

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
    const settingsStore = useSettingsStore();
    const errors = ref({});
    const user = ref<User | null>(null);
    const isAuthenticated = ref(!!JwtService.getToken() || JwtService.getToken() !== 'undefined');
    const organization = ref({});

    function setAuth(authUser: User) {
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = {};
        JwtService.saveToken(user.value.token);
    }

    function setOrganization(org: any) {
        organization.value = { ...org };
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function purgeAuth() {
        isAuthenticated.value = false;
        user.value = {} as User;
        errors.value = {};
        JwtService.destroyToken();
        organization.value = {};
        settingsStore.generalSettings = null;
    }

    function login(credentials: Credentials) {
        return ApiService.post("/login", credentials)
            .then(({ data }) => {
                setAuth(data.userInfo);
                setOrganization(data.organizationInfo);
                settingsStore.setUiSettings(data.settings);
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

    function verifyAuth() {
        if (JwtService.getToken()) {
          ApiService.setHeader();
          ApiService.post("/verify_token", { api_token: JwtService.getToken() })
            .then(({ data }) => {
              setAuth(data.userInfo);
              setOrganization(data.organizationInfo);
              settingsStore.setUiSettings(data.settings);
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
        logout,
        organization
    }
});