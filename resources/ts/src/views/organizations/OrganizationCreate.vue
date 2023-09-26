<template>
  <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
    <div
      class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border"
    >
      <div
        class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent"
      >
        <div class="flex flex-wrap mx-3">
          <div class="flex items-center flex-none w-1/2 max-w-full px-3">
            <h6 class="mb-0">Organization Details</h6>
          </div>
          <div class="flex-none w-1/2 max-w-full px-3 text-right">
            <router-link
              to="/organizations"
              class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"
            >
              <ArrowLeftIcon
                class="w-5 h-5 text-white-600 hover:text-gray-500 hover:scale-110"
              />
              &nbsp;&nbsp;Back
            </router-link>
          </div>
        </div>

        <div class="flex flex-wrap mx-3 mb-3">
          <div class="w-full max-w-full px-3 mt-6">
            <div
              class="relative flex flex-col min-w-0 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border"
            >
              <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-0">Organization Details</h6>
              </div>
              <div class="flex-auto p-4 pt-6">
                <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                  <li
                    class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50"
                  >
                    <div class="w-full">
                      <div class="w-full max-w-full px-3 mt-6 md:flex-none">
                        <div class="mt-10 mb-10">
                          <div class="flex flex-col pb-10">

                            <div class="mb-6 md:flex md:items-center">
                              <div class="md:w-1/6">
                                <label
                                  class="block pr-4 mb-1 text-sm font-bold text-gray-900 dark:text-white md:text-right md:mb-0"
                                >
                                  Principal
                                </label>
                              </div>
                              <div class="md:w-2/3">
                                <KGMS_ErrorLable
                                  :errors="errors.principal_id"
                                ></KGMS_ErrorLable>
                                <Multiselect 
                                  v-model="form.principal_id"
                                  placeholder="Choose principal" 
                                  :close-on-select="true"
                                  :searchable="true" 
                                  label="name"
                                  valueProp="value"
                                  :min-chars="3"
                                  :options="principalList" />
                              </div>
                            </div>


                            <div class="mb-6 md:flex md:items-center">
                              <div class="md:w-1/6">
                                <label
                                  class="block pr-4 mb-1 text-sm font-bold text-gray-900 dark:text-white md:text-right md:mb-0"
                                >
                                  Name
                                </label>
                              </div>
                              <div class="md:w-2/3">
                                <KGMS_ErrorLable
                                  :errors="errors.name"
                                ></KGMS_ErrorLable>
                                <input
                                  v-model="form.name"
                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  type="text"
                                  placeholder="Enter organization name"
                                />
                              </div>
                            </div>

                            <div class="mb-6 md:flex md:items-center">
                              <div class="md:w-1/6">
                                <label
                                  class="block pr-4 mb-1 text-sm font-bold text-gray-900 dark:text-white md:text-right md:mb-0"
                                >
                                  Address
                                </label>
                              </div>
                              <div class="md:w-2/3">
                                <KGMS_ErrorLable
                                  :errors="errors.address"
                                ></KGMS_ErrorLable>
                                <input
                                  v-model="form.address"
                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  type="text"
                                  placeholder="Enter organization address"
                                />
                              </div>
                            </div>

                            <div class="mb-6 md:flex md:items-center">
                              <div class="md:w-1/6">
                                <label
                                  class="block pr-4 mb-1 text-sm font-bold text-gray-900 dark:text-white md:text-right md:mb-0"
                                >
                                  Contact No
                                </label>
                              </div>
                              <div class="md:w-2/3">
                                <KGMS_ErrorLable
                                  :errors="errors.contact_num"
                                ></KGMS_ErrorLable>
                                <input
                                  v-model="form.contact_num"
                                  @input="filterPhoneNumber"
                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  type="text"
                                  placeholder="Enter organization contact number"
                                />
                              </div>
                            </div>

                            <div class="mb-6 md:flex md:items-center">
                              <div class="md:w-1/6">
                                <label
                                  class="block pr-4 mb-1 text-sm font-bold text-gray-900 dark:text-white md:text-right md:mb-0"
                                >
                                  Email
                                </label>
                              </div>
                              <div class="md:w-2/3">
                                <KGMS_ErrorLable
                                  :errors="errors.email"
                                ></KGMS_ErrorLable>
                                <input
                                  v-model="form.email"
                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                  type="text"
                                  placeholder="Enter organization contact number"
                                />
                              </div>
                            </div>
                          </div>
                          <button
                            @click="submitOrganizationFormData"
                            class="inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25"
                          >
                            Submit
                          </button>
                        </div>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, reactive, onMounted, watch, ref } from "vue";
import { ArrowLeftIcon } from "@heroicons/vue/24/solid";
import { useOrganizationsStore } from "@/stores/organizations";
import { usePrincipalsStore, type PrincipalDropDownList } from '@/stores/principals';
import { useRoute } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Multiselect from "@vueform/multiselect";
import KGMS_ErrorLable from "@/components/global/ErrorLabel.vue";

export default defineComponent({
  name: "KGMS_OrganizationCreate",
  components: {
    ArrowLeftIcon,
    KGMS_ErrorLable,
    Multiselect
  },
  setup(props, { emit }) {
    const route = useRoute();

    const store = useOrganizationsStore();

    const pStore = usePrincipalsStore();

    const errors = store.formDataErrors;

    const principalList = ref<Array<PrincipalDropDownList>>([]);

    const form = reactive({
      principal_id: NaN,
      name: "",
      address: "",
      contact_num: "",
      email: "",
    });

    watch(form,(nVal, OVal) => {
      console.log(nVal);
    });

    const filterPhoneNumber = () => {
      form.contact_num = form.contact_num.replace(/\D/g, "");

      if (form.contact_num.length > 25) {
        form.contact_num = form.contact_num.substring(0, 25);
      }
      if (form.contact_num.length < 10) {
        form.contact_num = form.contact_num.substring(0, 10);
      }
    };

    const submitOrganizationFormData = async () => {
      
      let response = await store.saveOrganization(form);
      console.log(response);
      if (response.status !== 200) {
          Swal.fire({
            title: "Oops...",
            text: response?.data?.message,
            icon: "error",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "Try again!",
          });

      }else{
        Swal.fire({
          title: "Success",
          text: response.message,
          icon: "success",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "Close",
        });
      
        form.principal_id = NaN;
        form.name = "";
        form.address = "";
        form.contact_num = "";
        form.email = "";
      }
    
    };

    onMounted(async () => {

      await fetchPrincipals();

    });

    const fetchPrincipals = async () => {
      await pStore.fetchPrincipalsForDropDown();
      principalList.value.splice(0, principalList.value.length, ...pStore.principalDropDownList);
      console.log(pStore.principalDropDownList);
    }

    return {
      filterPhoneNumber,
      form,
      errors,
      ArrowLeftIcon,
      KGMS_ErrorLable,
      submitOrganizationFormData,
      principalList,
      Multiselect
    };
  },
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>