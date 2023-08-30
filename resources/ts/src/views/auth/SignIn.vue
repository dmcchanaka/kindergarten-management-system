<template>
    <div class="bg-gray-100 p-5 flex rounded-2xl shadow-lg max-w-3xl">
        <div class="md:w-1/2 px-5">
            <h2 class="text-2xl font-bold text-[#002D74]">Login</h2>
            <p class="text-sm mt-4 text-[#002D74]">If you have an account, please login</p>
            <form class="mt-6" @submit.prevent="onSubmitLogin">
            <div>
                <label class="block text-gray-700">Username</label>
                <input v-model="login.username" placeholder="Enter Username" class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500 focus:bg-white focus:outline-none" autofocus required>
            </div>

            <div class="mt-4">
                <label class="block text-gray-700">Password</label>
                <input v-model="login.password" placeholder="Enter Password" class="w-full px-4 py-3 rounded-lg bg-gray-200 mt-2 border focus:border-blue-500
                    focus:bg-white focus:outline-none" required>
            </div>

            <div class="text-right mt-2">
                <a href="#" class="text-sm font-semibold text-gray-700 hover:text-blue-700 focus:text-blue-700">Forgot Password?</a>
            </div>

            <button 
                ref="submitButton" 
                class="w-full block bg-blue-500 hover:bg-blue-400 focus:bg-blue-400 text-white font-semibold rounded-lg px-4 py-3 mt-6"
                :disabled="loading"
                >
                <span v-if="!loading">Log In</span>
                <span v-if="loading">
                Please wait...
                </span>
            </button>
            </form>

            <div class="mt-7 grid grid-cols-3 items-center text-gray-500">
            <hr class="border-gray-500" />
            <p class="text-center text-sm">OR</p>
            <hr class="border-gray-500" />
            </div>

            <button class="bg-white border py-2 w-full rounded-xl mt-5 flex justify-center items-center text-sm hover:scale-105 duration-300 ">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-6 h-6" viewBox="0 0 48 48"><defs><path id="a" d="M44.5 20H24v8.5h11.8C34.7 33.9 30.1 37 24 37c-7.2 0-13-5.8-13-13s5.8-13 13-13c3.1 0 5.9 1.1 8.1 2.9l6.4-6.4C34.6 4.1 29.6 2 24 2 11.8 2 2 11.8 2 24s9.8 22 22 22c11 0 21-8 21-22 0-1.3-.2-2.7-.5-4z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" fill="#FBBC05" d="M0 37V11l17 13z"/><path clip-path="url(#b)" fill="#EA4335" d="M0 11l17 13 7-6.1L48 14V0H0z"/><path clip-path="url(#b)" fill="#34A853" d="M0 37l30-23 7.9 1L48 0v48H0z"/><path clip-path="url(#b)" fill="#4285F4" d="M48 48L17 24l-4-3 35-10z"/></svg>
            <span class = "ml-4">Login with Google</span>
            </button>

            <div class="text-sm flex justify-between items-center mt-3 text-[#002D74]">
                <p>Change language</p>
                <select id="language" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 blockl p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="us">ENGLISH</option>
                    <option value="de">GERMEN</option>
                    <option value="fr">FRENCH</option>
                </select>
            </div>
        </div>

        <div class="w-1/2 md:block hidden ">
            <img :src="getAssetPath('media/backgrounds/bg-02.png')" class="rounded-2xl" alt="page img">
        </div>

        </div>
    <div>
        
    </div>
</template>

<style lang="scss">
</style>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore, type User } from "@/stores/auth";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";


export default defineComponent({
  name: "sign-in",
  setup() {
    const store = useAuthStore();
    const router = useRouter();

    const submitButton = ref<HTMLButtonElement | null>(null);
    const loading = ref(false);

    const login = ref({
        username: "",
        password: ""
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
                title: 'Good job!',
                text: 'You have successfully logged in!',
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Ok, got it!'
            }).then(() => {
                router.push({ name: "dashboard" });
            })
        // Swal.fire({
        //   text: "You have successfully logged in!",
        //   icon: "success",
        //   buttonsStyling: false,
        //   confirmButtonText: "Ok, got it!",
        //   heightAuto: false,
        //   customClass: {
        //    confirmButton: "btn fw-semobold btn-light-primary",
        //   },
        // }).then(() => {
        //   // Go to page after successfully login
        //   router.push({ name: "choose-product" });
        // });
      } else {
        Swal.fire({
            title: 'Oops...',
            text: error[0] as string,
            icon: 'error',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Try again!'
        }).then((result) => {
            store.errors = {};
        })
      }
      submitButton.value!.disabled = false;
      loading.value = false;
    }

    return {
        login,
        onSubmitLogin,
        getAssetPath,
        submitButton,
        loading
    }
  },
});
</script>