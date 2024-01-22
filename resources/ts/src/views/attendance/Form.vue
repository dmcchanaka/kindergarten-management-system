<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-12/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="text-center w-full mt-5">
                            <div class="flex flex-wrap">
                                <div class="w-full max-w-full px-3 mb-2">
                                    <div class="relative flex flex-col min-w-0 break-words bg-white shadow-soft-xl rounded-2xl bg-clip-border mb-2" v-for="(item, index) in tableData">
                                        <div class="flex-auto p-1">
                                            <div class="flex flex-wrap -mx-3">
                                                <div class="flex flex-col justify-center pl-5">
                                                    <img :src="item.image_url" class="inline-flex items-center content-center text-sm transition-all duration-200 ease-soft-in-out h-12 w-12 rounded-full my-2" alt="xd" />
                                                </div>
                                                <div class="flex flex-col justify-center items-center w-full sm:w-1/2 md:w-4/12 lg:w-4/12">
                                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ item.first_name }} {{ item.last_name }}</p>
                                                </div>
                                                <div class="flex flex-col justify-center items-center w-full sm:w-1/2 md:w-2/12 lg:w-2/12">
                                                    <p class="mb-0 font-sans text-sm font-semibold leading-normal">{{ item.class_room.name }}</p>
                                                </div>
                                                <div class="flex flex-col justify-center items-center w-full sm:w-1/2 md:w-2/12 lg:w-2/12">
                                                    <span v-if="item.attendance_status === true" class="bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Anwesend</span>
                                                    <span v-else class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">Abwesend</span>
                                                </div>
                                                <div class="flex flex-col justify-center items-center w-full md:w-3/12 lg:w-3/12 text-right">
                                                    <a @click="markAttendance(item.id)" v-if="item.attendance_status === false"
                                                        class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                                                        <fa icon="check" class="text-purple-700 group-hover:text-white"></fa>
                                                    </a>
                                                    <span v-else>&nbsp;</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                columnName: computed(()=> { return translate("image") }),
                columnLabel: "image_url",
                sortEnabled: true,
                columnWidth: 10,
                textAlign: "text-center",
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
            await fetchStudentList();
        });

        const fetchStudentList = async () => {
            await store.fetchAllStudentList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                studentList.value.splice(0, studentList.value.length, ...store.studentList);
                tableData.value.splice(0, tableData.value.length, ...store.studentList);
            } else {
                Swal.fire({
                    title: 'Oops...',
                    text: translate(error[0] as string),
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Erneut versuchen!'
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
                title: "Bist du sicher?",
                text: "Willst Du deine Anwesenheit markieren!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ja, bestätigen!",
            }).then(async(result: any) => {
                if (result.isConfirmed) {
                    const inputs = {
                        student_id: studentId,
                    };
                    let response = await attendanceStore.markStudentAttendance(inputs);
                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        Swal.fire({
                            title: 'Gute Arbeit!',
                            text: translate(response.message),
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK, verstanden!'
                        }).then(async() => {
                            await fetchStudentList();
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
            markAttendance
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>