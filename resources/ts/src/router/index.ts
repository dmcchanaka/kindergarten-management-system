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
                component: () => import("@/views/user-roles/ViewRoles.vue"),
                meta: {
                  pageTitle: "User Roles",
                  breadcrumbs: ["User Roles"],
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

