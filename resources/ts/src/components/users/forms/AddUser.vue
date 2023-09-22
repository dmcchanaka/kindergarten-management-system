<template>
<div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
    <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
        <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
            <div class="flex flex-wrap mx-3">
                <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                    <h6 class="mb-0">User Informations</h6>
                </div>
                <div class="flex-none w-1/2 max-w-full px-3 text-right">
                    <router-link to="/users" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                        <fa icon="arrow-left" />
                        &nbsp;&nbsp;Back
                    </router-link>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap mx-3 mb-3">
            <div class="w-full max-w-full px-3 mt-6 md:w-12/12 md:flex-none">
                <div
                    class="relative flex flex-col min-w-0 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                    <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                        <h6 class="mb-0">Basic Information</h6>
                    </div>
                    <div class="flex-auto p-4 pt-6">
                        <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                            <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                <div class="w-full">
                                    <form>
                                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                                            <div>
                                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">First name</label>
                                                <input type="text" v-model="userForm.firstName" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="First Name" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.first_name">{{ formErrors.first_name }}</p>
                                            </div>
                                            <div>
                                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Last name</label>
                                                <input type="text" v-model="userForm.lastName" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Last Name" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.last_name">{{ formErrors.last_name }}</p>
                                            </div>
                                            <div>
                                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                                                <input type="email" v-model="userForm.email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Email" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.email">{{ formErrors.email }}</p>
                                            </div>  
                                            <div>
                                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Phone number</label>
                                                <input type="tel" v-model="userForm.phoneNumber" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Phone Number" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.phone_number">{{ formErrors.phone_number }}</p>
                                            </div>
                                        </div>
                                        <div class="mb-6">
                                            <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Address</label>
                                            <textarea id="address" v-model="userForm.address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Address" required></textarea>
                                            <p class="text-red-500 text-xs italic" v-if="formErrors.address">{{ formErrors.address }}</p>
                                        </div>
                                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                                            <div>
                                                <label for="userRole" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">User role</label>
                                                <select v-model="userForm.userRole" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" required>
                                                    <option value="">Select User Role</option>
                                                    <option v-for="(item, index) in userRole" :key="index" :value="item.role_id">{{ item.description }}</option>
                                                </select>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.u_tp_id">{{ formErrors.u_tp_id }}</p>
                                            </div>
                                            <div>
                                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username</label>
                                                <input type="text" v-model="userForm.userName" id="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Username" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.username">{{ formErrors.username }}</p>
                                            </div>
                                            <div class="mb-6">
                                                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                                                <input type="password" v-model="userForm.password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required>
                                                <p class="text-red-500 text-xs italic" v-if="formErrors.password">{{ formErrors.password }}</p>
                                            </div> 
                                            <div class="mb-6">
                                                <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirm password</label>
                                                <input type="password" v-model="userForm.passwordConfirm" id="confirm_password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="•••••••••" required>
                                            </div> 
                                        </div>
                                        <button 
                                            ref="submitButton" 
                                            type="submit" 
                                            @click.prevent="submitUser"
                                            :disabled="userForm.loading"
                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            <span v-if="!userForm.loading">Submit</span>
                                            <span v-if="userForm.loading">
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
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import Swal from "sweetalert2/dist/sweetalert2.js";

import { useUserStore, type Users, UserForm } from "@/stores/users";
import { useUserRoleStore, type UserRole, Permission, SaveUserRole } from "@/stores/userRole";
export default defineComponent({
    name: "add-user-role",
    props: {
    },
    components: {},
    setup(){
        const store = useUserStore();
        const userRoleStore = useUserRoleStore();
        const submitButton = ref<HTMLButtonElement | null>(null);

        const userRole = ref<Array<UserRole>>([]);
        const formErrors = ref<UserForm>({
            first_name: '',
            last_name: '',
            email: '',
            phone_number: '',
            address: '',
            u_tp_id: '',
            username: '',
            password: '',
        });

        const userForm = ref({
            firstName: "",
            lastName: "",
            email: "",
            phoneNumber: "",
            address: "",
            userRole: "",
            userName: "",
            password: "",
            passwordConfirm: "",
            loading: false
        });

        onMounted(async () => {
            await getUserRoleInfo();
        });

        const getUserRoleInfo = async() => {
            await userRoleStore.fetchUserRoles();
            const error = Object.values(userRoleStore.errors);
            if (error.length === 0) {
                userRole.value.splice(0, userRole.value.length, ...userRoleStore.userRoleList);
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    userRoleStore.errors = {};
                })
            }
                // userRole.value.splice(0, userRole.value.length, ...userRoleStore.userRoleList);
        }

        const submitUser = async() => {
            const inputs = {
                first_name: userForm.value.firstName,
                last_name: userForm.value.lastName,
                email: userForm.value.email,
                phone_number: userForm.value.phoneNumber,
                address: userForm.value.address,
                u_tp_id: userForm.value.userRole,
                username: userForm.value.userName,
                password: userForm.value.password,
                password_confirmation: userForm.value.passwordConfirm
            };
            userForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            await store.userRegistration(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                    Swal.fire({
                        title: 'Good job!',
                        text: 'Record has been successfuly added',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Ok, got it!'
                    }).then(() => {
                        userForm.value.firstName = "";
                        userForm.value.lastName = "";
                        userForm.value.email = "";
                        userForm.value.phoneNumber = "";
                        userForm.value.address = "";
                        userForm.value.userRole = "";
                        userForm.value.userName = "";
                        userForm.value.password = "";
                        userForm.value.passwordConfirm = "";
                        userForm.value.loading = false;
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
                    })
                }
                submitButton.value!.disabled = false;
                userForm.value.loading = false;
        }

        return {
            userForm,
            submitUser,
            userRole,
            formErrors,
            submitButton
        }
    }
});
</script>