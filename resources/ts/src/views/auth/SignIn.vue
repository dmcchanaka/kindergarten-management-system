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
                <input type="password" v-model="login.password" :placeholder="translate('enterPassword')" class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border border-lime-500 focus:border-lime-400
                    focus:bg-white focus:outline-none" required>
            </div>

            <div class="text-right mt-2">
                <!-- <a href="#" class="text-sm font-semibold text-gray-700 hover:text-blue-700 focus:text-blue-700">Forgot Password?</a> -->
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

    const login = ref({
        username: "",
        password: ""
    });

    onMounted(() => {
      if (store.isAuthenticated) {
        router.push({ name: "dashboard" });
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
                router.push({ name: "dashboard" });
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
        translate
    }
  },
});
</script>