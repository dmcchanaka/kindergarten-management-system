import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

import { useSettingsStore } from "./settings"; 

export interface User {
    token: string;
    userId: number;
    name: string;
    firstName: string;
    lastName: string;
    email: string;
    userAccessLevel: number;
    userRole: string;
    logo: string;
    initialLogin: boolean;
}

export interface Credentials {
    username: string;
    password: string;
}

export interface UserPermission {
    id: number,
    name: string
}

export interface UserMenu {
    heading: string;
    route: string;
    icon: string;
}

export const useAuthStore = defineStore("auth", () => {
    const settingsStore = useSettingsStore();
    const errors = ref({});
    const user = ref<User | null>(null);
    const isAuthenticated = ref(!!JwtService.getToken() || JwtService.getToken() !== 'undefined');
    const isPasswordReset = ref(!!user.value?.initialLogin);
    const organization = ref({});
    const userPermissions = ref<UserPermission[]>([]);
    const navBarList = ref<UserMenu[]>([]);
    const formDataErrors = ref({});

    function setAuth(authUser: User) {
        isAuthenticated.value = true;
        user.value = authUser;
        errors.value = {};
        JwtService.saveToken(user.value.token);
    }

    function setOrganization(org: any) {
        organization.value = { ...org };
    }

    function setUserPermission(myPermission: UserPermission[]){
        userPermissions.value = myPermission;
    }

    function setUserMenu(myMenuItem: UserMenu[]){
        navBarList.value = myMenuItem;
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
        userPermissions.value = [];
        navBarList.value = [];
    }

    function login(credentials: Credentials) {
        return ApiService.post("/login", credentials)
            .then(({ data }) => {
                setAuth(data.userInfo);
                setOrganization(data.organizationInfo);
                settingsStore.setUiSettings(data.settings);
                setUserPermission(data.userPermissions);
                setUserMenu(data.userMenu);
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
              setUserPermission(data.userPermissions);
              setUserMenu(data.userMenu);
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

    function resetPassword(userPassword: any){
        return ApiService.post("/password-reset", userPassword)
        .then(({ data }) => {
            errors.value = {};
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

    function forgotPassword(inputs: any) {
        return ApiService.post("/forgot", inputs)
        .then(({ data }) => {
            errors.value = {};
            return data;
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                let errorMsg = '';
                if (typeof response.data.errors === 'object') {
                    errorMsg = 'somethingWentWrong';
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

    function passwordReset(inputs: any) {
        return ApiService.post("/reset", inputs)
        .then(({ data }) => {
            errors.value = {};
            return data;
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                let errorMsg = '';
                if (typeof response.data.errors === 'object') {
                    errorMsg = 'somethingWentWrong';
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

    return {
        errors,
        user,
        isAuthenticated,
        login,
        verifyAuth,
        logout,
        organization,
        userPermissions,
        navBarList,
        resetPassword,
        isPasswordReset,
        forgotPassword,
        formDataErrors,
        passwordReset
    }
});