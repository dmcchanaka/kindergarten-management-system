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
                  pageTitle: "editUserRole",
                  breadcrumbs: ["userRoles", "editUserRole"],

                },
            },
            {
                path: "/users",
                name: "users",
                component: () => import("@/views/users/UsersList.vue"),
                meta: {
                  pageTitle: "users",
                  breadcrumbs: ["userList"],
                },
            },
            {
                path: "/add-user",
                name: "add-user",
                component: () => import("@/components/users/forms/AddUser.vue"),
                meta: {
                  pageTitle: "users",
                  breadcrumbs: ["users", "addUser"],
                },
            },
            {
              path: "/edit-user",
              name: "edit-user",
              component: () => import("@/components/users/forms/EditUser.vue"),
              meta: {
                pageTitle: "Users",
                breadcrumbs: ["users", "editUser"],
              },
          },
            {
                path: "/organizations",
                name: "organizations",
                component: () => import("@/views/organizations/OrganizationsList.vue"),
                meta: {
                  pageTitle: "organizations",
                  breadcrumbs: ["organizations"],
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
                  pageTitle: "editOrganization",
                  breadcrumbs: ["editOrganization"],
                }
            },
            {
                path: "/class-rooms",
                name: "class-rooms",
                component: () => import("@/views/class-rooms/ClassRoomList.vue"),
                meta: {
                  pageTitle: "classRooms",
                  breadcrumbs: ["classRoomsList"],
                },
            },
            {
                path: "/add-class-room",
                name: "add-class-room",
                component: () => import("@/components/class-rooms/forms/AddClassRoom.vue"),
                meta: {
                  pageTitle: "classRooms",
                  breadcrumbs: ["classRooms", "addClassRoom"],
                },
            },
            {
                path: "/edit-class-room",
                name: "edit-class-room",
                component: () => import("@/components/class-rooms/forms/EditClassRoom.vue"),
                meta: {
                  pageTitle: "classRooms",
                  breadcrumbs: ["classRooms", "editClassRoom"],
                },
            },
            {
                path: "/parents",
                name: "parents",
                component: () => import("@/views/parents/ParentsList.vue"),
                meta: {
                  pageTitle: "parents",
                  breadcrumbs: ["parentsList"],
                },
            },
            {
                path: "/add-parent",
                name: "add-parent",
                component: () => import("@/components/parents/forms/AddParent.vue"),
                meta: {
                  pageTitle: "parents",
                  breadcrumbs: ["parents", "addParent"],
                },
            },
            {
                path: "/edit-parent",
                name: "edit-parent",
                component: () => import("@/components/parents/forms/EditParent.vue"),
                meta: {
                  pageTitle: "parents",
                  breadcrumbs: ["parents", "editParent"],
                },
            },
            {
                path: "/students",
                name: "students",
                component: () => import("@/views/students/StudentsList.vue"),
                meta: {
                  pageTitle: "students",
                  breadcrumbs: ["studentsList"],
                },
            },
            {
                path: "/add-student",
                name: "add-student",
                component: () => import("@/components/students/forms/AddStudent.vue"),
                meta: {
                  pageTitle: "students",
                  breadcrumbs: ["students", "addStudent"],
                },
            },
            {
                path: "/edit-student",
                name: "edit-student",
                component: () => import("@/components/students/forms/EditStudent.vue"),
                meta: {
                  pageTitle: "students",
                  breadcrumbs: ["students", "editStudent"],
                },
            },
            {
              path: "/gallery",
              name: "gallery",
              component: () => import("@/views/gallery/GalleryList.vue"),
              meta: {
                pageTitle: "gallery",
                breadcrumbs: ["galleryList"],
              },
            },
            {
              path: "/add-gallery",
              name: "add-gallery",
              component: () => import("@/components/gallery/forms/AddGallery.vue"),
              meta: {
                pageTitle: "gallery",
                breadcrumbs: ["gallery", "addGallery"],
              },
            },
            {
              path: "/edit-gallery",
              name: "edit-gallery",
              component: () => import("@/components/gallery/forms/EditGallery.vue"),
              meta: {
                pageTitle: "gallery",
                breadcrumbs: ["gallery", "editGallery"],
              },
            },
            {
              path: "/news-feed",
              name: "news-feed",
              component: () => import("@/views/news-feed/NewsFeed.vue"),
              meta: {
                pageTitle: "classRoomActivities",
                breadcrumbs: ["newsFeed"],
              },
            },
            {
              path: "/news-feed-content",
              name: "news-feed-content",
              component: () => import("@/views/news-feed/NewsFeedDetails.vue"),
              meta: {
                pageTitle: "classRoomActivities",
                breadcrumbs: ["newsFeed"],
              },
            },
            {
              path: "/attendance",
              name: "attendance",
              component: () => import("@/views/attendance/AttendanceList.vue"),
              meta: {
                pageTitle: "attendance",
                breadcrumbs: ["attendanceList"],

              },
          },
          {
              path: "/chat",
              name: "chat",
              component: () => import("@/views/chats/Chat.vue"),
              meta: {
                pageTitle: "chat",
                breadcrumbs: ["chat"],

              },
          },
          {
              path: "/settings",
              name: "settings",
              component: () => import("@/views/settings/Settings.vue"),
              meta: {
                pageTitle: "generalSettings",
                breadcrumbs: ["settings"],

              },
          },
          {
            path: "/my-profile",
            name: "my-profile",
            component: () => import("@/views/profile/MyProfile.vue"),
            meta: {
              pageTitle: "myProfile",
              breadcrumbs: ["profile"],

            },
          },
          {
            path: "/calendar",
            name: "calendar",
            component: () => import("@/views/event-calendar/Calendar.vue"),
            meta: {
              pageTitle: "calendar",
              breadcrumbs: ["calendar"],

            },
          },
          {
            path: "/events",
            name: "events",
            component: () => import("@/views/event-calendar/EventsList.vue"),
            meta: {
              pageTitle: "events",
              breadcrumbs: ["events"],

            },
          },
          {
            path: "/add-event",
            name: "add-event",
            component: () => import("@/components/events/AddEvent.vue"),
            meta: {
              pageTitle: "events",
              breadcrumbs: ["events"],

            },
          },
          {
            path: "/edit-event",
            name: "edit-event",
            component: () => import("@/components/events/EditEvent.vue"),
            meta: {
              pageTitle: "events",
              breadcrumbs: ["events"],

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
      path: "/attendance-form",
      component: () => import("@/layouts/GuestLayout.vue"),
      children: [
        {
            path: "/attendance-form",
            name: "attendance-form",
            component: () =>
                import("@/views/attendance/Form.vue"),
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

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    // verify auth token before each page change
    await authStore.verifyAuth();

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

