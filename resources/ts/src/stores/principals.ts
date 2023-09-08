import { ref } from "vue";
import { defineStore } from "pinia";

export interface Principal {
    name: string,
    contact_no: string,
    email: string,
    password: string
}


export const usePrincipalsStore = defineStore("Principal", () => {

    const errors = ref({});
    const PrincipalsList = ref<Principal[]>([]);


    function fetchPrincipals() {
        // API reques to backend
    }

    function setPrincipals(principals: Principal[]) {
        PrincipalsList.value.splice(0, PrincipalsList.value.length, ...principals);
        errors.value = {}
    }

    function getPrincipal(inddex: number){
        return PrincipalsList.value[0];
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    return {
        fetchPrincipals,
        setPrincipals,
        getPrincipal,
        PrincipalsList,
        errors
    }

});