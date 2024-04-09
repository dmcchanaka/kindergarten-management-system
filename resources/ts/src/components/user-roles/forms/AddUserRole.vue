<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div
            class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0 text-header">{{ translate('userRoleAndPermissions') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/user-roles"
                            class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div
                        class="relative flex flex-col min-w-0 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <h6 class="mb-0 text-sub-header">{{ translate('basicInformation') }}</h6>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="mb-6">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('userRole') }}</label>
                                                <input type="text"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                                    :placeholder="translate('enterUserRole')" v-model="userRoleForm.userRole" required>
                                            </div>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitUserRole"
                                                :disabled="userRoleForm.loading"
                                                class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                                <span v-if="!userRoleForm.loading">{{ translate('submit') }}</span>
                                                <span v-if="userRoleForm.loading">
                                                {{ translate('pleaseWait') }}...
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div
                        class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0 text-sub-header">{{ translate('permissionInformation') }}</h6>
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
                                            <div class="mb-6">
                                                <label
                                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('permissions') }}</label>
                                                <Multiselect 
                                                    v-model="userRoleForm.selectedPermissions"
                                                    :placeholder="translate('choosePermissions')" 
                                                    mode="tags" 
                                                    :close-on-select="false"
                                                    :searchable="true" 
                                                    :create-option="true" 
                                                    :options="permissionsList" />
                                            </div>
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
import { defineComponent, onMounted, ref } from "vue";
import { useUserRoleStore, type UserRole, Permission, SaveUserRole } from "@/stores/userRole";

import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";

import Multiselect from '@vueform/multiselect'

export default defineComponent({
    name: "add-user-role",
    props: {
    },
    components: {
        ArrowLeftIcon,
        Multiselect
    },
    setup() {
        const store = useUserRoleStore();
        const { t, te } = useI18n();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };
        
        const submitButton = ref<HTMLButtonElement | null>(null);
        const permissionsList = ref<Array<Permission>>([]);

        const userRoleForm = ref({
            userRole: "",
            selectedPermissions: [],
            loading: false
        });

        onMounted(async () => {
            await fetchPermissionList();
        });

        const fetchPermissionList = async () => {
            await store.fetchPermissions();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                permissionsList.value.splice(0, permissionsList.value.length, ...store.permissionsList);
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    store.errors = {};
                })
            }
        }

        const submitUserRole = async() => {

            if(userRoleForm.value.userRole == ''){
                Swal.fire({
                    title: translate('opps') + '...',
                    text: translate('pleaseEnterUserRoleName'),
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                })
            } else if(userRoleForm.value.selectedPermissions.length === 0){
                Swal.fire({
                    title: translate('opps') + '...',
                    text: translate('pleaseSelectAtleastOnePermission'),
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                })
            } else {
                userRoleForm.value.loading = true;
                if (submitButton.value) { 
                    submitButton.value!.disabled = true;
                }
                const inputs = {
                    userRole: userRoleForm.value.userRole, // Use the appropriate property
                    permissions: userRoleForm.value.selectedPermissions,
                };
                await store.saveUserRole(inputs);
                const error = Object.values(store.errors);
                if (error.length === 0) {
                    Swal.fire({
                        title: translate('goodJob') + '!',
                        text: translate('recordHasBeenSuccesfullyAdded'),
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: translate('okGotIt') + '!'
                    }).then(() => {
                        userRoleForm.value.selectedPermissions = [];
                        userRoleForm.value.userRole = "";
                    });
                } else {
                    Swal.fire({
                        title: translate('opps') + '...',
                        text: error[0] as string,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: translate('tryAgain') + '!'
                    }).then((result) => {
                        store.errors = {};
                    })
                }
                submitButton.value!.disabled = false;
                userRoleForm.value.loading = false;
            } 
        }

        return {
            permissionsList,
            userRoleForm,
            submitUserRole,
            submitButton,
            translate
        }
    },
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>