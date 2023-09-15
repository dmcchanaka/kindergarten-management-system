import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";
import JwtService from "@/core/services/JwtService";

export interface Principal {
    name: string,
    contact_no: string,
    email: string,
    password: string
}

export interface PrincipalDropDownList {
    id: number,
    name: string
}


export const usePrincipalsStore = defineStore("Principal", () => {

    const errors = ref({});
    const principalsList = ref<Principal[]>([]);
    const principalDropDownList = ref<PrincipalDropDownList[]>([]);


    async function fetchPrincipalsForDropDown() {
        return await ApiService.get("/role-user/principal")
        .then(({data}) => {
          //console.log(data.users);
          setPrincipalDropDown(data.users);
        })
        .catch(({ response }) => {
            console.log(response);
            if (response.status !== 200) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setPrincipals(principals: Principal[]) {
        principalsList.value.splice(0, principalsList.value.length, ...principals);
        errors.value = {}
    }

    function setPrincipalDropDown(principalsDataSet: PrincipalDropDownList[]) {
        principalDropDownList.value.splice(0, principalDropDownList.value.length, ...principalsDataSet);
        errors.value = {}
    }

    function getPrincipal(inddex: number){
        return principalsList.value[0];
    }

    function setError(error: any) {
        errors.value = { ...error };
    }


    return {
        fetchPrincipalsForDropDown,
        setPrincipals,
        getPrincipal,
        principalsList,
        errors,
        principalDropDownList
    }

});