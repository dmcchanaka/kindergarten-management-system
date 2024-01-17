<template>
    <div
        class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                <h6 class="mb-0">{{ translate('studentsList') }}</h6>
            </div>
            <div class="flex-none w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <input type="text" v-model="search" @input="searchItems()"
                    class="flex-grow border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    :placeholder="translate('searchStudents')" />
                <router-link v-if="isPermittedRoute('add-student')" to="/add-student"
                    class="ml-3 inline-block px-6 py-3 text-xs font-bold text-center text-white uppercase align-middle transition-all bg-transparent rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-gradient-to-tl from-gray-900 to-slate-800 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25">
                    <fa icon="plus" />&nbsp;&nbsp;{{ translate('addStudent') }}
                </router-link>
            </div>
        </div>
        <Datatable key="userId" @on-sort="sort" @on-items-select="onItemSelect" :data="tableData" :header="tableHeader"
            :enable-items-per-page-dropdown="true" :checkbox-enabled="false" checkbox-label="id">
            <template v-slot:id="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.id }}</a>
            </template>
            <template v-slot:image_url="{ row: users }">
                <img :src="users.image_url" class="inline-flex items-center justify-center mr-4 text-sm text-white transition-all duration-200 ease-soft-in-out h-9 w-9 rounded-xl" alt="xd" />
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
            <template v-slot:guardian="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.guardian.name }}</a>
            </template>
            <template v-slot:gender="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.gender }}</a>
            </template>
            <template v-slot:address="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.address }}</a>
            </template>
            <template v-slot:special_notice="{ row: users }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ users.special_notice }}</a>
            </template>
            <template v-slot:actions="{ row: users }">
                <a v-if="isPermittedRoute('edit-student')" @click="editStudent(users.id)"
                    class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                </a>
                <span v-else>&nbsp;</span>
                <a v-if="isPermittedRoute('delete-student')" @click="deleteStudent(users.id)"
                    class="cursor-pointer text-red-700 border border-red-700 hover:bg-red-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:focus:ring-red-800 dark:hover:bg-red-500 group">
                    <fa icon="trash-can" class="text-red-700 group-hover:text-white"></fa>
                </a>
                <span v-else>&nbsp;</span>
            </template>
        </Datatable>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";
import arraySort from "array-sort";

import { useStudentStore, type Student } from "@/stores/students";
import Datatable from "@/components/table/Datatable.vue";
export default defineComponent({
    name: "students-list",
    components: {
        Datatable
    },
    setup() {
        const { t, te } = useI18n();
        const store = useStudentStore();
        const authStore = useAuthStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const selectedIds = ref<Array<number>>([]);
        const studentList = ref<Array<Student>>([]);
        const tableData = ref<Array<Student>>([]);

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
                columnWidth: 100,
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
                columnName: computed(()=> { return translate("guardian") }),
                columnLabel: "guardian",
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
                columnName: computed(()=> { return translate("specialNotice") }),
                columnLabel: "special_notice",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
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
            await store.fetchStudentList();
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

        const isPermittedRoute = (currentRoute) => {
            if (authStore.userPermissions.length > 0) {
                return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }

        //edit student details
        const editStudent = (studentId) => {
            store.saveStudentId(studentId);
            router.push({ name: "edit-student" });
        }

        //delete student details
        const deleteStudent = async(studentId) => {
            await Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then(async(result: any) => {
                if (result.isConfirmed) {
                    const inputs = {
                        studentId: studentId,
                    };
                    let response = await store.removeStudent(inputs);
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
            isPermittedRoute,
            tableData,
            tableHeader,
            searchItems,
            sort,
            onItemSelect,
            search,
            editStudent,
            deleteStudent
        }
    }
});
</script>