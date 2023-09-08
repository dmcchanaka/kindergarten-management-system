import { createRouter, createWebHistory, type RouteRecordRaw, } from "vue-router";

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
        children: [
            {
                path: "/dashboard",
                name: "dashboard",
                component: () => import("@/views/Dashboard.vue"),
                meta: {
                  pageTitle: "Dashboard",
                  breadcrumbs: ["Dashboards"],
                },
            },
            {
                path: "/user-roles",
                name: "user-roles",
                component: () => import("@/views/user-roles/ViewRoles.vue"),
                meta: {
                  pageTitle: "User Roles",
                  breadcrumbs: ["User Roles"],
                },
            },
            {
                path: "/organizations",
                name: "organizations",
                component: () => import("@/views/organizations/KGMS_OrganizationsList.vue"),
                meta: {
                  pageTitle: "Organizations",
                  breadcrumbs: ["Organizations"],
                },
            },
            {
                path: "/organization/create",
                name: "create-organization",
                component: () => import("@/views/organizations/KGMS_OrganizationCreate.vue"),
                meta: {
                  pageTitle: "Create Organization",
                  breadcrumbs: ["Create Organization"],
                },
            },
            {
                path: "/organization/edit/:id",
                name: "edit-organization",
                component: () => import("@/views/organizations/KGMS_OrganizationEdit.vue"),
                meta: {
                  pageTitle: "Edit Organization",
                  breadcrumbs: ["Edit Organization"],
                },
            },
        ]
    }
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;

