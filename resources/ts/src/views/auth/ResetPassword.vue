<template>
    <div class="bg-gray-100 p-5 flex rounded-2xl shadow-lg max-w-3xl">
        <div class="px-10">
            <h2 class="text-title font-bold text-[#002D74] header text-center">{{ translate('resetPassword') }}</h2>
            <form class="mt-6" @submit.prevent="onSubmitPassword">
                <div class="mb-3">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("password") }}</label>
                    <input type="password" v-model="resetForm.password" id="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="•••••••••" required>
                    <ErrorLabel v-if="formErrors.password" :error="formErrors.password"></ErrorLabel>
                    <ErrorLabel v-if="formErrors.token" :error="formErrors.token"></ErrorLabel>
                </div>
                    <div>
                        <label for="confirm_password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("confirmPassword") }}</label>
                        <input type="password" v-model="resetForm.password_confirmation"
                            id="confirm_password"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="•••••••••" required>
                    </div>
                <div class="text-right mt-4">
                    <router-link to="sign-in" class="text-sm font-semibold text-gray-700 hover:text-lime-500 focus:text-lime-500">Back to login</router-link>
                </div>
                <button 
                    ref="submitButton" 
                    class="w-full block bg-lime-500 hover:bg-lime-400 focus:bg-lime-600 text-white font-semibold rounded-lg px-4 py-3 mt-6"
                    :disabled="resetForm.loading"
                    >
                    <span v-if="!resetForm.loading">{{ translate('submit') }}</span>
                    <span v-if="resetForm.loading">
                    {{ translate('pleaseWait') }}...
                    </span>
                </button>
            </form>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useAuthStore, type User } from "@/stores/auth";
import { useI18n } from "vue-i18n";
import { useRouter } from "vue-router";

import ErrorLabel from "@/components/global/ErrorLabel.vue";
export default defineComponent({
    name: "reset-password",
    components: {
        ErrorLabel
    },
    setup() {
        const store = useAuthStore();
        const router = useRouter();
        const i18n = useI18n();
        const { t, te } = useI18n();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        console.log(translate);

        i18n.locale.value = localStorage.getItem("lang")
        ? (localStorage.getItem("lang") as string)
        : "de";

        const submitButton = ref<HTMLButtonElement | null>(null);
        const resetForm = ref({
            token: "",
            password: "",
            password_confirmation: "",
            loading: false,
        });
        const formErrors = ref<any>({
            password: '',
            token: '',
        });

        onMounted(() => {
            const token = router.currentRoute.value.query.token;
            if (token) {
                resetForm.value.token = token as string;
            }
        });

        const onSubmitPassword = async(values: any) => {
            resetForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }

            let inputs = {
                token: resetForm.value.token,
                password: resetForm.value.password,
                password_confirmation: resetForm.value.password_confirmation,
            }
            await store.passwordReset(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: translate('goodJob') + '!',
                    text: translate('passwordResetLinkHasBeenSent') + '!',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('okGotIt') + '!'
                }).then(() => {
                    router.push({ name: "sign-in" });
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
        resetForm.value.loading = false;
        }

        return {
            translate,
            submitButton,
            resetForm,
            formErrors,
            onSubmitPassword
        }
    }
});
</script>