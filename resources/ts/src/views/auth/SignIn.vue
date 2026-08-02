<template>
    <div class="bg-gray-100 p-5 flex rounded-2xl shadow-lg max-w-3xl">
        <div class="px-10">
            <h2 class="text-title font-bold text-[#002D74] header text-center">{{ translate('login') }}</h2>
            <!-- <p class="text-sm mt-4 text-[#002D74]">{{ translate('loginSubTitle') }}</p> -->
            <form class="mt-6" @submit.prevent="onSubmitLogin">
            <div>
                <label class="block text-[#002D74]">{{ translate('username') }}</label>
                <input v-model="login.username" :placeholder="translate('enterUserame')" class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border border-lime-500 focus:border-lime-400 focus:bg-white focus:outline-none" autofocus required>
            </div>

            <div class="mt-4">
                <label class="block text-[#002D74]">{{ translate('password') }}</label>
                <div class="relative w-full">
                    <input :type="showPassword ? 'text' : 'password'" v-model="login.password" :placeholder="translate('enterPassword')" class="w-full px-4 py-3 pr-10 rounded-lg bg-gray-200 mt-2 border border-lime-500 focus:border-lime-400 focus:bg-white focus:outline-none" required>
                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 mt-1 -translate-y-1/2 text-gray-500 hover:text-lime-600 focus:outline-none">
                        <fa :icon="showPassword ? 'eye-slash' : 'eye'" />
                    </button>
                </div>
            </div>

            <div class="text-right mt-2">
                <router-link to="forgot-password" class="text-sm font-semibold text-gray-700 hover:text-lime-500 focus:text-lime-500">{{ translate('forgotPassword') }}?</router-link>
            </div>

            <button 
                ref="submitButton" 
                class="w-full block bg-lime-500 hover:bg-lime-400 focus:bg-lime-600 text-white font-semibold rounded-lg px-4 py-3 mt-6"
                :disabled="loading"
                >
                <span v-if="!loading">{{ translate('logIn') }}</span>
                <span v-if="loading">
                {{ translate('pleaseWait') }}...
                </span>
            </button>
            </form>

            <div class="mt-2 grid grid-cols-3 items-center text-gray-500">
                <hr class="border-gray-500" />
                <p class="text-center text-sm">&nbsp;</p>
                <hr class="border-gray-500" />
            </div>
            <div class="text-sm flex justify-between items-center mt-3 text-[#002D74]">
                <p class="mt-5 text-[#002D74]">{{ translate('changeLanguage') }}</p>
                <select @click="selectLanguage" v-model="i18n.locale.value" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-lime-500 blockl p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option v-for="(country, code) in countries" :key="code" :value="code">
                        {{ country.name.value }}
                    </option>
                </select>
            </div>
        </div>

        <!-- <div class="w-1/2 md:block hidden ">
            <img :src="getAssetPath('media/backgrounds/bg-02.png')" style="height: 100%;width: 100%;" class="rounded-2xl" alt="page img">
        </div> -->

        </div>
    <div>
        
    </div>
</template>

<style lang="scss">
</style>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore, type User } from "@/stores/auth";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "sign-in",
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

    //languages with country flags
    const countries = {
      en: {
        flag: "media/flags/united-states.svg",
        name: computed(()=> { return translate("english") }),
      },
      de: {
        flag: "media/flags/germany.svg",
        name: computed(()=> { return translate('german') }),
      },
    };

    i18n.locale.value = localStorage.getItem("lang")
      ? (localStorage.getItem("lang") as string)
      : "de";

    const submitButton = ref<HTMLButtonElement | null>(null);
    const loading = ref(false);
    const showPassword = ref(false);

    const login = ref({
        username: "",
        password: ""
    });

    onMounted(() => {
      if (store.isAuthenticated && store.isPasswordReset) {
        router.push({ name: "dashboard" });
      } else {
        signOut();
      }
    });

    const onSubmitLogin = async (values: any) => {
        values = values as User;
        loading.value = true;
        if (submitButton.value) { 
            submitButton.value!.disabled = true;
        }

        let credentials = {
            username: login.value.username,
            password: login.value.password,
        }
        await store.login(credentials);
        const error = Object.values(store.errors);
        if (error.length === 0) {
            Swal.fire({
                title: translate('goodJob') + '!',
                text: translate('successfulyLoggedIn') + '!',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: translate('okGotIt') + '!'
            }).then(() => {
                if(store.user?.initialLogin === true){
                    resetPassword();
                } else {
                    router.push({ name: "dashboard" });
                }
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
        })
      }
      submitButton.value!.disabled = false;
      loading.value = false;
    }

    const resetPassword = () => {
        Swal.fire({
            title: "Reset your password",
            html: '<div style="position: relative; width: 100%;">'
                + '<input id="swal-password" type="password" class="swal2-input" placeholder="Password" style="width: 100%; box-sizing: border-box; padding-right: 45px; margin: 1em auto 0 auto;">'
                + '<button type="button" id="toggle-password" style="position: absolute; right: 25px; top: 60%; transform: translateY(-50%); border: none; background: transparent; cursor: pointer; display: flex; align-items: center;">'
                    + '<svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width: 24px; height: 24px; color: #6b7280;">'
                        + '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />'
                        + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />'
                    + '</svg>'
                + '</button>'
            + '</div>',
            showCancelButton: true,
            confirmButtonText: "Reset",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: "#d33",
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            footer: '<div style="text-align: center;">'
                + '<p style="font-weight: bold">Password Policy</p>'
                + '<ul style="text-align: left;list-style-type: none; padding-left: 0;">'
                    + '<li>- At least 8 characters</li>'
                    + '<li>- 1 capital letter</li>'
                    + '<li>- 1 number</li>'
                    + '<li>- 1 special character (e.g., #)</li>'
                + '</ul>'
            + '</div>',
            didOpen: () => {
                const passwordInput = document.getElementById('swal-password') as HTMLInputElement;
                const toggleButton = document.getElementById('toggle-password');
                const eyeIcon = document.getElementById('eye-icon');
                if (toggleButton && passwordInput && eyeIcon) {
                    toggleButton.addEventListener('click', () => {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        if (type === 'text') {
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.815 7.815L21 21m-2.956-2.956L14.39 14.39m0 0a3 3 0 11-4.143-4.143L14.39 14.39z" />';
                        } else {
                            eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.43 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />'
                                + '<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />';
                        }
                    });
                }
            },
            preConfirm: async () => {
                const passwordInput = document.getElementById('swal-password') as HTMLInputElement;
                const password = passwordInput ? passwordInput.value : '';
                if (!password) {
                    Swal.showValidationMessage('Password is required');
                    return false;
                }
                try {
                    const inputs = {
                        id: store.user?.userId,
                        password: password,
                        password_confirmation: password
                    };
                    await store.resetPassword(inputs);
                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        return password;
                    } else {
                        Swal.showValidationMessage(`
                            Request failed: ${error[0].password}
                        `);
                        return false;
                    }
                } catch (error) {
                    return false;
                }
            },
        }).then((result) => {
            if (result.isConfirmed) {
                router.push({ name: "dashboard" });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                signOut();
            }
        });
    }

    const signOut = () => {
      store.logout();
      router.push({ name: "sign-in" });
    };

    //select induvidual language
    const selectLanguage = (event) => {
      localStorage.setItem("lang", event.target.value);
      i18n.locale.value = event.target.value;
    };

    return {
        login,
        onSubmitLogin,
        getAssetPath,
        submitButton,
        loading,
        selectLanguage,
        i18n,
        countries,
        translate,
        showPassword
    }
  },
});
</script>