<template>
    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <div class="flex flex-wrap -mx-3">
            <div class="flex items-center flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 sm:mb-0">
                <h6 class="mb-0 text-sub-header">{{ translate('eventCalendar') }}</h6>
            </div>
            <div class="flex-none w-full sm:w-1/2 max-w-full px-3 mb-2 flex items-center justify-end">
                <router-link v-if="isPermittedRoute('add-event')" to="/add-event"
                    class="inline-block px-4 py-3 text-center font-bold text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-sm ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-poppins">
                    <fa icon="plus" />&nbsp;&nbsp;{{ translate('addEvent') }}
                </router-link>
            </div>
        </div>
        <FullCalendar :options="calendarOptions" />
    </div>
    <!-- <div>
        <button @click="toggleWeekends">Toggle Weekends</button>
        <FullCalendar :options="calendarOptions" />
    </div> -->
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useI18n } from "vue-i18n";
import { useAuthStore } from "@/stores/auth";
import { useEventStore, type Event } from "@/stores/event";
import Swal from "sweetalert2/dist/sweetalert2.js";

import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

export default defineComponent({
    name: "students-list",
    components: {
        FullCalendar
    },
    setup(){
        const store = useEventStore();
        const authStore = useAuthStore();
        const { t, te } = useI18n();
        const i18n = useI18n();

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

        const calendarOptions = ref<{
            plugins: any[];
            initialView: string;
            events: { title: string; date: string }[];
            locale: string;
        }>({
            plugins: [dayGridPlugin, interactionPlugin],
            initialView: 'dayGridMonth',
            events: [],
            locale: i18n.locale.value
        });

        onMounted(async () => {
            await fetchEventList();
        });

        const fetchEventList = async() => {
            await store.fetchEventList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                calendarOptions.value.events = store.eventList.map(event => ({
                    title: event.description,
                    date: event.event_date
                }));
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

        const toggleWeekends = () => {
            calendarOptions.value.weekends = !calendarOptions.value.weekends;
        };

        i18n.locale.value = localStorage.getItem("lang")
        ? (localStorage.getItem("lang") as string)
        : "en";

        return {
            translate,
            isPermittedRoute,
            calendarOptions,
            toggleWeekends,
            i18n
        }
    }
});
</script>