<template>
    <!-- cards row -->
    <div class="flex flex-wrap my-6 -mx-3">
        <div class="w-full h-full max-w-full px-3 lg:w-4/12 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mb-5">
                <div class="relative z-10 flex flex-col flex-auto h-full p-4">
                    <div class="flex items-center space-x-4">
                        <img class="w-20 h-20 rounded-lg" :src="userProfile.logo" alt="" >
                        <div class="font-medium dark:text-white">
                            <div>Name</div>
                            <div class="mt-3">
                            <label
                                for="logoInput"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 cursor-pointer dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
                            >
                                <input
                                id="logoInput"
                                type="file"
                                accept="image/*"
                                class="hidden"
                                @change="changelogo"
                                />
                                <fa icon="cloud-arrow-up" class="h-4 w-5 text-white" /> Upload Logo
                            </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mt-5">
                <form class="space-y-6" @submit.prevent="onSubmitPasswordInfo">
                    <h5 class="mb-0 font-bold capitalize">{{ translate('updatePassword') }}</h5>
                    <div>
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                translate("password") }}</label>
                        <input type="password" v-model="userProfile.password" id="password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="•••••••••" required>
                        <ErrorLabel v-if="formPWErrors.password" :error="formPWErrors.password"></ErrorLabel>
                    </div>
                    <div>
                        <label for="confirm_password"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("confirmPassword")
                            }}</label>
                        <input type="password" v-model="userProfile.password_confirmation"
                            id="confirm_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="•••••••••" required>
                    </div>
                    <button ref="passwordUpdateButton" type="submit" :disabled="userProfile.pwLoading"
                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                        <span v-if="!userProfile.pwLoading">Submit</span>
                        <span v-if="userProfile.pwLoading">
                        Please wait...
                        </span>
                    </button>
                </form>
            </div>
        </div>
        <div class="w-full h-full max-w-full px-3 lg:w-8/12 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
                <form class="space-y-6" @submit.prevent="onSubmitProfileInfo">
                    <h5 class="mb-0 font-bold capitalize">{{ translate('basicInformation') }}</h5>
                    <div>
                        <label for="first_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                translate("firstName") }}</label>
                        <input type="text" v-model="userProfile.firstName" id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="First Name" required>
                        <ErrorLabel v-if="formErrors.first_name" :error="formErrors.first_name"></ErrorLabel>
                    </div>
                    <div>
                        <label for="last_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                translate("lastName") }}</label>
                        <input type="text" v-model="userProfile.lastName" id="last_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Last Name" required>
                        <ErrorLabel v-if="formErrors.last_name" :error="formErrors.last_name"></ErrorLabel>
                    </div>
                    <div>
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                translate("email") }}</label>
                        <input type="email" v-model="userProfile.email" id="email"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Email" required>
                        <ErrorLabel v-if="formErrors.email" :error="formErrors.email"></ErrorLabel>
                    </div>
                    <button ref="submitButton" type="submit" :disabled="userProfile.basicProfileLoading"
                        class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                        <span v-if="!userProfile.basicProfileLoading">Submit</span>
                        <span v-if="userProfile.basicProfileLoading">
                        Please wait...
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <!-- end cards row -->
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useUserStore, type UserMainInfo, UserPasswordInfo } from "@/stores/users";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

import ErrorLabel from "@/components/global/ErrorLabel.vue";
export default defineComponent({
    name: "my-profile",
    components: {
        ErrorLabel
    },
    setup(){
        const store = useAuthStore();
        const router = useRouter();
        const userStore = useUserStore();
        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);
        const passwordUpdateButton = ref<HTMLButtonElement | null>(null);

        const userProfile = ref({
            id: "",
            logo: "",
            selectedLogo: "",
            firstName: "",
            lastName: "",
            email: "",
            password: "",
            password_confirmation: "",
            basicProfileLoading: false,
            pwLoading: false,
        });

        const formErrors = ref<UserMainInfo>({
            first_name: '',
            last_name: '',
            email: '',
        });

        const formPWErrors = ref<UserPasswordInfo>({
            password: '',
        });

        onMounted(async()=>{
            await fetchUserInfo();
        });

        const fetchUserInfo = async() => {
            if(store.user){
                userProfile.value.id = (store.user?.userId || '').toString();
                userProfile.value.firstName = store.user?.firstName || '';
                userProfile.value.lastName = store.user?.lastName || '';
                userProfile.value.email = store.user?.email || '';
                userProfile.value.logo = store.user?.logo || '';
            } else {
                router.push({ name: "dashboard" });
            }
        }

        const changelogo = async(event) => {
            const createUrl = URL.createObjectURL(event.target.files[0]);
            URL.revokeObjectURL(event.target.files[0]);
            userProfile.value.logo = createUrl;
            userProfile.value.selectedLogo = event.target.files[0];

            const formData = new FormData();
            formData.append('image',userProfile.value.selectedLogo);
            formData.append('userId', userProfile.value.id);

            await userStore.saveLogo(formData);
            const error = Object.values(userStore.errors);
            if (error.length === 0) {
                userProfile.value.logo = userStore.logo;
                await store.verifyAuth();
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    userStore.errors = {};
                })
            }
        }

        const onSubmitProfileInfo = async() => {
            const inputs = {
                id: userProfile.value.id,
                first_name: userProfile.value.firstName,
                last_name: userProfile.value.lastName,
                email: userProfile.value.email,
            };
            userProfile.value.basicProfileLoading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            let response = await userStore.updateUserProfile(inputs);
            const error = Object.values(userStore.errors);
            formErrors.value = Object(userStore.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: 'Good job!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok, got it!'
                }).then(async() => {
                    userProfile.value.basicProfileLoading = false;
                    await store.verifyAuth();
                });
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    userStore.errors = {};
                    userStore.formDataErrors = {};
                })
            }
            submitButton.value!.disabled = false;
            userProfile.value.basicProfileLoading = false;
        }

        const onSubmitPasswordInfo = async() => {
            const inputs = {
                id: userProfile.value.id,
                password: userProfile.value.password,
                password_confirmation: userProfile.value.password_confirmation,
            };
            userProfile.value.pwLoading = true;
            if (passwordUpdateButton.value) { 
                passwordUpdateButton.value!.disabled = true;
            }
            let response = await userStore.updateUserProfilePassword(inputs);
            const error = Object.values(userStore.errors);
            formPWErrors.value = Object(userStore.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: 'Good job!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok, got it!'
                }).then(async() => {
                    userProfile.value.pwLoading = false;
                    userProfile.value.password = '';
                    userProfile.value.password_confirmation = '';
                });
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    userStore.errors = {};
                    userStore.formDataErrors = {};
                })
            }
            passwordUpdateButton.value!.disabled = false;
            userProfile.value.pwLoading = false;
        }

        return {
            userProfile,
            changelogo,
            onSubmitProfileInfo,
            onSubmitPasswordInfo,
            translate,
            submitButton,
            passwordUpdateButton,
            formErrors,
            formPWErrors
        }
    }
});
</script>