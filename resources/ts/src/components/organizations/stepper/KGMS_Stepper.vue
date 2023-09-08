<template>
    <div class="wrapper-stepper">
        <div class="mx-auto stepper">
            <div class="stepper-progress">
                <div
                    class="stepper-progress-bar"
                    :style="'width:' + stepperProgress"
                ></div>
            </div>

            <div
                class="stepper-item"
                :class="{ current: step == item, success: step > item }"
                v-for="item in totalSteps"
                :key="item"
            >
                <div class="stepper-item-counter">
                    <img
                        class="icon-success"
                        src="https://www.seekpng.com/png/full/1-10353_check-mark-green-png-green-check-mark-svg.png"
                        alt=""
                    />
                    <span class="number">
                        {{ item }}
                    </span>
                </div>
                <span class="stepper-item-title"> Step {{ item }} </span>
            </div>
        </div>

        <KGMS_Step1Organization
            v-if="step == 1"
            @send-data="handleOrganizationData"
        ></KGMS_Step1Organization>
        <KGMS_Step2Principal
            v-if="step == 2"
            @send-data="handlePrincipalData"
        ></KGMS_Step2Principal>

        <div class="controls">
            <button class="btn" @click="prevStep" :disabled="step == 1">
                Previous
            </button>
            <button
                class="btn btn--green-1"
                @click="step == totalSteps ? createOrganization() : nextStep()"
            >
                {{ step == totalSteps ? "Finish" : "Next" }}
            </button>
            <!-- <button
                class="btn btn--green-1"
                @click="nextStep"
                :disabled="step == totalSteps"
            >
                {{ step == totalSteps ? 'Finish' : 'Next' }}
            </button> -->
        </div>
    </div>
</template>

<style lang="scss">
$default: #c5c5c5;
$green-1: #75cc65;
$transiton: all 500ms ease;

.tx-green-1 {
    color: $green-1;
    font-weight: 600;
}

.wrapper-stepper {
    background-color: #fff;
    padding: 60px;
    border-radius: 32px;
    box-shadow: rgba($color: #000000, $alpha: 0.09);
}

.stepper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 660px;
    position: relative;
    z-index: 0;
    margin-bottom: 24px;

    &-progress {
        position: absolute;
        background-color: $default;
        height: 2px;
        z-index: -1;
        left: 0;
        right: 0;
        margin: 0 auto;

        &-bar {
            position: absolute;
            left: 0;
            height: 100%;
            width: 0%;
            background-color: $green-1;
            transition: $transiton;
        }
    }
}

.stepper-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: $default;
    transition: $transiton;

    &-counter {
        height: 68px;
        width: 68px;
        display: grid;
        place-items: center;
        background-color: #fff;
        border-radius: 100%;
        border: 2px solid $default;
        position: relative;

        .icon-success {
            position: absolute;
            opacity: 0;
            transform: scale(0);
            width: 24px;
            transition: $transiton;
        }

        .number {
            font-size: 22px;
            transition: $transiton;
        }
    }

    &-title {
        position: absolute;
        font-size: 14px;
        bottom: -24px;
    }
}

.stepper-item.success {
    .stepper-item-counter {
        border-color: $green-1;
        background-color: #c8ebc1;
        color: #fff;
        font-weight: 600;

        .icon-success {
            opacity: 1;
            transform: scale(1);
        }

        .number {
            opacity: 0;
            transform: scale(0);
        }
    }

    .stepper-item-title {
        color: $green-1;
    }
}

.stepper-item.current {
    .stepper-item-counter {
        border-color: $green-1;
        background-color: $green-1;
        color: #fff;
        font-weight: 600;
    }

    .stepper-item-title {
        color: #818181;
    }
}

.stepper-pane {
    color: #333;
    text-align: center;
    padding: 120px 60px;
    //box-shadow: 0 8px 12px rgba($color: #000000, $alpha: 0.09);
    margin: 40px 0;
}

.controls {
    display: flex;
}

.btn {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 6px 16px;
    border: 1px solid;
    text-align: center;
    vertical-align: middle;
    cursor: pointer;
    line-height: 1.5;
    transition: all 150ms;
    border-radius: 4px;
    width: fit-content;
    font-size: 0.75rem;
    color: #333;
    background-color: #f0f0f0;
    border-color: #f0f0f0;

    &:disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    &--green-1 {
        background-color: $green-1;
        border-color: $green-1;
        color: #fff;
        margin-left: auto;
    }
}
</style>

<script lang="ts">

import { useOrganizationFormDataStore } from "@/stores/organizationFormData";
import ApiService from "@/core/services/ApiService";
import Swal from "sweetalert2/dist/sweetalert2.js";
import { defineComponent, ref, computed, reactive, watch } from "vue";
import KGMS_Step1Organization from "@/components/organizations/stepper/KGMS_Step1Organization.vue";
import KGMS_Step2Principal from "@/components/organizations/stepper/KGMS_Step2Principal.vue";


interface Step1Organization {
    oName: string;
    oAddress: string;
    oContact: string;
    oEmail: string;
}

interface Step2Principal {
    pName: string;
    pContact: string;
    pEmail: string;
    pPassword: string;
}

export default defineComponent({
    name: "KGMS_Stepper",
    components: {
        KGMS_Step1Organization,
        KGMS_Step2Principal,
    },
    setup() {

        const store = useOrganizationFormDataStore();
        
        const step = ref(1);

        const totalSteps = ref(2);

        const formData = reactive({
            oName: "",
            oAddress: "",
            oContact: 0,
            oEmail: "",
            pName: "",
            pContact: 0,
            pEmail: "",
            pPassword: "",
        });

        const stepperProgress = computed(() => {
            return ((100 / 4) * (step.value - 1)).toString() + "%";
        });

        const prevStep = () => {
            if (step.value > 1) {
                step.value--;
            }
        };

        const nextStep = () => {
            if (step.value < totalSteps.value) {
                step.value++;
            }
        };

        const handleOrganizationData = (data: Step1Organization) => {
            formData.oName = data?.oName;
            formData.oAddress = data?.oAddress;
            formData.oContact = parseInt(data?.oContact);
            formData.oEmail = data?.oEmail;
        };

        const handlePrincipalData = (data: Step2Principal) => {
            formData.pName = data?.pName;
            formData.pContact = parseInt(data?.pContact);
            formData.pEmail = data?.pEmail;
            formData.pPassword = data?.pPassword;
        };

        const createOrganization = async () => {
            return await ApiService.post("/organization/create", formData)
            .then(({ data }) => {
                console.log('Success : ',data);
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Close'
                });
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    Swal.fire({
                        title: 'Oops...',
                        text: response.data.message,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Try again!'
                    });

                    store.setOrganizationFormDataErrors(response.data.errors);
                    step.value = 1;
                    //console.log(store.formDataErrors);
                }
            });
        };

        watch(formData, (newValue, oldValue) => {
            store.setOrganizationFormData(formData);
            //console.log(store.formData);
        });

        return {
            KGMS_Step1Organization,
            KGMS_Step2Principal,
            stepperProgress,
            prevStep,
            nextStep,
            step,
            totalSteps,
            handleOrganizationData,
            handlePrincipalData,
            createOrganization,
        };
    },
});
</script>
