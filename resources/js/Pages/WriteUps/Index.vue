<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import WriteUp from '@/Components/WriteUp.vue';
import DropZone from '@/Components/DropZone.vue';
import { ref } from 'vue';
import Editor from '@tinymce/tinymce-vue';
import { watch } from 'vue';
import axios from 'axios';


defineProps({'writeups': Array, 'editor': Editor, 'writeUpCategories': Array });

const selectedCategory = ref({
  "id": 0,
  "name": "",
  "created_at": "",
  "updated_at": ""
});
const form = useForm({
    title: '',
    content: '',
    files: [],     
});
const dropzoneFile = ref("");
const categoryComboBox = ref(false);
const newCategoryItem = ref();
const categories = ref([]);



const drop = (e) => {
    dropzoneFile.value = e.dataTransfer.files[0];    
    form.files.push(dropzoneFile.value);
}

const selectedFile = () => {
    dropzoneFile.value = document.querySelector('.dropzoneFile').files[0];
    form.files.push(dropzoneFile.value);
}

const showCategoryComboBox = () => {
    categoryComboBox.value = !categoryComboBox.value;
}

watch(newCategoryItem, async (newVal) => {
    if (newVal) {
        try {
            // Send POST request to store the new category
            const response = await axios.post('/api/writeUpCategory', {
                name: newVal
            });

            if (response.status === 201) {
                // Fetch updated categories from the server
                const updatedCategoriesResponse = await axios.get('/api/writeUpCategories');

                if (updatedCategoriesResponse.status === 200) {
                    // Update the writeUpCategories ref with the new data
                    categories.value = updatedCategoriesResponse.data;
                    console.log(categories.value)
                }
            }
        } catch (error) {
            console.error('Error adding new category:', error);
        }
    }
});

</script>
 
<template>
    <Head title="WriteUps" />
 
    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <v-btn color="primary" hover="blue darken-2" elevation="2" raised @click="showCategoryComboBox">
                <v-icon>
                    mdi-tag
                </v-icon>
            </v-btn>
            <div v-if="categoryComboBox" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <v-combobox 
                    v-model="newCategoryItem"
                    # get a list of categories from db 
                    :items="writeUpCategories"
                    label="Edit categories"
                    multiple
                    chips
                >
                </v-combobox>
            </div>
            <form class="write-up-form" @submit.prevent="form.post(route('writeups.store'), { onSuccess: () => form.reset() })"  enctype="multipart/form-data">
                
                <input v-model="form.title" placeholder="what is the title of your writeup?" class="my-5 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                <Editor api-key="4eprkkpt3tfvqbjg1hebo9crm8n5ty6mr27rvgj39w12wy5u"  v-model="form.content"
                    placeholder="Start writing your write up!"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"/>

                <InputError :content="form.errors.content" class="mt-2" />
                <DropZone v-model="form.files" @drop.prevent="drop" @change="selectedFile" class="my-5" />
                <span class="file-info">File: {{ dropzoneFile.name }}</span>
                <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                    <v-select class="hover:bg-blue-100"
                        v-model="selectedCategory"            
                        :items="categories"
                        item-id="id"
                        item-value="name"
                        item-title="name"
                        label="Select write-up Category:"
                        >
                    </v-select>
                </div>
                 
                <PrimaryButton class="mt-4">Save</PrimaryButton>
            </form>
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <WriteUp
                    v-for="writeup in writeups"
                    :key="writeup.id"
                    :writeup="writeup"
                />
            </div>
            
            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <p>{{ $page.props.message }}</p>
            </div>            
        </div>
    </AuthenticatedLayout>
</template>