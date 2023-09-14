export interface MenuItem {
    heading?: string;
    sectionTitle?: string;
    route?: string;
    pages?: Array<MenuItem>;
    icon?: string;
    bootstrapIcon?: string;
    sub?: Array<MenuItem>;
}

const MainMenuConfig: Array<MenuItem> = [
    {
        heading: "dashboard",
        route: "/dashboard",
        icon: "home",
    },
    {
        heading: "user-roles",
        route: "/user-roles",
        icon: "arrows-alt",
      },
      {
        heading: "users",
        route: "/users",
        icon: "user",
      },
      {
        heading: "settings",
        route: "/settings",
        icon: "wrench",
      }
];
export default MainMenuConfig;