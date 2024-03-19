<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full">
            <div class="grid gap-1 md:grid-cols-4">
                <div class="grid gap-1 mb-6 md:grid-cols-1 text-left">
                    <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                    <Multiselect 
                        @select="selectOrganization"
                        v-model="filters.orgId"
                        :placeholder="translate('chooseOrganization')"
                        :close-on-select="true"
                        :searchable="true" 
                        :options="organizationList" />
                </div>
                <div class="grid gap-2 mb-6 md:grid-cols-1 text-left">
                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                    <Multiselect
                        v-model="filters.classRoomId"
                        :placeholder="translate('chooseClassRoom')"
                        :close-on-select="true"
                        :searchable="true" 
                        :option-height="10"
                        :options="classRoomList" />
                </div>
                <div class="grid gap-2 mb-6 md:grid-cols-1 text-left">
                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('startDate') }}</label>
                    <Datepicker :format="format" :locale="i18n.locale.value" :cancelText="translate('cancel')" :selectText="translate('select')" v-model="filters.fromDate" :placeholder="translate('startDate')"/>
                </div>
                <div class="grid gap-2 mb-6 md:grid-cols-1 text-left">
                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('endDate') }}</label>
                    <Datepicker :format="format" :locale="i18n.locale.value" :cancelText="translate('cancel')" :selectText="translate('select')" v-model="filters.toDate" :placeholder="translate('endDate')"/>
                </div>
            </div>
            <div class="grid gap-1 mb-0 md:grid-cols-8">
                <button 
                    ref="submitButton" 
                    type="submit" 
                    @click.prevent="search"
                    :disabled="filters.loading"
                    class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                    <span v-if="!filters.loading">{{ translate('search') }}</span>
                    <span v-if="filters.loading">
                        {{ translate('pleaseWait') }}...
                    </span>
                </button>
                <button 
                    ref="submitButton" 
                    type="submit" 
                    @click.prevent="exportXLSX"
                    :disabled="filters.xlsxLoading"
                    class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                    <span v-if="!filters.xlsxLoading">{{ translate('export') }}</span>
                    <span v-if="filters.xlsxLoading">
                        {{ translate('pleaseWait') }}...
                    </span>
                </button>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";
import Multiselect from '@vueform/multiselect';
import Datepicker from '@vuepic/vue-datepicker';
import "@vuepic/vue-datepicker/dist/main.css";

import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useStudentStore, type ClassRoom, Parent, StudentForm } from "@/stores/students";
export default defineComponent({
    name: "filter-card",
    components: {
        Multiselect,
        Datepicker
    },
    setup(props, { emit }) {
        const { t, te } = useI18n();
        const i18n = useI18n();
        const classRoomStore = useClassRoomStore();
        const studentStore = useStudentStore();

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

        const filters = ref({
            fromDate: "",
            toDate: "",
            orgId: "",
            classRoomId: "",
            loading: false,
            xlsxLoading: false
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

        onMounted(async () => {
            await fetchOrganizationList();
        });

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
                filters.value.classRoomId = "";
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

        const search = async() => {
            emit('search-attendance', filters);
        }

        const exportXLSX = async() => {
            emit('export-attendance', filters);
        }

        return {
            translate,
            submitButton,
            filters,
            selectOrganization,
            organizationList,
            classRoomList,
            format,
            i18n,
            search,
            exportXLSX
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>