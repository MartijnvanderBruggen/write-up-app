<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import WriteUp from '@/Components/WriteUp.vue';
import DropZone from '@/Components/DropZone.vue';
import { ref } from 'vue';

defineProps(['writeups']);


const form = useForm({
    title: '',
    content: '',
    files: []  
});
const dropzoneFile = ref("");

const drop = (e) => {
    dropzoneFile.value = e.dataTransfer.files[0];    
    form.files.push(dropzoneFile.value);
}

const selectedFile = () => {
    dropzoneFile.value = document.querySelector('.dropzoneFile').files[0];
    form.files.push(dropzoneFile.value);
}


</script>
 
<template>
    <Head title="WriteUps" />
 
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form class="write-up-form" @submit.prevent="form.post(route('writeups.store'), { onSuccess: () => form.reset() })"  enctype="multipart/form-data">
                <input v-model="form.title" placeholder="what is the title of your writeup?" class="my-5 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                <textarea
                    v-model="form.content"
                    placeholder="Start writing your write up!"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :content="form.errors.content" class="mt-2" />
                <DropZone v-model="form.files" @drop.prevent="drop" @change="selectedFile" class="my-5" />
                <span class="file-info">File: {{ dropzoneFile.name }}</span>
                <PrimaryButton class="mt-4">Save</PrimaryButton>
            </form>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <WriteUp
                    v-for="writeup in writeups"
                    :key="writeup.id"
                    :writeup="writeup"
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>