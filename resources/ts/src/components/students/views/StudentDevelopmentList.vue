<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0 text-header">{{ translate('studentDevelopment') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/student-profile" class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
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
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate("name") }}</label>
                                                    <input type="text" v-model="studentName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" readonly>
                                                </div>
                                                <div>
                                                    <label for="specialNotice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('note') }}</label>
                                                    <textarea id="specialNotice" v-model="studentDevelopmentForm.note" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('note')" required></textarea>
                                                    
                                                </div>
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="resetForm"
                                                class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl bg-red-600 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                                {{ translate('reset') }}
                                            </button>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitStudentDevelopment"
                                                :disabled="studentDevelopmentForm.loading"
                                                class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                                                <span v-if="!studentDevelopmentForm.loading">{{ translate('submit') }}</span>
                                                <span v-if="studentDevelopmentForm.loading">
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
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0 text-sub-header">{{ translate('previousNotes') }}</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full h-96 overflow-y-auto">
                                        <ul role="list" v-if="studentDevelopmentList.length > 0">
                                            <template v-for="(item, index) in studentDevelopmentList">
                                                <li class="group/item bg-white shadow-soft-xs hover:bg-slate-100 p-3 rounded-2 mb-2 flex">
                                                    <div class="ml-3 overflow-hidden">
                                                        <h2 class="text-slate-900 group-hover:text-white text-sub-header font-semibold">{{ item.date }}</h2>
                                                        <p class="text-slate-500 group-hover:text-white text-sm">{{ item.note }}</p>
                                                    </div>
                                                </li>
                                            </template>
                                        </ul>
                                        <div v-else class="flex flex-col items-center justify-center min-h-[19rem]">
                                            <div class="h-14 w-14 rounded-full bg-lime-500 flex items-center justify-center my-3">
                                                    <fa icon="book" class="text-2xl text-white" />
                                                </div>
                                            <div class="font-semibold">{{ translate('studentDevelopmentNotes') }}</div>
                                            <span class="text-gray-500">{{ translate('thereIsNoAnyPreviousNotes') }}</span>
                                        </div>
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
import { useStudentStore, type ClassRoom, Parent, StudentForm, Student, StudentDevelopmentForm } from "@/stores/students";
import { useRouter } from "vue-router";
import Swal from "sweetalert2/dist/sweetalert2.js";

export default defineComponent({
    name: "student-profile",
    setup() {
        const router = useRouter();
        const store = useStudentStore();

        const i18n = useI18n();
        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);
        const studentInfo = ref<Array<Student>>([]);
        const studentDevelopmentList = ref<Array<any>>([]);
        const studentDevelopmentForm = ref({
            studentId: "",
            firstName: "",
            lastName: "",
            note: "",
            loading: false,
        });

        const formErrors = ref<StudentDevelopmentForm>({
            note: "",
        });

        onMounted(async () => {
            await getStudentInfo();
            await getStudentDevelopmentNotes();
        });

        const getStudentInfo = async () => {
            if (store.idStudent) {
                studentInfo.value.splice(0, studentInfo.value.length, ...store.studentList);
                let results = studentInfo.value.filter((item) => {
                    return item.id.toString() == store.idStudent.toString();
                });
                studentInfo.value.splice(0, studentInfo.value.length, ...results);

                studentDevelopmentForm.value.studentId = studentInfo?.value[0].id.toString() || "";
                studentDevelopmentForm.value.firstName = studentInfo?.value[0].first_name || "";
                studentDevelopmentForm.value.lastName = studentInfo?.value[0].last_name || "";
            } else {
                router.go(-1);
            }
        }

        const getStudentDevelopmentNotes = async() => {
            const inputs = {
                studentId: studentDevelopmentForm.value.studentId,
            }
            await store.fetchStudentDevelopmentNotes(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                studentDevelopmentList.value.splice(0, studentDevelopmentList.value.length, ...store.studentDevelopmentList);
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
        }

        const studentName = computed(()=>{
            return studentDevelopmentForm.value.firstName + ' ' + studentDevelopmentForm.value.lastName;
        });

        const submitStudentDevelopment = async() => {
            studentDevelopmentForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            const inputs = {
                studentId: studentDevelopmentForm.value.studentId,
                note: studentDevelopmentForm.value.note
            }
            let response = await store.addStudentDevelopmentNote(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: translate('goodJob') + '!',
                    text: translate(response.message),
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('okGotIt') + '!'
                }).then(async() => {
                    studentDevelopmentForm.value.note = "";
                    studentDevelopmentForm.value.loading = false;
                    await getStudentDevelopmentNotes();
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
                    store.formDataErrors = {};
                })
            }

            submitButton.value!.disabled = false;
            studentDevelopmentForm.value.loading = false;
        }

        const resetForm = async() => {}

        return {
            translate,
            studentDevelopmentForm,
            submitButton,
            submitStudentDevelopment,
            studentName,
            resetForm,
            studentDevelopmentList
        }
    }
});
</script>