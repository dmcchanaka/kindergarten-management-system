<template>
    <div class="max-w-full px-3 mb-4 lg:mb-0 lg:w-full lg:flex-none">
        <div class="relative flex flex-col min-w-0 mt-6 break-words bg-white border-0 border-transparent border-solid shadow-soft-xl rounded-2xl bg-clip-border">
            <div class="p-4 pb-0 mb-0 bg-white border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
                <div class="flex flex-wrap mx-3">
                    <div class="flex items-center flex-none w-1/2 max-w-full px-3">
                        <h6 class="mb-0 text-header">{{ translate('gallery') }}</h6>
                    </div>
                    <div class="flex-none w-1/2 max-w-full px-3 text-right">
                        <router-link to="/gallery" class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                            <fa icon="arrow-left" />
                            &nbsp;&nbsp;{{ translate('back') }}
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap mx-3 mb-3">
                <div class="w-full max-w-full px-3 mt-6 md:w-8/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0 text-sub-header">{{ translate('basicInformation') }}</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <div>
                                                    <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('title') }}</label>
                                                    <input v-model="galleryForm.title" type="text" id="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('title')" required>
                                                    <ErrorLabel v-if="formErrors.title" :error="formErrors.title"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label for="address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ translate('description') }}</label>
                                                    <textarea v-model="galleryForm.description" id="address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="translate('description')" required></textarea>
                                                    <ErrorLabel v-if="formErrors.description" :error="formErrors.description"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">{{ translate('featureImage') }}</label>
                                                    <input @change="selectFeatureImage" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="multiple_files" type="file">
                                                    <div v-if="galleryForm.feature_image">
                                                        <img :src="galleryForm.feature_image_label" class="w-12 h-12 mt-2 rounded-lg" alt="Feature Image Preview">
                                                    </div>
                                                    <ErrorLabel v-if="formErrors.feature_image" :error="formErrors.feature_image"></ErrorLabel>
                                                </div>
                                                <div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="multiple_files">{{ translate('contentImages') }}</label>
                                                    <div class="relative">
                                                        <input @change="selectContentImages" class="block w-full p-2 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="multiple_files" type="file" multiple>
                                                        <span v-if="galleryForm.content_images_label.length > 0" class="absolute top-0 right-0 -mt-8 mr-4 text-sm text-gray-500">{{ galleryForm.content_images.length }} file(s) selected</span>
                                                    </div>
                                                    <div class="flex mt-2">
                                                        <div v-for="(image, index) in galleryForm.content_images_label" :key="index">
                                                            <div class="relative">
                                                            <img :src="image" class="w-12 h-12 mt-2 mx-0.5 rounded-lg" alt="Content Image Preview">
                                                            <a @click="removeContentImage(index)" class="absolute top-0 right-0 w-6 h-6 flex items-center justify-center bg-gray-300 rounded-full cursor-pointer"><fa icon="xmark" /></a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button 
                                                ref="submitButton" 
                                                type="submit" 
                                                @click.prevent="submitGallery"
                                                :disabled="galleryForm.loading"
                                                class="inline-block px-6 py-2 text-center text-white uppercase align-middle transition-all rounded-lg cursor-pointer leading-pro text-lg ease-soft-in shadow-soft-md bg-150 bg-lime-500 hover:shadow-soft-xs active:opacity-85 hover:scale-102 tracking-tight-soft bg-x-25 font-custom">
                                                <span v-if="!galleryForm.loading">{{ translate('submit') }}</span>
                                                <span v-if="galleryForm.loading">
                                                    {{ translate('pleaseWait') }}...
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="w-full max-w-full px-3 mt-6 md:w-4/12 md:flex-none">
                    <div class="relative flex flex-col h-full min-w-0 mb-6 break-words bg-transparent border border-solid shadow-none rounded-xl border-slate-100 bg-clip-border">
                        <div class="p-6 px-4 pb-0 mb-0 bg-white border-b-0 rounded-t-2xl">
                            <div class="flex flex-wrap -mx-3">
                                <div class="max-w-full px-3 md:w-1/2 md:flex-none">
                                    <h6 class="mb-0 text-sub-header">{{ translate('additionalInformation') }}</h6>
                                </div>
                                <div class="flex items-center justify-end max-w-full px-3 md:w-1/2 md:flex-none">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="flex-auto p-4 pt-6">
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('organization') }}</label>
                                                <Multiselect 
                                                    @select="selectOrganization"
                                                    v-model="galleryForm.orgId"
                                                    :placeholder="translate('chooseOrganization')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="organizationList" />
                                                    <ErrorLabel v-if="formErrors.org_id" :error="formErrors.org_id"></ErrorLabel>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('classRoom') }}</label>
                                                <Multiselect
                                                    @select="selectClassRoom"
                                                    v-model="galleryForm.classRoomId"
                                                    :placeholder="translate('chooseClassRoom')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="classRoomList" />
                                                    <ErrorLabel v-if="formErrors.class_room_id" :error="formErrors.class_room_id"></ErrorLabel>
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            <ul class="flex flex-col pl-0 mb-0 rounded-lg">
                                <li class="relative flex p-6 mb-2 border-0 rounded-t-inherit rounded-xl bg-gray-50">
                                    <div class="w-full">
                                        <form>
                                            <div class="grid gap-6 mb-6 md:grid-cols-1">
                                                <label class="block mb-0 text-sm font-medium text-gray-900 dark:text-white">{{ translate('students') }}</label>
                                                <Multiselect
                                                    v-model="galleryForm.studentId"
                                                    :placeholder="translate('chooseStudents')"
                                                    :close-on-select="true"
                                                    :searchable="true" 
                                                    :options="studentList" />
                                            </div>
                                        </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script lang="ts">
