<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
 
dayjs.extend(relativeTime);
 

const props = defineProps(['writeup']);


const form = useForm({
    content: props.writeup.content,
    images: props.writeup.images
});

const editing = ref(false);

const showImageModal = (event) => {
    console.log(event);
}
</script>
 
<template>
    <div class="p-6 flex space-x-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <div class="flex-1">
            <div class="flex justify-between items-center">
                <div>
                    <span class="text-gray-800">{{ writeup.user.name }}</span>
                    <small class="ml-2 text-sm text-gray-600">{{ dayjs(writeup.created_at).fromNow() }}</small>
                    <small v-if="writeup.created_at !== writeup.updated_at" class="text-sm text-gray-600"> &middot; edited</small>
                </div>
                <!-- $page is probably information about the current page coming from vue router props passed down, accessed through object destructuring -->
                <Dropdown v-if="writeup.user.id === $page.props.auth.user.id">
                    <!-- #trigger is to reference a named slot in a template -->
                    <template #trigger>
                        <button>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                            </svg>
                        </button>
                    </template>
                    <template #content>
                        <button class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:bg-gray-100 transition duration-150 ease-in-out" @click="editing = true">
                            Edit
                        </button>
                        <DropdownLink as="button" :href="route('writeups.destroy', writeup.id)" method="delete">
                            Delete
                        </DropdownLink>
                    </template>
                </Dropdown>
            </div>
             <form v-if="editing" @submit.prevent="form.put(route('writeups.update', writeup.id), { onSuccess: () => editing = false })">
                <textarea v-model="form.content" class="mt-4 w-full text-gray-900 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <div class="space-x-2">
                    <PrimaryButton class="mt-4">Save</PrimaryButton>
                    <button class="mt-4" @click="editing = false; form.reset(); form.clearErrors()">Cancel</button>
                </div>
            </form>
            <p v-else v-html="writeup.content" class="mt-4 text-lg text-gray-900 writeup-content"></p>
            <p class="flex w-full justify-normal justify-items-center"><img v-for="image in form.images" :key="image.id" :src="image.image_path" @click="showImageModal" class="flex-1 w-32 border border-b-emerald-400"/></p>
        </div>
    </div>
</template>

<style scoped>
.writeup-content {
    background-color: rgb(27, 29, 29);
    height:200px;
    color:rgba(78, 184, 6, 1);
    padding:15px;
    border-radius:5px;
}
</style>