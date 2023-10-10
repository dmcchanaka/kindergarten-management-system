import { createI18n } from "vue-i18n";

const messages = {
    en: {
        home: "Home",
        dashboard: "Dashboard",
        userRoles: "User Roles",
        addUserRole: "Add User Role",
        userRoleList: "User Role List",
        description: "Description",
        actions: "Actions",
    },
    fr: {
        home: "Maison",
        dashboard: "Générateur de mise",
        userRoles: "Rôles des utilisateurs",
        addUserRole: "Ajouter un rôle d'utilisateur",
        userRoleList: "Liste des rôles utilisateur",
        description: "Description",
        actions: "Actions",
    },
    de: {
        home: "Heim",
        dashboard: "Armaturenbrett",
        userRoles: "Benutzerregeln",
        addUserRole: "Benutzerrolle hinzufügen",
        userRoleList: "Liste der Benutzerrollen",
        description: "Beschreibung",
        actions: "Aktionen",
    }
};

const i18n = createI18n({
    legacy: false,
    locale: "fr",
    globalInjection: true,
    messages,
});
  
export default i18n;