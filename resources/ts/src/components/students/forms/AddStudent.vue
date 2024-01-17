<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0">{{ translate('students') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/students" class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0">{{ translate('basicInformation') }}</h6>
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
                                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div>
                                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('firstName') }}</label>
                                                    <input type="text" v-model="studentForm.firstName" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('firstName')" required>
                                                    <ErrorLabel v-if="formErrors.first_name" :error="formErrors.first_name"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('lastName') }}</label>
                                                    <input type="text" v-model="studentForm.lastName" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('lastName')" required>
                                                    <ErrorLabel v-if="formErrors.last_name" :error="formErrors.last_name"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('dateOfBirth') }}</label>
                                                    <Datepicker v-model="studentForm.dateOfBirth" placeholder="Date of birth" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                                    <ErrorLabel v-if="formErrors.date_of_birth" :error="formErrors.date_of_birth"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('age') }}</label>
                                                    <input type="text" v-model="studentForm.age" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('age')" required>
                                                    <ErrorLabel v-if="formErrors.age" :error="formErrors.age"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('gender') }}</label>
                                                    <Multiselect 
                                                        v-model="studentForm.gender"
                                                        placeholder="Choose gender"
                                                        :close-on-select="true"
                                                        :searchable="true" 
                                                        :options="genderList" />
                                                    <ErrorLabel v-if="formErrors.gender" :error="formErrors.gender"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('address') }}</label>
                                                    <textarea id="address" v-model="studentForm.address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('address')" required></textarea>
                                                    <ErrorLabel v-if="formErrors.address" :error="formErrors.address"></ErrorLabel>
                                                </div>
                                            </div>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div>
                                                    <label for="specialNotice" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('specialNotice') }}</label>
                                                    <textarea id="specialNotice" v-model="studentForm.specialNotice" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('specialNotice')" required></textarea>
                                                    
                                                </div>
                                            </div>
                                            <button 
                                                type="button" 
                                                @click="resetForm"
                                                class="inline-block px-6 py-3 mr-1 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-purple-800 to-pink-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                                {{ translate('reset') }}
                                            </button>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitStudent"
                                                :disabled="studentForm.loading"
                                                class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                                <span v-if="!studentForm.loading">{{ translate('submit') }}</span>
                                                <span v-if="studentForm.loading">
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
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0">{{ translate('additionalInformation') }}</h6>
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
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                                                <Multiselect 
                                                    @select="selectOrganization"
                                                    v-model="studentForm.orgId"
                                                    :placeholder="translate('chooseOrganization')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="organizationList" />
                                                    <ErrorLabel v-if="formErrors.org_id" :error="formErrors.org_id"></ErrorLabel>
                                            </div>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                                                <Multiselect
                                                    v-model="studentForm.classRoomId"
                                                    :placeholder="translate('chooseClassRoom')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="classRoomList" />
                                                    <ErrorLabel v-if="formErrors.class_room_id" :error="formErrors.class_room_id"></ErrorLabel>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('guardian') }}</label>
                                                <Multiselect 
                                                    v-model="studentForm.parentId"
                                                    :placeholder="translate('chooseGuardian')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="parentsList" />
                                                    <ErrorLabel v-if="formErrors.parent_id" :error="formErrors.parent_id"></ErrorLabel>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">{{ translate('image') }}</label>
                                        <input @change="selectStudentImage" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="multiple_files" type="file">
                                        <div v-if="studentForm.image_label">
                                            <img :src="studentForm.image_label" class="w-12 h-12 mt-2 rounded-lg" alt="Image Preview">
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
import Swal from "sweetalert2/dist/sweetalert2.js";

import Datepicker from 'vue3-datepicker';
import Multiselect from '@vueform/multiselect';
import ErrorLabel from "@/components/global/ErrorLabel.vue";

import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useStudentStore, type ClassRoom, Parent, StudentForm } from "@/stores/students";

