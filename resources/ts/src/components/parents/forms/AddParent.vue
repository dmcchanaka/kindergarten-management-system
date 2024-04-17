<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <!-- <h6 class="mb-0 text-header">{{ translate('parents') }}</h6> -->
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/parents" class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-12/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0 text-sub-header">{{ translate('basicInformation') }}</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div>
                                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("firstName") }}</label>
                                                    <input type="text" v-model="parentForm.firstName" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('firstName')" required>
                                                    <ErrorLabel v-if="formErrors.first_name" :error="formErrors.first_name"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("lastName") }}</label>
                                                    <input type="text" v-model="parentForm.lastName" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('lastName')" required>
                                                    <ErrorLabel v-if="formErrors.last_name" :error="formErrors.last_name"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("email") }}</label>
                                                    <input type="email" v-model="parentForm.email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('email')" required>
                                                    <ErrorLabel v-if="formErrors.email" :error="formErrors.email"></ErrorLabel>
                                                </div>  
                                                <div>
                                                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("phoneNumber") }}</label>
                                                    <input type="tel" v-model="parentForm.phoneNumber" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('phoneNumber')" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                                                    <ErrorLabel v-if="formErrors.phone_number" :error="formErrors.phone_number"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("address") }}</label>
                                                    <textarea id="address" v-model="parentForm.address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('address')" required></textarea>
                                                    <ErrorLabel v-if="formErrors.address" :error="formErrors.address"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("username") }}</label>
                                                    <input type="text" v-model="parentForm.userName" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('username')" required>
                                                    <ErrorLabel v-if="formErrors.username" :error="formErrors.username"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("password") }}</label>
                                                    <input type="password" v-model="parentForm.password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required>
                                                    <ErrorLabel v-if="formErrors.password" :error="formErrors.password"></ErrorLabel>
                                                </div> 
                                                <div>
                                                    <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{translate("confirmPassword") }}</label>
                                                    <input type="password" v-model="parentForm.passwordConfirm" id="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required>
                                                </div>
                                            </div>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitUser"
                                                :disabled="parentForm.loading"
                                                class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                                <span v-if="!parentForm.loading">{{translate("submit") }}</span>
                                                <span v-if="parentForm.loading">
                                                    {{translate("pleaseWait") }}...
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
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useParentStore, type ClassRoom, ParentForm } from "@/stores/parents";

import Multiselect from '@vueform/multiselect';
import ErrorLabel from "@/components/global/ErrorLabel.vue";
export default defineComponent({
    name: "add-parent",
    components: {
        Multiselect,
        ErrorLabel
    },
    setup(){
        const store = useParentStore();
        const classRoomStore = useClassRoomStore();
        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);

        const parentForm = ref({
            firstName: "",
            lastName: "",
            email: "",
            phoneNumber: "",
            address: "",
            userName: "",
            password: "",
            passwordConfirm: "",
            loading: false
        });

        const formErrors = ref<ParentForm>({
            first_name: "",
            last_name: "",
            email: "",
            phone_number: "",
            address: "",
            username: "",
            password: "",
            password_confirmation: "",
        });

        const submitUser = async() => {
            const inputs = {
                first_name: parentForm.value.firstName,
                last_name: parentForm.value.lastName,
                email: parentForm.value.email,
                phone_number: parentForm.value.phoneNumber,
                address: parentForm.value.address,
                username: parentForm.value.userName,
                password: parentForm.value.password,
                password_confirmation: parentForm.value.passwordConfirm,
            };
            parentForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            let response = await store.parentRegistration(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                    Swal.fire({
                        title: translate('goodJob') + '!',
                        text: translate(response.message),
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: translate('okGotIt') + '!'
                    }).then(() => {
                        parentForm.value.firstName = "";
                        parentForm.value.lastName = "";
                        parentForm.value.email = "";
                        parentForm.value.phoneNumber = "";
                        parentForm.value.address = "";
                        parentForm.value.userName = "";
                        parentForm.value.password = "";
                        parentForm.value.passwordConfirm = "";
                        parentForm.value.loading = false;
                    });
                } else {
                    Swal.fire({
                        title: translate('opps') + '...',
                        text: translate(error[0] as string),
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: translate('tryAgain') + '!'
                    }).then((result) => {
                        store.errors = {};
                        store.formDataErrors = {};
                    })
                }
                submitButton.value!.disabled = false;
                parentForm.value.loading = false;
        }

        return {
            translate,
            parentForm,
            submitButton,
            submitUser,
            formErrors
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>