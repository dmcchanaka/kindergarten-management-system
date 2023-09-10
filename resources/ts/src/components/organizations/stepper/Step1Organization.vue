<template>
    <div class="mt-10 mb-10">
        <div class="flex flex-col pb-10">
            <div class="mb-10">
                <h3>Organization Details</h3>
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
                    <KGMS_ErrorLable :errors='errors.oName'></KGMS_ErrorLable>
                    <input
                        v-model="form.oName"
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
                        Address
                    </label>
                </div>
                <div class="md:w-2/3">
                    <KGMS_ErrorLable :errors='errors.oAddress'></KGMS_ErrorLable>
                    <input
                        v-model="form.oAddress"
                        @input="setFormData"
                        class="w-full px-4 py-2 leading-tight text-gray-700 bg-gray-200 border-2 border-gray-200 rounded appearance-none focus:outline-none focus:bg-white focus:border-purple-500"
                        type="text"
                        placeholder="Enter organization address"
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
                    <KGMS_ErrorLable :errors='errors.oContact'></KGMS_ErrorLable>
                    <input
                        v-model="form.oContact"
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
                    <KGMS_ErrorLable :errors='errors.oEmail'></KGMS_ErrorLable>
                    <input
                        v-model="form.oEmail"
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
    name: "KGMS_Step1Organization",
    props: {
    },
    components:{
        KGMS_ErrorLable
    },
    setup(props, { emit }) {

        const store = useOrganizationFormDataStore();

        const errors = store.formDataErrors;

        const form = reactive({
            oName: "",
            oAddress: "",
            oContact: "",
            oEmail: "",
        });

        const setFormData = () => {
            filterPhoneNumber();
            emit("send-data", form);
        };

        const filterPhoneNumber = () => {
            form.oContact = form.oContact.replace(/\D/g, '');

            if (form.oContact.length > 25) {
                form.oContact = form.oContact.substring(0, 25);
            }
            if (form.oContact.length < 10) {
                form.oContact = form.oContact.substring(0, 10);
            }
        }

        onMounted(() => {

            // Set existing values
            form.oName = store.formData.oName;
            form.oAddress = store.formData.oAddress;
            form.oContact =  ['NaN', 0, '0'].includes(store.formData.oContact.toString()) ? '' : store.formData.oContact.toString();
            form.oEmail = store.formData.oEmail;
            
        
        });


        return {
            setFormData,
            form,
            errors
        };
    },
});
</script>