export default defineComponent({
    name: "add-student",
    components: {
        Datepicker,
        Multiselect,
        ErrorLabel
    },
    setup(){
        const store = useStudentStore();
        const classRoomStore = useClassRoomStore();

        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);

        const organizationList = ref<Array<Organization>>([]);
        const classRoomList = ref<Array<ClassRoom>>([]);
        const parentsList = ref<Array<Parent>>([]);

        const studentForm = ref({
            firstName: "",
            lastName: "",
            age: "",
            dateOfBirth: new Date(),
            address: "",
            specialNotice: "",
            gender: "",
            orgId: "",
            classRoomId: "",
            parentId: "",
            image: "",
            image_label: "",
            loading: false
        });

        const formErrors = ref<StudentForm>({
            first_name: "",
            last_name: "",
            date_of_birth: "",
            age: "",
            gender: "",
            address: "",
            special_notice: "",
            org_id: "",
            class_room_id: "",
            parent_id: "",
        });

        const genderList = ref([
            {
                value: 'male',
                label: 'Male',
            },
            {
                value: 'female',
                label: 'Female',
            },
            {
                value: 'other',
                label: 'Other',
            },
        ]);

        onMounted(async () => {
            await fetchOrganizationList();
            await fetchParentList();
        });

        const fetchOrganizationList = async() => {
            await classRoomStore.fetchOrganizations();
            const error = Object.values(classRoomStore.errors);
            if (error.length === 0) {
                organizationList.value.splice(0, organizationList.value.length, ...classRoomStore.organizationsList);
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Try again!'
                }).then((result) => {
                    classRoomStore.errors = {};
                })
            }
        }

        const selectOrganization = async (selectedOrganization) => {
            const inputs = {
                organizationId: selectedOrganization,
            };
            await store.lookupClassRooms(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                classRoomList.value.splice(0, classRoomList.value.length, ...store.classRoomList);
                studentForm.value.classRoomId = "";
            } else {
                Swal.fire({
                title: 'Oops...',
                text: error[0] as string,
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Try again!'
                }).then((result) => {
                classRoomStore.errors = {};
                })
            }
        }

        const fetchParentList = async() => {
            await store.lookupParents({})
            const error = Object.values(store.errors);
            if (error.length === 0) {
                parentsList.value.splice(0, parentsList.value.length, ...store.parentsList);
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

        const selectStudentImage = (event) => {
            const createUrl = URL.createObjectURL(event.target.files[0]);
            URL.revokeObjectURL(event.target.files[0]);
            studentForm.value.image_label = createUrl;
            studentForm.value.image = event.target.files[0];
        }

        const submitStudent = async() => {
            const formData = new FormData();
            formData.append('first_name', studentForm.value.firstName);
            formData.append('last_name', studentForm.value.lastName);
            formData.append('date_of_birth', studentForm.value.dateOfBirth.toISOString());
            formData.append('age', studentForm.value.age);
            formData.append('gender', studentForm.value.gender);
            formData.append('address', studentForm.value.address);
            formData.append('special_notice', studentForm.value.specialNotice);
            formData.append('org_id', studentForm.value.orgId);
            formData.append('class_room_id', studentForm.value.classRoomId);
            formData.append('parent_id', studentForm.value.parentId);
            formData.append('image', studentForm.value.image);

            studentForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            let response = await store.studentRegistration(formData);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: 'Good job!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Ok, got it!'
                }).then(() => {
                    studentForm.value.firstName = "";
                    studentForm.value.lastName = "";
                    studentForm.value.age = "";
                    studentForm.value.dateOfBirth = new Date();
                    studentForm.value.address = "";
                    studentForm.value.specialNotice = "";
                    studentForm.value.gender = "";
                    studentForm.value.orgId = "";
                    studentForm.value.classRoomId = "";
                    studentForm.value.parentId = "";
                    studentForm.value.image = "";
                    studentForm.value.image_label = "";
                    studentForm.value.loading = false;
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
            studentForm.value.loading = false;
        }

        const resetForm = () => {
            studentForm.value.firstName = "";
            studentForm.value.lastName = "";
            studentForm.value.age = "";
            studentForm.value.dateOfBirth = new Date();
            studentForm.value.address = "";
            studentForm.value.specialNotice = "";
            studentForm.value.gender = "";
            studentForm.value.orgId = "";
            studentForm.value.classRoomId = "";
            studentForm.value.parentId = "";
        }

        return {
            translate,
            studentForm,
            genderList,
            organizationList,
            selectOrganization,
            classRoomList,
            parentsList,
            submitButton,
            submitStudent,
            formErrors,
            resetForm,
            selectStudentImage
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>