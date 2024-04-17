<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <!-- <h6 class="mb-0 text-header">{{ translate('event') }}</h6> -->
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/events" class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
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
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('description') }}</label>
                                                    <textarea v-model="eventForm.description" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('description')" required></textarea>
                                                    <ErrorLabel v-if="formErrors.description" :error="formErrors.description"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('date') }}</label>
                                                    <Datepicker :format="format" :locale="i18n.locale.value" :cancelText="translate('cancel')" :selectText="translate('select')" v-model="eventForm.date" :placeholder="translate('date')" class=" text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 p-2.5 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
                                                    <ErrorLabel v-if="formErrors.date" :error="formErrors.date"></ErrorLabel>
                                                </div>
                                            </div>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitEvent"
                                                :disabled="eventForm.loading"
                                                class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                                                <span v-if="!eventForm.loading">{{ translate('submit') }}</span>
                                                <span v-if="eventForm.loading">
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
                                    <h6 class="mb-0 text-sub-header">{{ translate('additionalInformation') }}</h6>
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
                                            <div class="grid gap-2 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                                                <Multiselect 
                                                    @select="selectOrganization"
                                                    v-model="eventForm.orgId"
                                                    :placeholder="translate('chooseOrganization')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="organizationList" />
                                                    <ErrorLabel v-if="formErrors.org_id" :error="formErrors.org_id"></ErrorLabel>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-2 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                                                <Multiselect
                                                    v-model="eventForm.classRoomId"
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

import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useStudentStore, type ClassRoom, Parent, StudentForm } from "@/stores/students";
import { useEventStore, type Event } from "@/stores/event";

import ErrorLabel from "@/components/global/ErrorLabel.vue";
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import "@vuepic/vue-datepicker/dist/main.css";

import { useRouter } from "vue-router";
export default defineComponent({
    name: "add-gallery",
    components: {
        ErrorLabel,
        Multiselect,
        Datepicker
    },
    setup() {
        const i18n = useI18n();
        const { t, te } = useI18n();
        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const store = useEventStore();
        const classRoomStore = useClassRoomStore();
        const studentStore = useStudentStore();
        const router = useRouter();

        const eventForm = ref({
            id: "",
            description: "",
            date: "",
            orgId: "",
            classRoomId: "",
            loading: false
        });

        const formErrors = ref<any>({
            description: "",
            feature_image: "",
            orgId: "",
            classRoomId: "",
        });

        const format = (date) => {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            return `${day}.${month}.${year}`;
        };

        i18n.locale.value = localStorage.getItem("lang")
        ? (localStorage.getItem("lang") as string)
        : "en";

        const submitButton = ref<HTMLButtonElement | null>(null);

        const organizationList = ref<Array<Organization>>([]);
        const classRoomList = ref<Array<ClassRoom>>([]);
        const eventInfo = ref<Array<Event>>([]);

        onMounted(async () => {
            await fetchOrganizationList();
            await getEventInfo();
        });

        const getEventInfo = async () => {
            if(store.idEvent){
                eventInfo.value.splice(0, eventInfo.value.length, ...store.eventList);
                let results = eventInfo.value.filter((item) => {
                    return item.id.toString() == store.idEvent.toString();
                });
                eventInfo.value.splice(0,eventInfo.value.length,...results);

                eventForm.value.id = eventInfo?.value[0].id.toString() || "";
                eventForm.value.date = eventInfo?.value[0].event_date || "";
                eventForm.value.description = eventInfo?.value[0].description || "";

                eventForm.value.orgId = eventInfo?.value[0].organization.id.toString() || "";
                if(eventInfo?.value[0].organization.id.toString() != ""){
                    await selectOrganization(eventInfo?.value[0].organization.id.toString());
                    eventForm.value.classRoomId = eventInfo?.value[0].class_room.id.toString() || "";
                }
            } else {
                router.go(-1);
            }
        }

        const fetchOrganizationList = async() => {
            await classRoomStore.fetchOrganizations();
            const error = Object.values(classRoomStore.errors);
            if (error.length === 0) {
                organizationList.value.splice(0, organizationList.value.length, ...classRoomStore.organizationsList);
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    classRoomStore.errors = {};
                })
            }
        }

        const selectOrganization = async (selectedOrganization) => {
            const inputs = {
                organizationId: selectedOrganization,
            };
            await studentStore.lookupClassRooms(inputs);
            const error = Object.values(studentStore.errors);
            if (error.length === 0) {
                classRoomList.value.splice(0, classRoomList.value.length, ...studentStore.classRoomList);
                eventForm.value.classRoomId = "";
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    studentStore.errors = {};
                })
            }
        }

        const submitEvent = async() => {
            const inputs = {
                id: eventForm.value.id,
                description: eventForm.value.description,
                date: eventForm.value.date,
                org_id: eventForm.value.orgId,
                class_room_id: eventForm.value.classRoomId,
            };
            eventForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            await store.eventModification(inputs);
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
                    eventForm.value.loading = false;
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
            eventForm.value.loading = false;
        }

        return {
            translate,
            eventForm,
            formErrors,
            submitEvent,
            submitButton,
            organizationList,
            selectOrganization,
            classRoomList,
            format,
            i18n
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>