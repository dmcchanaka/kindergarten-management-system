<template>
    <div class="lg:flex lg:items-center lg:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">Edit New Organization</h2>
            <div class="flex flex-col mt-1 sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
            </div>
        </div>
    </div>

    <div class="w-full p-4 mt-5 text-center bg-white border border-gray-200 rounded-lg shadow sm:p-8 dark:bg-gray-800 dark:border-gray-700">
        <KGMS_Stepper></KGMS_Stepper>
    </div>
    
</template>

<style lang="scss"></style>

<script lang="ts">
import { defineComponent } from "vue";
import KGMS_Stepper from "@/components/organizations/stepper/Stepper.vue";

export default defineComponent({
    name: "KGMS_OrganizationEdit",
    components: {
        KGMS_Stepper,
    },
    setup() {},
});
</script>