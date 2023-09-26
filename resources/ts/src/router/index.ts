import { createRouter, createWebHistory, type RouteRecordRaw, } from "vue-router";
import { useAuthStore } from "@/stores/auth";

const routes: Array<RouteRecordRaw> = [
    {
        path: "/",
        component: () => import("@/layouts/AuthLayout.vue"),
        children: [
            {
                path: "/sign-in",
                name: "sign-in",
                component: () =>
                    import("@/views/auth/SignIn.vue"),
                meta: {
                    pageTitle: "Sign In",
                },
            },
        ]
    },
    {
        path: "/",
        redirect: "/dashboard",
        component: () => import("@/layouts/main-layout/MainLayout.vue"),
        meta: {
            middleware: "auth",
        },
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/views/Dashboard.vue"),
                meta: {
                  pageTitle: "Dashboard",
                  breadcrumbs: ["Dashboard"],
                },
            },
            {
                path: "/user-roles",
                name: "user-roles",
                component: () => import("@/views/user-roles/UserRoleList.vue"),
                meta: {
                  pageTitle: "User Roles",
                  breadcrumbs: ["User Roles"],
                },
            },
            {
                path: "/organizations",
                name: "organizations",
                component: () => import("@/views/organizations/OrganizationsList.vue"),
                meta: {
                  pageTitle: "Organizations",
                  breadcrumbs: ["Organizations"],
                },
            },
            {
                path: "/organization/create",
                name: "create-organization",
                component: () => import("@/views/organizations/OrganizationCreate.vue"),
                meta: {
                  pageTitle: "Create Organization",
                  breadcrumbs: ["Create Organization"],
                },
            },
            {
                path: "/organization/edit",
                name: "edit-organization",
                component: () => import("@/views/organizations/OrganizationEdit.vue"),
                meta: {
                  pageTitle: "Edit Organization",
                  breadcrumbs: ["Edit Organization"],
                }
            },
            {
                path: "/add-user-role",
                name: "add-user-role",
                component: () => import("@/components/user-roles/forms/AddUserRole.vue"),
                meta: {
                  pageTitle: "Add User Role",
                  breadcrumbs: ["User Roles", "Add User Role"],
                },
            },
            {
                path: "/edit-user-role",
                name: "edit-user-role",
                component: () => import("@/components/user-roles/forms/EditUserRole.vue"),
                meta: {
                  pageTitle: "Edit User Role",
                  breadcrumbs: ["User Roles", "Edit User Role"],

                },
            },
            {
                path: "/users",
                name: "users",
                component: () => import("@/views/users/UsersList.vue"),
                meta: {
                  pageTitle: "Users",
                  breadcrumbs: ["User List"],
                },
            },
            {
                path: "/add-user",
                name: "add-user",
                component: () => import("@/components/users/forms/AddUser.vue"),
                meta: {
                  pageTitle: "Users",
                  breadcrumbs: ["Users", "Add User"],
                },
            },
            {
                path: "/settings",
                name: "settings",
                component: () => import("@/views/settings/Settings.vue"),
                meta: {
                  pageTitle: "General Settings",
                  breadcrumbs: ["Settings"],

                },
            },
        ]
    }
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore();

    // verify auth token before each page change
    authStore.verifyAuth();

     // before page access check if page requires authentication
    if (to.meta.middleware == "auth") {
        if (authStore.isAuthenticated) {
        next();
        } else {
        next({ name: "sign-in" });
        }
    } else {
        next();
    }
});

export default router;

