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
    }
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;

