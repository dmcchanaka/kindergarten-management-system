<template>
  <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
    <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
      <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
          <div class="flex flex-wrap mx-3">
              <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                  <h6 class="mb-0">Organization Informations</h6>
              </div>
              <div class="flex-none w-1/2 max-w-full px-3 text-right">
                  <router-link to="/organizations" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                      <fa icon="arrow-left" />
                      &nbsp;&nbsp;Back
                  </router-link>
              </div>
          </div>
      </div>
      <div class="flex flex-wrap mx-3 mb-3">
        <div class="w-full max-w-full px-3 mt-6 md:w-12/12 md:flex-none">
          <div class="relative flex flex-col min-w-0 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
            <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                <h6 class="mb-0">Basic Information</h6>
            </div>
            <div class="flex-auto p-4 pt-6">
              <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                  <div class="w-full">
                    <form>
                      <div class="grid gap-6 mb-6 md:grid-cols-1">
                        <div>
                            <label for="principal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Principal</label>
                            <Multiselect 
                                v-model="form.principal_id"
                                placeholder="Choose principal" 
                                :close-on-select="true"
                                :searchable="true" 
                                label="name"
                                valueProp="value"
                                :min-chars="3"
                                :options="principalList" />
                            <ErrorLable v-if="formErrors.principal_id" :error="formErrors.principal_id.toString()"></ErrorLable>
                        </div>
                        <div>
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input
                              v-model="form.name"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              type="text"
                              placeholder="Enter organization name"
                            />
                            <ErrorLable v-if="formErrors.name" :error="formErrors.name"></ErrorLable>
                        </div>
                        <div>
                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                            <input
                              v-model="form.address"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              type="text"
                              placeholder="Enter organization address"
                            />
                            <ErrorLable v-if="formErrors.address" :error="formErrors.address"></ErrorLable>
                        </div>
                        <div>
                            <label for="contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contact No</label>
                            <input
                              v-model="form.contact_num"
                              @input="filterPhoneNumber"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              type="text"
                              placeholder="Enter organization contact number"
                            />
                            <ErrorLable v-if="formErrors.contact_num" :error="formErrors.contact_num"></ErrorLable>
                        </div>
                        <div>
                            <label for="contact_no" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                            <input
                              v-model="form.email"
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                              type="text"
                              placeholder="Enter organization email"
                            />
                            <ErrorLable v-if="formErrors.email" :error="formErrors.email"></ErrorLable>
                        </div>
                      </div>
                      <button 
                        ref="submitButton" 
                        type="submit" 
                        @click.prevent="submitOrganizationFormData"
                        :disabled="form.loading"
                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                        <span v-if="!form.loading">Submit</span>
                        <span v-if="form.loading">
                        Please wait...
                        </span>
                      </button>
                    </form>
                  </div>
                </li>
              </ul>
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
import { useOrganizationsStore, type OrganizationFormData } from "@/stores/organizations";
import { usePrincipalsStore, type PrincipalDropDownList } from '@/stores/principals';
import { useRoute } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Multiselect from "@vueform/multiselect";
import ErrorLable from "@/components/global/ErrorLabel.vue";

export default defineComponent({
  name: "organization-create",
  components: {
    ArrowLeftIcon,
    ErrorLable,
    Multiselect
  },
  setup(props, { emit }) {
    const route = useRoute();

    const store = useOrganizationsStore();
    const pStore = usePrincipalsStore();
    const principalList = ref<Array<PrincipalDropDownList>>([]);
    const submitButton = ref<HTMLButtonElement | null>(null);

    const formErrors = ref<OrganizationFormData>({
      name: '',
      address: '',
      contact_num: '',
      email: '',
      principal_id: NaN,
    });
    

    const form = reactive({
      principal_id: NaN,
      name: "",
      address: "",
      contact_num: "",
      email: "",
      loading: false
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
      form.loading = true;
      if (submitButton.value) { 
          submitButton.value!.disabled = true;
      }
      let response = await store.saveOrganization(form);
      const error = Object.values(store.errors);
      formErrors.value = Object(store.formDataErrors);
      if (error.length === 0) {
        Swal.fire({
            title: 'Good job!',
            text: response.message,
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok, got it!'
        }).then(() => {
            form.principal_id = NaN;
            form.name = "";
            form.address = "";
            form.contact_num = "";
            form.email = "";
        });
      } else {
        Swal.fire({
            title: 'Oops...',
            text: error[0] as string,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Try again!'
        }).then((result) => {
            store.errors = {};
            store.formDataErrors = {};
        });
      }
      submitButton.value!.disabled = false;
      form.loading = false;
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
      ArrowLeftIcon,
      ErrorLable,
      submitOrganizationFormData,
      principalList,
      Multiselect,
      formErrors,
      submitButton
    };
  },
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>