import { ref } from "vue";
import { defineStore } from "pinia";
import ApiService from "@/core/services/ApiService";

export interface GalleryForm {
    title: string,
    description: string,
    feature_image: string,
    content_images: any[],
    org_id: string,
    class_room_id: string,
}

export interface Gallery {
    id: string;
    title: string,
    description: string,
    feature_image: string,
    content_images: any[],
    class_room: ClassRoom,
    organization: Organization;
    student: Student;
    added_date: string,
}

export interface ClassRoom {
    id: number;
    name: string;
}

export interface Organization {
    id: number;
    name: string;
}

export interface Student {
    id: number;
    name: string;
}

export const useGalleryStore = defineStore("gallery", () => {
    const errors = ref({});
    const formDataErrors = ref({});
    const contentList = ref<Gallery[]>([]);
    const studentList = ref<Student[]>([]);
    const idContent = ref(0);

    function galleryRegistration(formData: FormData) {
        return ApiService.post("/gallery-registration", formData)
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    let errorMsg = '';
                    if (typeof response.data.errors === 'object') {
                        errorMsg = 'Some fields are missing';
                    } else {
                        errorMsg = response.data.errors;
                    }
                    const error = {
                        message : errorMsg,
                        status : response.status,
                    }
                    setError(error);
                    setFormDataErrors(response.data.errors);
                }
            });
    }

    function setError(error: any) {
        errors.value = { ...error };
    }

    function setFormDataErrors(error: any) {
        formDataErrors.value = { ...error };
    }

    function fetchStudentContent() {
        return ApiService.post("/content-list", {})
        .then(({ data }) => {
            setContents(data.activitiesList);
        })
        .catch(({ response }) => {
            if (response.status !== 200) {
                const error = {
                    message : response.data.errors,
                    status : response.status,
                }
                setError(error);
            }
        });
    }

    function setContents(gallery: Gallery[]){
        contentList.value = gallery;
    }

    function saveContentId(contentId){
        idContent.value = contentId;
    }
    
    function modifyGallery(formData: FormData) {
        return ApiService.post("/gallery-update", formData)
            .then(({ data }) => {
               return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    let errorMsg = '';
                    if (typeof response.data.errors === 'object') {
                        errorMsg = 'Some fields are missing';
                    } else {
                        errorMsg = response.data.errors;
                    }
                    const error = {
                        message : errorMsg,
                        status : response.status,
                    }
                    setError(error);
                    setFormDataErrors(response.data.errors);
                }
            });
    }

    function removeGallery(input){
        return ApiService.post("/gallery-remove", input)
            .then(({ data }) => {
                return data;
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function lookupClassRoomStudents(input){
        return ApiService.post("/student-list-associate-with-class-room", input)
            .then(({ data }) => {
                setStudents(data.studentsList);
            })
            .catch(({ response }) => {
                if (response.status !== 200) {
                    const error = {
                        message : response.data.errors,
                        status : response.status,
                    }
                    setError(error);
                }
            });
    }

    function setStudents(students: Student[]) {
        studentList.value = students;
        errors.value = {};
    }

    return {
        errors,
        formDataErrors,
        galleryRegistration,
        fetchStudentContent,
        contentList,
        saveContentId,
        idContent,
        modifyGallery,
        removeGallery,
        lookupClassRoomStudents,
        studentList
    }
});
