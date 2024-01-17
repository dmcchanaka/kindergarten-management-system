<template>
  <div
    class="w-full p-4 mt-5 relative flex flex-col flex-auto min-w-0 p-4 mx-0 overflow-hidden break-words border-0 shadow-blur rounded-2xl bg-white/80 bg-clip-border backdrop-blur-2xl backdrop-saturate-200">
    <!-- <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700"> -->
    <div class="flex flex-wrap -mx-3">
      <div class="flex-none w-auto max-w-full px-3">
        <div
          class="text-base ease-soft-in-out h-18.5 w-18.5 relative inline-flex items-center justify-center rounded-xl text-white transition-all duration-200">
          <div
            class="bg-gradient-to-tl from-purple-700 to-blue-500 shadow-soft-2xl mr-2 flex h-12 w-12 items-center justify-center rounded-lg bg-white bg-center stroke-0 text-center xl:p-2.5">
            <fa icon="school" class="h-7" />
          </div>
        </div>
      </div>
      <div class="flex-none w-auto max-w-full px-0 my-auto">
        <div class="h-full">
          <h5 class="mb-1 text-header">{{ currentOrganization }}</h5>
          <p class="mb-0 font-semibold leading-normal text-sm">{{ userRole }}</p>
        </div>
      </div>
    </div>
  </div>
  <div class="flex flex-wrap my-6 -mx-3" v-if="userRoleId == 3">
    <div class="w-full max-w-full px-3 mt-0 mb-6 md:mb-0 md:flex-none lg:flex-none">
      <div
        class="border-black/12.5 shadow-soft-xl relative flex min-w-0 flex-col break-words rounded-2xl border-0 border-solid bg-white bg-clip-border">
        <div class="border-black/12.5 mb-0 rounded-t-2xl border-b-0 border-solid bg-white p-6 pb-0">
          <div class="flex flex-wrap mt-0 -mx-3">
            <div class="flex-none w-7/12 max-w-full px-3 mt-0 lg:w-1/2 lg:flex-none">
              <h6>Class Room Students</h6>
            </div>
            <div class="flex-none w-5/12 max-w-full px-3 my-auto text-right lg:w-1/2 lg:flex-none">
              <div class="relative pr-6 lg:float-right">
              </div>
            </div>
          </div>
        </div>
        <div class="flex-auto p-6 px-0 pb-2">
          <div class="overflow-x-auto">
            <table class="items-center w-full mb-0 align-top border-gray-200 text-slate-500">
              <thead class="align-bottom">
                <tr>
                  <th
                    class="px-6 py-3 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("firstName") }}</th>
                  <th
                    class="px-6 py-3 pl-2 font-bold tracking-normal text-left uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("lastName") }}</th>
                  <th
                    class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("dateOfBirth") }}</th>
                  <th
                    class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("age") }}</th>
                  <th
                    class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("classRoom") }}</th>
                  <th
                    class="px-6 py-3 font-bold tracking-normal text-center uppercase align-middle bg-transparent border-b letter border-b-solid text-xxs whitespace-nowrap border-b-gray-200 text-slate-400 opacity-70">
                    {{ translate("organization") }}</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in studentList" :key="index">
                  <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                    <div class="flex px-2 py-1">
                      <div class="flex flex-col justify-center">
                        <h6 class="mb-0 text-sm leading-normal">{{ item.first_name }}</h6>
                      </div>
                    </div>
                  </td>
                  <td class="p-2 align-middle bg-transparent border-b whitespace-nowrap">
                    <div class="flex px-2 py-1">
                      <div class="flex flex-col justify-center">
                        <h6 class="mb-0 text-sm leading-normal">{{ item.last_name }}</h6>
                      </div>
                    </div>
                  </td>
                  <td
                    class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                    <span class="text-xs font-semibold leading-tight"> {{ item.date_of_birth }}</span>
                  </td>
                  <td
                    class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                    <span class="text-xs font-semibold leading-tight"> {{ item.age }}</span>
                  </td>
                  <td
                    class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                    <span class="text-xs font-semibold leading-tight"> {{ item.class_room.name }}</span>
                  </td>
                  <td
                    class="p-2 text-sm leading-normal text-center align-middle bg-transparent border-b whitespace-nowrap">
                    <span class="text-xs font-semibold leading-tight"> {{ item.organization.name }}</span>
                  </td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent, computed, onMounted, ref } from "vue";
import { getAssetPath } from "@/core/helpers/assets";
import { useAuthStore } from "@/stores/auth";
import { useStudentStore, type Student } from "@/stores/students";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "dashboard",
  setup() {
    const store = useAuthStore();
    const studentStore = useStudentStore();
    const { t, te } = useI18n();

    const translate = (text: string) => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    const studentList = ref<Array<Student>>([]);

    const currentOrganization = computed(() => {
      const organization = store.organization;
      return typeof organization?.name != 'undefined' ? organization?.name : "-";
    });

    const userRole = computed(() => {
      const user = store.user;
      return typeof user?.userRole != 'undefined' ? user?.userRole : "-";
    });

    const userRoleId  = computed(() => {
      const user = store.user;
      return typeof user?.userAccessLevel != 'undefined' ? user?.userAccessLevel : 0;  
    });

    onMounted(() => {
      fetchClassRoomStudents();
    });

    const fetchClassRoomStudents = async() => {
      await studentStore.fetchStudentList();
            const error = Object.values(store.errors);
            if (error.length === 0) {
                studentList.value.splice(0, studentList.value.length, ...studentStore.studentList);
            } else {
              studentList.value = [];
            }
    }

    return {
      getAssetPath,
      currentOrganization,
      userRole,
      userRoleId,
      translate,
      studentList
    }
  },
});
</script>