import { defineComponent, onMounted, ref, computed } from "vue";
import { useRouter } from "vue-router";
import { useI18n } from "vue-i18n";
import Swal from "sweetalert2/dist/sweetalert2.js";

import ErrorLabel from "@/components/global/ErrorLabel.vue";
import Multiselect from '@vueform/multiselect';

import { useGalleryStore, type Gallery, Student } from "@/stores/gallery";
import { useClassRoomStore, type Organization } from "@/stores/classRoom";
import { useStudentStore, type ClassRoom, Parent, StudentForm } from "@/stores/students";

export default defineComponent({
    name: "edit-gallery",
    components: {
        ErrorLabel,
        Multiselect
    },
    setup() {
        const { t, te } = useI18n();
        const store = useGalleryStore();
        const classRoomStore = useClassRoomStore();
        const studentStore = useStudentStore();
        const router = useRouter();

        const translate = (text: string) => {
            if (te(text)) {
                return t(text);
            } else {
                return text;
            }
        };

        const galleryForm = ref({
            id: "",
            title: "",
            description: "",
            feature_image: "" as any,
            feature_image_label: "",
            content_images: [] as any[],
            content_images_label: [] as string[],
            orgId: "",
            classRoomId: "",
            studentId: "",
            loading: false
        });
        
        const formErrors = ref<any>({
            title: "",
            description: "",
            feature_image: "",
            orgId: "",
            classRoomId: "",
        });

        const submitButton = ref<HTMLButtonElement | null>(null);

        const organizationList = ref<Array<Organization>>([]);
        const classRoomList = ref<Array<ClassRoom>>([]);
        const studentList = ref<Array<Student>>([]);
        const contentInfo = ref<Array<Gallery>>([]);

        onMounted(async () => {
            await fetchOrganizationList();
            await getGalleryInfo();
        });

        const getGalleryInfo = async () => {
            if(store.idContent){
                contentInfo.value.splice(0, contentInfo.value.length, ...store.contentList);
                let results = contentInfo.value.filter((item) => {
                    return item.id.toString() == store.idContent.toString();
                });
                contentInfo.value.splice(0,contentInfo.value.length,...results);

                galleryForm.value.id = contentInfo?.value[0].id.toString() || "";
                galleryForm.value.title = contentInfo?.value[0].title || "";
                galleryForm.value.description = contentInfo?.value[0].description || "";

                galleryForm.value.orgId = contentInfo?.value[0].organization.id.toString() || "";
                if(contentInfo?.value[0].organization.id.toString() != ""){
                    await selectOrganization(contentInfo?.value[0].organization.id.toString());
                    galleryForm.value.classRoomId = contentInfo?.value[0].class_room.id.toString() || "";
                }

                if(contentInfo?.value[0].class_room.id.toString() != ""){
                    await selectClassRoom(contentInfo?.value[0].class_room.id.toString());
                    if(typeof contentInfo?.value[0].student.id != "undefined"){
                        galleryForm.value.studentId = contentInfo?.value[0].student.id.toString() || "";
                    }
                }
                
                // Convert feature_image to blob
                const featureImageUrl = contentInfo?.value[0].feature_image;
                const featureImageResponse = await fetch(featureImageUrl);
                const featureImageBlob = await featureImageResponse.blob();
                const featureImageFile = new File([featureImageBlob], 'image.jpg', { type: featureImageBlob.type });

                galleryForm.value.feature_image = featureImageFile;
                galleryForm.value.feature_image_label = contentInfo?.value[0].feature_image || "";

                const contentImagesPromises = contentInfo?.value[0].content_images.map(async (imageObj) => {
                    const imageUrl = imageObj.image_url;
                    const response = await fetch(imageUrl);
                    const blob = await response.blob();
                    const file = new File([blob], 'image.jpg', { type: blob.type });
                    return file;
                });

                const contentImages = await Promise.all(contentImagesPromises);

                galleryForm.value.content_images = contentImages;
                galleryForm.value.content_images_label = contentImages.map(image => URL.createObjectURL(image))
            } else {
                router.go(-1);
            }
        }

        const fetchOrganizationList = async() => {
            await classRoomStore.fetchOrganizations();
            const error = Object.values(classRoomStore.errors);
            if (error.length === 0) {
                organizationList.value.splice(0, organizationList.value.length, ...classRoomStore.organizationsList);
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    classRoomStore.errors = {};
                })
            }
        }

        const selectOrganization = async (selectedOrganization) => {
            const inputs = {
                organizationId: selectedOrganization,
            };
            await studentStore.lookupClassRooms(inputs);
            const error = Object.values(studentStore.errors);
            if (error.length === 0) {
                classRoomList.value.splice(0, classRoomList.value.length, ...studentStore.classRoomList);
                galleryForm.value.classRoomId = "";
                galleryForm.value.studentId = "";
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                classRoomStore.errors = {};
                })
            }
        }

        const selectClassRoom = async(selectedClassRoom) => {
            const inputs = {
                organizationId: galleryForm.value.orgId,
                classRoomId: selectedClassRoom,
            };
            await store.lookupClassRoomStudents(inputs);
            const error = Object.values(store.errors);
            if (error.length === 0) {
                studentList.value.splice(0, studentList.value.length, ...store.studentList);
                galleryForm.value.studentId = "";
            } else {
                galleryForm.value.studentId = "";
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    store.errors = {};
                })
            }
        }

        const selectFeatureImage = (event) => {
            const createUrl = URL.createObjectURL(event.target.files[0]);
            URL.revokeObjectURL(event.target.files[0]);
            galleryForm.value.feature_image_label = createUrl;
            galleryForm.value.feature_image = event.target.files[0];
        }

        const selectContentImages = (event) => {
            const tmpFiles = event.target.files;
            if (tmpFiles.length === 0) {
                return false;
            }
            const imageUrls: string[] = [...galleryForm.value.content_images_label]; // Retain previous image URLs
            const tmpArray: any[] = [...galleryForm.value.content_images]; // Retain previous images
            for (let i = 0; i < tmpFiles.length; i++) {
                const file = tmpFiles[i];
                if (typeof file === "string") {
                    imageUrls.push(file);
                    // For the string case, you need to convert it to a File object if needed
                    const fileObj = new File([new Blob()], 'image.jpg', { type: 'image/jpeg' });
                    tmpArray.push(fileObj); // Adding the new images to the content_images array
                } else if (file instanceof File) {
                    const imageUrl = URL.createObjectURL(file);
                    imageUrls.push(imageUrl);
                    tmpArray.push(file); // Adding the new images to the content_images array
                }
            }
            galleryForm.value.content_images = tmpArray;
            galleryForm.value.content_images_label = imageUrls;
        }

        const removeContentImage = (index) => {
            galleryForm.value.content_images.splice(index, 1);
            galleryForm.value.content_images_label.splice(index, 1);
        }

        const submitGallery = async() => {
            const formData = new FormData();
            formData.append('id', galleryForm.value.id);
            formData.append('title', galleryForm.value.title);
            formData.append('description', galleryForm.value.description);
            formData.append('feature_image', galleryForm.value.feature_image);
            for (let i = 0; i < galleryForm.value.content_images.length; i++) {
                formData.append('content_images[]', galleryForm.value.content_images[i]);
            }
            formData.append('org_id', galleryForm.value.orgId);
            formData.append('class_room_id', galleryForm.value.classRoomId);
            formData.append('student_id', galleryForm.value.studentId);
            galleryForm.value.loading = true;
            if (submitButton.value) { 
                submitButton.value!.disabled = true;
            }
            let response = await store.modifyGallery(formData);
            const error = Object.values(store.errors);
            formErrors.value = Object(store.formDataErrors);
            if (error.length === 0) {
                Swal.fire({
                    title: translate('goodJob') + '!',
                    text: translate(response.message),
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('okGotIt') + '!'
                }).then(() => {
                });
            } else {
                Swal.fire({
                    title: translate('opps') + '...',
                    text: error[0] as string,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: translate('tryAgain') + '!'
                }).then((result) => {
                    store.errors = {};
                    store.formDataErrors = {};
                })
            }
            submitButton.value!.disabled = false;
            galleryForm.value.loading = false;
        }

        return {
            translate,
            galleryForm,
            organizationList,
            selectOrganization,
            classRoomList,
            selectFeatureImage,
            selectContentImages,
            submitGallery,
            formErrors,
            submitButton,
            removeContentImage,
            selectClassRoom,
            studentList
        }
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>