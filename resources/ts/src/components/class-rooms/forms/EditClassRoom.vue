<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0 text-header">{{ translate('classRooms') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/class-rooms" class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
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
                                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                                                    <Multiselect 
                                                    v-model="classRoom.org_id"
                                                    :placeholder="translate('chooseOrganization')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :create-option="true" 
                                                    :options="organizationList" />
                                                    <ErrorLabel v-if="formErrors.org_id" :error="formErrors.org_id.toString()"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoomName') }}</label>
                                                    <input type="text" v-model="classRoom.name" id="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('classRoomName')" required>
                                                    <ErrorLabel v-if="formErrors.name" :error="formErrors.name"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('email') }}</label>
                                                    <input type="email" v-model="classRoom.email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('emailAddress')" required>
                                                    <ErrorLabel v-if="formErrors.email" :error="formErrors.email"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="phone" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('telephone') }}</label>
                                                    <input type="tel" v-model="classRoom.phone_number" id="phone" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('phoneNumber')" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" required>
                                                    <ErrorLabel v-if="formErrors.phone_number" :error="formErrors.phone_number"></ErrorLabel>
                                                </div>
                                            </div>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitClassRoom"
                                                :disabled="classRoom.loading"
                                                class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                                <span v-if="!classRoom.loading">{{ translate('submit') }}</span>
                                                <span v-if="classRoom.loading">
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
                                    <h6 class="mb-0 text-sub-header">{{ translate('teachersInformation') }}</h6>
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
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('teachers') }}</label>
                                                <Multiselect 
                                                    v-model="classRoom.teachers"
                                                    placeholder="Choose teachers" 
                                                    mode="tags" 
                                                    :close-on-select="false"
                                                    :searchable="true" 
                                                    :create-option="true" 
                                                    :options="teachersList" />
                                            </div>
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
import { useClassRoomStore, type Teacher, Organization, ClassRoomForm, ClassRoom } from "@/stores/classRoom";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";

import Multiselect from '@vueform/multiselect';
import ErrorLabel from "@/components/global/ErrorLabel.vue";
export default defineComponent({
    name: "edit-class-room",
    components: {
        Multiselect,
        ErrorLabel
    },
    setup(){
        const { t, te } = useI18n();
        const store = useClassRoomStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const submitButton = ref<HTMLButtonElement | null>(null);

        const organizationList = ref<Array<Organization>>([]);
        const teachersList = ref<Array<Teacher>>([]);
        const classRoomInfo = ref<Array<ClassRoom>>([]);

        const classRoom = ref({
            id: "",
            org_id: null as number | null,
            name: "",
            phone_number: "",
            email: "",
            teachers: [] as number[],
            loading: false
        });

        const formErrors = ref<ClassRoomForm>({
            org_id: null,
            name: '',
            phone_number: '',
            email: '',
            teachers: [],
        });

        onMounted(async () => {
            await fetchOrganizationList();
            await fetchTeachesList();
            await getClassRoomInfo();
        });

        const fetchOrganizationList = async() => {
            await store.fetchOrganizations();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                organizationList.value.splice(0, organizationList.value.length, ...store.organizationsList);
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
        }

        const fetchTeachesList = async() => {
            await store.fetchTeachers();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                teachersList.value.splice(0, teachersList.value.length, ...store.teachersList);
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
        }

        const getClassRoomInfo = async() => {
            if(store.idClassRoom){
                classRoomInfo.value.splice(0, classRoomInfo.value.length, ...store.classRoomList);
                let results = classRoomInfo.value.filter((item) => {
                    return item.id.toString() == store.idClassRoom.toString();
                });
                classRoomInfo.value.splice(0,classRoomInfo.value.length,...results);
                
                classRoom.value.id = classRoomInfo?.value[0].id.toString() || "";
                classRoom.value.org_id = classRoomInfo?.value[0].organization?.id;
                classRoom.value.name = classRoomInfo?.value[0].name || "";
                classRoom.value.email = classRoomInfo?.value[0].email || "";
                classRoom.value.phone_number = classRoomInfo?.value[0].phone_number || "";

                const allocatedTeachers = classRoomInfo?.value[0].teachers.map(teacher => teacher.id);
                classRoom.value.teachers = allocatedTeachers
            } else {
                router.go(-1);
            }
        }

        const submitClassRoom = async() => {
            const inputs = {
                id: classRoom.value.id,
                org_id: classRoom.value.org_id,
                name: classRoom.value.name,
                phone_number: classRoom.value.phone_number,
                email: classRoom.value.email,
                teachers: classRoom.value.teachers,
            };
            classRoom.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            await store.classRoomModification(inputs);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: translate('goodJob') + '!',
                    text: translate('recordHasBeenSuccesfullyUpdated'),
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('okGotIt') + '!'
                }).then(() => {
                    classRoom.value.loading = false;
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
            classRoom.value.loading = false;
        }
        

        return {
            submitButton,
            organizationList,
            teachersList,
            classRoom,
            formErrors,
            submitClassRoom,
            translate
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>