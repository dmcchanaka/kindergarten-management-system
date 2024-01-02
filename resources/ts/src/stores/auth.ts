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
    const organization = ref({});
    const userPermissions = ref<UserPermission[]>([]);
    const navBarList = ref<UserMenu[]>([]);

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

    return {
        errors,
        user,
        isAuthenticated,
        login,
        verifyAuth,
        logout,
        organization,
        userPermissions,
        navBarList
    }
});