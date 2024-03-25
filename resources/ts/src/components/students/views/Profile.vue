<template>
    <div class="flex flex-wrap my-6 -mx-3">
        <div class="w-full h-full max-w-full px-3 lg:w-4/12 lg:flex-none mb-5">
            <div class="py-8 px-8 bg-white rounded-xl shadow-lg space-y-2 cursor-pointer sm:py-4 sm:flex sm:items-center sm:space-y-0 sm:space-x-6">
                <img class="block mx-auto h-30 w-30 rounded-full sm:mx-0 sm:shrink-0" :src="studentForm.image" alt="Student image" />
                <div class="text-center space-y-2 sm:text-left">
                    <div class="space-y-0.5">
                        <p class="text-lg text-black font-semibold">
                            <fa icon="baby" />&nbsp;&nbsp;{{ studentName }}
                        </p>
                        <p class="text-slate-500 font-medium">
                            <fa icon="chair" />&nbsp;&nbsp;{{ studentForm.classRoom }}
                        </p>
                        <p class="text-slate-500 font-medium">
                            <fa icon="school" />&nbsp;&nbsp;{{ studentForm.org }}
                        </p>
                    </div>
                    <router-link to="/students" class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                        <fa icon="arrow-left" />
                        &nbsp;&nbsp;{{ translate('back') }}
                    </router-link>
                    <router-link v-if="isPermittedRoute('student-development')" to="/student-development" class="inline-block px-6 py-2 ml-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                        <fa icon="pen-to-square" />
                        &nbsp;&nbsp;{{ translate('studentDevelopment') }}
                    </router-link>
                </div>
            </div>
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4 mt-5">
                <form class="space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("guardian") }}</label>
                        <input type="text" v-model="studentForm.parent" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("classRoom") }}</label>
                        <input type="text" v-model="studentForm.classRoom" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("organization") }}</label>
                        <input type="text" v-model="studentForm.org" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                </form>
            </div>
        </div>
        <div class="w-full h-full max-w-full px-3 lg:w-8/12 lg:flex-none">
            <div class="border-black/12.5 shadow-soft-xl relative flex h-full min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border p-4">
                <form class="space-y-6">
                    <h5 class="mb-0 font-bold capitalize text-sub-header">{{ translate('basicInformation') }}</h5>
                    <div>
                        <label for="first_name"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                                translate("name") }}</label>
                        <input type="text" v-model="studentName" id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('address') }}</label>
                        <textarea id="address" v-model="studentForm.address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly></textarea>
                    </div>
                    <div>
                        <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('dateOfBirth') }}</label>
                        <input type="text" v-model="studentDOB" id="first_name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('age') }}</label>
                        <input type="text" v-model="studentForm.age" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                    </div>
                    <div>
                        <label for="specialNotice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('specialNotice') }}</label>
                        <textarea id="specialNotice" v-model="studentForm.specialNotice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly></textarea>  
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useStudentStore, type ClassRoom, Parent, StudentForm, Student } from "@/stores/students";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/stores/auth";

export default defineComponent({
    name: "student-profile",
    setup() {
        const router = useRouter();
        const store = useStudentStore();
        const authStore = useAuthStore();

        const i18n = useI18n();
        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const studentInfo = ref<Array<Student>>([]);
        const studentForm = ref({
            studentId: "",
            firstName: "",
            lastName: "",
            age: "",
            dateOfBirth: new Date(),
            address: "",
            specialNotice: "",
            gender: "",
            org: "",
            classRoom: "",
            parent: "",
            image: "" as any,
            loading: false
        });

        onMounted(async () => {
            await getStudentInfo();
        });

        const getStudentInfo = async () => {
            if (store.idStudent) {
                studentInfo.value.splice(0, studentInfo.value.length, ...store.studentList);
                let results = studentInfo.value.filter((item) => {
                    return item.id.toString() == store.idStudent.toString();
                });
                studentInfo.value.splice(0, studentInfo.value.length, ...results);
                
                studentForm.value.image = studentInfo?.value[0].image_url || "";

                studentForm.value.firstName = studentInfo?.value[0].first_name || "";
                studentForm.value.lastName = studentInfo?.value[0].last_name || "";
                studentForm.value.dateOfBirth = new Date(studentInfo?.value[0].date_of_birth) || new Date();
                studentForm.value.age = studentInfo?.value[0].age || "";
                studentForm.value.gender = studentInfo?.value[0].gender || "";
                studentForm.value.address = studentInfo?.value[0].address || "";
                studentForm.value.specialNotice = studentInfo?.value[0].special_notice || "";

                studentForm.value.org = studentInfo?.value[0].organization.name || "";
                studentForm.value.classRoom = studentInfo?.value[0].class_room.name || "";
                studentForm.value.parent = studentInfo?.value[0].guardian.name || "";
            } else {
                router.go(-1);
            }
        }

        const studentName = computed(()=>{
            return studentForm.value.firstName + ' ' + studentForm.value.lastName;
        });

        const studentDOB = computed(()=>{
            const date = new Date(studentForm.value.dateOfBirth).toISOString();
            return date.substring(0, date.indexOf('T'));
        });

        const isPermittedRoute = (currentRoute) => {
            if (authStore.userPermissions.length > 0) {
                return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }
        
        return {
            translate,
            studentForm,
            studentName,
            studentDOB,
            isPermittedRoute
        }
    }
});
</script>