<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <form>
                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                <div class="grid gap-6 mb-3 md:grid-cols-1 mx-5 mt-5">
                                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                                    <Multiselect 
                                        @select="selectOrganization"
                                        v-model="attendanceForm.orgId"
                                        :placeholder="translate('chooseOrganization')"
                                        :close-on-select="true"
                                        :searchable="true" 
                                        :options="organizationList" />
                                        <ErrorLabel v-if="formErrors.org_id" :error="formErrors.org_id"></ErrorLabel>
                                </div>
                                <div class="grid gap-6 mb-3 md:grid-cols-1 mx-5">
                                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                                    <Multiselect
                                        @select="selectClassRoom"
                                        v-model="attendanceForm.classRoomId"
                                        :placeholder="translate('chooseClassRoom')"
                                        :close-on-select="true"
                                        :searchable="true" 
                                        :options="classRoomList" />
                                        <ErrorLabel v-if="formErrors.class_room_id" :error="formErrors.class_room_id"></ErrorLabel>
                                </div>
                                <div class="grid gap-6 mb-3 md:grid-cols-1 mx-5">
                                    <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                                    <Multiselect
                                        v-model="attendanceForm.studentId"
                                        :placeholder="translate('chooseStudnet')"
                                        :close-on-select="true"
                                        :searchable="true" 
                                        :options="studentsList" />
                                        <ErrorLabel v-if="formErrors.student_id" :error="formErrors.student_id"></ErrorLabel>
                                </div>
                            </div>
                            <div class="mx-5">
                                <button 
                                    ref="submitButton" 
                                    type="submit" 
                                    @click.prevent="submitAttendance"
                                    :disabled="attendanceForm.loading"
                                    class="inline-block px-6 py-3 font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro text-xs ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                                    <span v-if="!attendanceForm.loading">{{ translate('markAttendance') }}</span>
                                    <span v-if="attendanceForm.loading">
                                        {{ translate('pleaseWait') }}...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="text-center w-full mt-5">
                            <Datatable key="userId" @on-sort="sort" @on-items-select="onItemSelect" :data="tableData" :header="tableHeader"
                                :enable-items-per-page-dropdown="true" :checkbox-enabled="false" checkbox-label="id">
                                <template v-slot:id="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.id }}</a>
                                </template>
                                <template v-slot:first_name="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.first_name }}</a>
                                </template>
                                <template v-slot:last_name="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.last_name }}</a>
                                </template>
                                <template v-slot:date_of_birth="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.date_of_birth }}</a>
                                </template>
                                <template v-slot:age="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.age }}</a>
                                </template>
                                <template v-slot:organization="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.organization.name }}</a>
                                </template>
                                <template v-slot:class_room="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.class_room.name }}</a>
                                </template>
                                <template v-slot:gender="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.gender }}</a>
                                </template>
                                <template v-slot:address="{ row: users }">
                                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.address }}</a>
                                </template>
                                <template v-slot:status="{ row: users }">
                                    <span v-if="users.attendance_status === true" class="bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Marked</span>
                                    <span v-else class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Not Marked</span>
                                </template>
                                <template v-slot:actions="{ row: users }">
                                    <a @click="markAttendance(users.id)" v-if="users.attendance_status === false"
                                        class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                                        <fa icon="check" class="text-purple-700 group-hover:text-white"></fa>
                                    </a>
                                    <span v-else>&nbsp;</span>
                                </template>
                            </Datatable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, ref, computed, onMounted } from "vue";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";
import { useStudentStore, type Student, ClassRoom } from "@/stores/students";
import { useAttendanceStore } from "@/stores/attendance";
import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useGalleryStore, type Student as Students } from "@/stores/gallery";

import Datatable from "@/components/table/Datatable.vue";
import ErrorLabel from "@/components/global/ErrorLabel.vue";
import Multiselect from '@vueform/multiselect';

