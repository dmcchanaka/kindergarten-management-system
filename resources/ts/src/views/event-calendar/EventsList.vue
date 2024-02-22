<template>
    <div
        class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 sm:mb-0">
                <h6 class="mb-0 text-sub-header">{{ translate('eventList') }}</h6>
            </div>
            <div class="flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <input type="text" v-model="search" @input="searchItems()"
                    class="flex-grow max-w-xs min-w-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    :placeholder="translate('searchEvent')" />
                <router-link v-if="isPermittedRoute('add-event')" to="/add-event"
                    class="ml-3 inline-block px-6 py-3 text-lg text-center text-white uppercase align-middle rounded-lg cursor-pointer leading-pro ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                    <fa icon="plus" />&nbsp;&nbsp;{{ translate('addEvent') }}
                </router-link>
            </div>
        </div>
        <Datatable
            key="id"
            @on-sort="sort"
            @on-items-select="onItemSelect"
            :data="tableData"
            :header="tableHeader"
            :enable-items-per-page-dropdown="true"
            :checkbox-enabled="false"
            checkbox-label="id"
        >
        <template v-slot:id="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.id }}</a>
                </template>
                <template v-slot:description="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.description }}</a>
                </template>
                <template v-slot:event_date="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.event_date }}</a>
                </template>
                <template v-slot:organization="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.organization.name }}</a>
                </template>
                <template v-slot:classRoom="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.class_room.name }}</a>
                </template>
                <template v-slot:created_at="{ row: event }">
                    <a class="mb-1 text-gray-800 fw-bold text-hover-primary fs-6">{{ event.added_date }}</a>
                </template>
                <template v-slot:actions="{ row: event }">
                    <a v-if="isPermittedRoute('edit-event')" @click="editEvent(event.id)"
                        class="cursor-pointer mr-2 text-purple-700 border border-purple-700 hover:bg-purple-700 hover:text-white focus:ring-4 focus:outline-none focus:ring-purple-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center dark:border-purple-500 dark:text-purple-500 dark:hover:text-white dark:focus:ring-purple-800 dark:hover:bg-purple-500 group">
                        <fa icon="pen-to-square" class="text-purple-700 group-hover:text-white"></fa>
                    </a>
                    <span v-else>&nbsp;</span>
                    <a v-if="isPermittedRoute('delete-event')" @click="deleteEvent(event.id)"
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
import { useEventStore, type Event } from "@/stores/event";

import Datatable from "@/components/table/Datatable.vue";

export default defineComponent({
    name: "events-list",
    components: {
        Datatable
    },
    setup(){
        const { t, te } = useI18n();
        const store = useEventStore();
        const authStore = useAuthStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const isPermittedRoute = (currentRoute) => {
            if (authStore.userPermissions.length > 0) {
                return authStore.userPermissions.some(permission => permission.name === currentRoute);
            }
        }

        const selectedIds = ref<Array<number>>([]);
        const eventList = ref<Array<Event>>([]);
        const tableData = ref<Array<Event>>([]);

        const tableHeader = ref([
            {
                columnName: "#",
                columnLabel: "id",
                sortEnabled: true,
                columnWidth: 20,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=> { return translate("description") }),
                columnLabel: "description",
                sortEnabled: true,
                columnWidth: 250,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('date') }),
                columnLabel: "event_date",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-center",
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
                columnLabel: "classRoom",
                sortEnabled: true,
                columnWidth: 100,
                textAlign: "text-left",
            },
            {
                columnName: computed(()=>{ return translate('registeredAt') }),
                columnLabel: "created_at",
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
            await fetchEventList();
        });

        const fetchEventList = async() => {
            await store.fetchEventList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                eventList.value.splice(0, eventList.value.length, ...store.eventList);
                tableData.value.splice(0, tableData.value.length, ...store.eventList);
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
            tableData.value.splice(0, tableData.value.length, ...eventList.value);
            if (search.value !== "") {
            let results: Array<Event> = [];
            for (let j = 0; j < tableData.value.length; j++) {
                if (searchingFunc(tableData.value[j], search.value.toLowerCase())) {
                results.push(tableData.value[j]);
                }
            }
            tableData.value.splice(0, tableData.value.length, ...results);
            }
        };
    
        const searchingFunc = (obj: any, value: string): boolean => {
            for (let key in obj) {
            if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
                if (obj[key].indexOf(value) != -1) {
                return true;
                }
            }
            }
            return false;
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

        const editEvent = async(eventId) => {
            store.saveEventId(eventId);
            router.push({ name: "edit-event" });
        }

        const deleteEvent = async(eventId) => {
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
                        eventId: eventId,
                    };
                    let response = await store.removeEvent(inputs);
                    const error = Object.values(store.errors);
                    if (error.length === 0) {
                        Swal.fire({
                            title: 'Good job!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Ok, got it!'
                        }).then(async() => {
                            await fetchEventList();
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
            onItemSelect,
            sort,
            editEvent,
            deleteEvent,
            search,
            searchItems
        }
    }
});
</script>