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
                  pageTitle: "dashboard",
                  breadcrumbs: ["dashboard"],
                },
            },
            {
                path: "/user-roles",
                name: "user-roles",
                component: () => import("@/views/user-roles/UserRoleList.vue"),
                meta: {
                  pageTitle: "userRoles",
                  breadcrumbs: ["userRoles"],
                },
            },
            {
                path: "/add-user-role",
                name: "add-user-role",
                component: () => import("@/components/user-roles/forms/AddUserRole.vue"),
                meta: {
                  pageTitle: "addUserRole",
                  breadcrumbs: ["userRoles", "addUserRole"],
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
                path: "/organizations",
                name: "organizations",
                component: () => import("@/views/organizations/OrganizationsList.vue"),
                meta: {
                  pageTitle: "Organizations",
                  breadcrumbs: ["Organizations"],
                },
            },
            {
                path: "/create-organization",
                name: "create-organization",
                component: () => import("@/components/organizations/forms/OrganizationCreate.vue"),
                meta: {
                  pageTitle: "Create Organization",
                  breadcrumbs: ["Create Organization"],
                },
            },
            {
                path: "/organization/edit",
                name: "edit-organization",
                component: () => import("@/components/organizations/forms/OrganizationEdit.vue"),
                meta: {
                  pageTitle: "Edit Organization",
                  breadcrumbs: ["Edit Organization"],
                }
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
    },
    {
        path: "/",
        component: () => import("@/layouts/SystemLayout.vue"),
        children: [
            {
                path: "/404",
                name: "404",
                component: () =>
                    import("@/views/auth/Error404.vue"),
                meta: {
                    pageTitle: "Error 404",
                },
            },
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        redirect: "/404",
    },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

router.beforeEach((to, from, next) => {
    console.log(from);
    console.log(to);
    const authStore = useAuthStore();

    // verify auth token before each page change
    authStore.verifyAuth();

    const requiredPermission = to.name;
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

