<template>
    <div class="mt-10 mb-10">
        <div class="flex flex-col pb-10">
            <div class="mb-10">
                <h3>Principal</h3>
            </div>

            <div class="mb-6 md:flex md:items-center">
                <div class="md:w-1/6">
                    <label
                        class="block pr-4 mb-1 font-bold text-gray-500 md:text-right md:mb-0"
                    >
                        Name
                    </label>
                </div>
                <div class="md:w-2/3">
                    <KGMS_ErrorLable :errors='errors.pName'></KGMS_ErrorLable>
                    <input
                        v-model="form.pName"
                        @input="setFormData"
                        class="w-full px-4 py-2 leading-tight text-gray-700 bg-gray-200 border-2 border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-purple-500"
                        type="text"
                        placeholder="Enter organization name"
                    />
                </div>
            </div>

            <div class="mb-6 md:flex md:items-center">
                <div class="md:w-1/6">
                    <label
                        class="block pr-4 mb-1 font-bold text-gray-500 md:text-right md:mb-0"
                    >
                        Contact No
                    </label>
                </div>
                <div class="md:w-2/3">
                    <KGMS_ErrorLable :errors='errors.pContact'></KGMS_ErrorLable>
                    <input
                        v-model="form.pContact"
                        @input="setFormData"
                        class="w-full px-4 py-2 leading-tight text-gray-700 bg-gray-200 border-2 border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-purple-500"
                        type="text"
                        placeholder="Enter organization contact number"
                    />
                </div>
            </div>

            <div class="mb-6 md:flex md:items-center">
                <div class="md:w-1/6">
                    <label
                        class="block pr-4 mb-1 font-bold text-gray-500 md:text-right md:mb-0"
                    >
                        Email
                    </label>
                </div>
                <div class="md:w-2/3">
                    <KGMS_ErrorLable :errors='errors.pEmail'></KGMS_ErrorLable>
                    <input
                        v-model="form.pEmail"
                        @input="setFormData"
                        class="w-full px-4 py-2 leading-tight text-gray-700 bg-gray-200 border-2 border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-purple-500"
                        type="text"
                        placeholder="Enter organization contact number"
                    />
                </div>
            </div>

            <div class="mb-6 md:flex md:items-center">
                <div class="md:w-1/6">
                    <label
                        class="block pr-4 mb-1 font-bold text-gray-500 md:text-right md:mb-0"
                    >
                        Password
                    </label>
                </div>
                <div class="md:w-2/3">
                    <KGMS_ErrorLable :errors='errors.pPassword'></KGMS_ErrorLable>
                    <input
                        v-model="form.pPassword"
                        @input="setFormData"
                        class="w-full px-4 py-2 leading-tight text-gray-700 bg-gray-200 border-2 border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-purple-500"
                        type="text"
                        placeholder="Enter organization contact number"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss"></style>

<script lang="ts">
import { defineComponent, reactive, onMounted } from "vue";
import { useOrganizationFormDataStore } from "@/stores/organizationFormData";
import KGMS_ErrorLable from "@/components/global/KGMS_ErrorLabel.vue";

export default defineComponent({
    name: "KGMS_Step2Principal",
    components: {
        KGMS_ErrorLable
    },
    setup(props, { emit }) {

        const store = useOrganizationFormDataStore();

        const errors = store.formDataErrors;

        const form = reactive({
            pName: "",
            pContact: "",
            pEmail: "",
            pPassword: "",
        });

        const setFormData = () => {
            filterPhoneNumber();
            emit("send-data", form);
        };

        const filterPhoneNumber = () => {
            form.pContact = form.pContact.replace(/\D/g, '');

            if (form.pContact.length > 25) {
                form.pContact = form.pContact.substring(0, 25);
            }
            if (form.pContact.length < 10) {
                form.pContact = form.pContact.substring(0, 10);
            }
        }

        onMounted(() => {

            // Set existing values
            form.pName = store.formData.pName;
            form.pContact =  ['NaN', 0, '0'].includes(store.formData.pContact.toString()) ? '' : store.formData.pContact.toString();
            form.pEmail = store.formData.pEmail;
            form.pPassword = store.formData.pPassword;
            
        
        });

        return {
            setFormData,
            form,
            errors
        };
    },
});
</script>
