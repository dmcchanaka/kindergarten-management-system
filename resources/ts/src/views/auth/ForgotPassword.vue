<template>
    <div class="bg-gray-100 p-5 flex rounded-2xl shadow-lg max-w-3xl">
        <div class="px-10">
            <h2 class="text-title font-bold text-[#002D74] header text-center">{{ translate('forgotPassword') }}</h2>
            <form class="mt-6" @submit.prevent="onSubmitEmail">
                <div>
                    <label class="block text-[#002D74]">{{ translate('email') }}</label>
                    <input v-model="resetForm.email" :placeholder="translate('email')" class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border border-lime-500 focus:border-lime-400 focus:bg-white focus:outline-none" autofocus required>
                    <ErrorLabel v-if="formErrors.email" :error="formErrors.email"></ErrorLabel>
                </div>
                <div class="text-right mt-4">
                    <router-link to="sign-in" class="text-sm font-semibold text-gray-700 hover:text-lime-500 focus:text-lime-500">{{ translate('backToLogin') }}</router-link>
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

import ErrorLabel from "@/components/global/ErrorLabel.vue";
export default defineComponent({
  name: "forgot-password",
  components: {
    ErrorLabel
  },
  setup() {
    const store = useAuthStore();
    const { t, te } = useI18n();
    const i18n = useI18n();

    const translate = (text: string) => {
        if (te(text)) {
            return t(text);
        } else {
            return text;
        }
    };

    i18n.locale.value = localStorage.getItem("lang")
        ? (localStorage.getItem("lang") as string)
        : "de";

    const submitButton = ref<HTMLButtonElement | null>(null);
    const resetForm = ref({
        email: "",
        loading: false,
    });
    const formErrors = ref<any>({
        email: '',
    });

    const onSubmitEmail = async (values: any) => {
        resetForm.value.loading = true;
        if (submitButton.value) { 
            submitButton.value!.disabled = true;
        }

        let inputs = {
            email: resetForm.value.email
        }
        await store.forgotPassword(inputs);
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
        onSubmitEmail,
        formErrors
    }
  }
});
</script>