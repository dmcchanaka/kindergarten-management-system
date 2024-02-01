<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                <h6 class="mb-0">{{ translate('attendanceList') }}</h6>
            </div>
            <div class="flex-none w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <input type="text" v-model="search" @input="searchItems()"
                    class="flex-grow border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    :placeholder="translate('searchAttendace')" />
            </div>
        </div>
        <Datatable key="userId" @on-sort="sort" @on-items-select="onItemSelect" :data="tableData" :header="tableHeader"
            :enable-items-per-page-dropdown="true" :checkbox-enabled="false" checkbox-label="id">
            <template v-slot:id="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.id }}</a>
            </template>
            <template v-slot:date="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.date }}</a>
            </template>
            <template v-slot:time="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.time }}</a>
            </template>
            <template v-slot:name="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.student.name }}</a>
            </template>
            <template v-slot:organization="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.organization.name }}</a>
            </template>
            <template v-slot:class_room="{ row: att }">
                <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ att.classRoom.name }}</a>
            </template>
            <template v-slot:status="{ row: att }">
                <span v-if="att.approve_status === true" class="bg-gradient-to-tl from-green-600 to-lime-400 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ translate('approved') }}</span>
                <span v-else class="bg-gradient-to-tl from-slate-600 to-slate-300 px-2.5 text-xs rounded-1.8 py-1.4 inline-block whitespace-nowrap text-center align-baseline font-bold uppercase leading-none text-white">{{ translate('notYetApproved') }}</span>
            </template>
            <template v-slot:actions="{ row: att }">
                <a @click="approveAttendance(att.id)" v-if="att.approve_status === false"
                    class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                    <fa icon="user-check" class="text-purple-700 group-hover:text-white"></fa>
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

import { useAttendanceStore, type Attendance } from "@/stores/attendance";
import Datatable from "@/components/table/Datatable.vue";

export default defineComponent({
    name: "attendance-list",
    components: {
        Datatable
    },
    setup(){
        const { t, te } = useI18n();
        const store = useAttendanceStore();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const selectedIds = ref<Array<number>>([]);
        const attendanceList = ref<Array<Attendance>>([]);
        const tableData = ref<Array<Attendance>>([]);

            const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("date") }),
                columnLabel: "date",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("time") }),
                columnLabel: "time",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("name") }),
                columnLabel: "name",
                sortEnabled: true,
                columnWidth: 100,
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
            await fetchAttendanceList();
        });

        const fetchAttendanceList = async () => {
            await store.fetchAttendanceList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                attendanceList.value.splice(0, attendanceList.value.length, ...store.studentAttendanceList);
                tableData.value.splice(0, tableData.value.length, ...store.studentAttendanceList);
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

        const search = ref<string>("");
        const searchItems = () => {
            tableData.value.splice(0, tableData.value.length, ...attendanceList.value);

            if (search.value !== "") {
                const regex = new RegExp(search.value, 'i');
                let results = attendanceList.value.filter((item) => {
                    // Iterate through each property of the item
                    for (const key in item) {
                        if (Object.prototype.hasOwnProperty.call(item, key)) {
                            const value = item[key];

                            // Check if the value is an object (nested structure)
                            if (typeof value === 'object') {
                                // If it's an object, iterate through its properties
                                for (const subKey in value) {
                                    if (Object.prototype.hasOwnProperty.call(value, subKey)) {
                                        // Check if the nested value matches the search regex
                                        if (regex.test(value[subKey])) {
                                            return true;
                                        }
                                    }
                                }
                            } else {
                                // Check if the value matches the search regex
                                if (regex.test(value)) {
                                    return true;
                                }
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

        const approveAttendance = async (attId) => {
            await Swal.fire({
                title: "Are you sure?",
                text: "Do you want to approve this attendance!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, mark it!",
            }).then(async(result: any) => {
                if (result.isConfirmed) {
                    const inputs = {
                        attId: attId,
                    };
                    let response = await store.approveStudentAttendance(inputs);
                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        Swal.fire({
                            title: translate('goodJob') + '!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: translate('okGotIt') + '!'
                        }).then(async() => {
                            await fetchAttendanceList();
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
            approveAttendance
        }
    }
});
</script>