export default defineComponent({
    name: "attendance-form",
    components: {
        Datatable,
        ErrorLabel,
        Multiselect
    },
    setup(){
        const { t, te } = useI18n();
        const store = useStudentStore();
        const attendanceStore = useAttendanceStore();
        const classRoomStore = useClassRoomStore();
        const galleryStore = useGalleryStore();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const attendanceForm = ref({
            orgId: "",
            classRoomId: "",
            studentId: "",
            loading: false,
        });

        const selectedIds = ref<Array<number>>([]);
        const studentList = ref<Array<Student>>([]);
        const tableData = ref<Array<Student>>([]);

        const organizationList = ref<Array<Organization>>([]);
        const classRoomList = ref<Array<ClassRoom>>([]);
        const studentsList = ref<Array<Students>>([]);

        const formErrors = ref<any>({
            org_id: "",
            class_room_id: "",
            student_id: "",
        });

        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("firstName") }),
                columnLabel: "first_name",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("lastName") }),
                columnLabel: "last_name",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("dateOfBirth") }),
                columnLabel: "date_of_birth",
                sortEnabled: true,
                columnWidth: 30,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("age") }),
                columnLabel: "age",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("organization") }),
                columnLabel: "organization",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("classRoom") }),
                columnLabel: "class_room",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("gender") }),
                columnLabel: "gender",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: computed(()=> { return translate("address") }),
                columnLabel: "address",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("status") }),
                columnLabel: "status",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
            },
            {
                columnName: computed(()=> { return translate("actions") }),
                columnLabel: "actions",
                sortEnabled: false,
                columnWidth: 50,
                textAlign: "text-center",
            },
        ]);

        onMounted(async () => {
            await fetchOrganizationList();
            await fetchStudentList();
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
                attendanceForm.value.classRoomId = "";
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

        const selectClassRoom = async (selectClassRoom) => {
            const inputs = {
                organizationId: attendanceForm.value.orgId,
                classRoomId: selectClassRoom,
            };
            await galleryStore.lookupClassRoomStudents(inputs);
            const error = Object.values(galleryStore.errors);
            if (error.length === 0) {
                studentsList.value.splice(0, studentsList.value.length, ...galleryStore.studentList);
                attendanceForm.value.studentId = "";
            } else {
                attendanceForm.value.studentId = "";
                Swal.fire({
                title: 'Oops...',
                text: error[0] as string,
                icon: 'error',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Try again!'
                }).then((result) => {
                    galleryStore.errors = {};
                })
            }
        }

        const submitAttendance = async () => {
            await markAttendance(attendanceForm.value.studentId);
        }

        const fetchStudentList = async () => {
            await store.fetchAllStudentList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                studentList.value.splice(0, studentList.value.length, ...store.studentList);
                tableData.value.splice(0, tableData.value.length, ...store.studentList);
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

        const search = ref<string>("");
        const searchItems = () => {
            tableData.value.splice(0, tableData.value.length, ...studentList.value);
            if (search.value !== "") {
                const regex = new RegExp(search.value, 'i');
                let results = studentList.value.filter((item) => {
                    for (const key in item) {
                        if (Object.prototype.hasOwnProperty.call(item, key)) {
                            if (regex.test(item[key])) {
                                return true;
                            }
                        }
                    }
                    return false;
                });
                tableData.value.splice(0, tableData.value.length, ...results);
            }
        };

        const sort = (sort: Sort) => {
            const reverse: boolean = sort.order === "asc";
            if (sort.label) {
                arraySort(tableData.value, sort.label, { reverse });
            }
        };
        const onItemSelect = (selectedItems: Array<number>) => {
            selectedIds.value = selectedItems;
        };

        const markAttendance = async (studentId) => {
            await Swal.fire({
                title: "Are you sure?",
                text: "Do you want to mark your attendance!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, mark it!",
            }).then(async(result: any) => {
                if (result.isConfirmed) {
                    const inputs = {
                        studentId: studentId,
                    };
                    let response = await attendanceStore.markStudentAttendance(inputs);
                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        Swal.fire({
                            title: 'Good job!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok, got it!'
                        }).then(async() => {
                            await fetchStudentList();
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
                        })
                    }
                }
            });
        }
        
        return {
            translate,
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
            search,
            markAttendance,
            attendanceForm,
            organizationList,
            formErrors,
            selectOrganization,
            classRoomList,
            selectClassRoom,
            studentsList,
            submitAttendance
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>