<template>
    <div >
        <Modal :show="showModal" >

            <div class="h-[auto] bg-[#010409] text-white flex flex-col items-start p-4">

                <div class="flex justify-between w-full">
                    <h2 class="text-2xl font-bold my-5">
                        Add Files</h2
                        >
                    <button class="mb-10 mr-2 text-xl"
                            @click="closeModal">
                            X</button
                    >
                </div>
                <ul class="w-full pb-5 px-4">
                    <li v-for="(file, index) in files" 
                        :key="index"
                        class="p-2 bg-gray-800 border border-gray-500 rounded-lg flex justify-between">
                        {{ file }}
                        <button @click="removeFile(index)">X</button></li
                    >

                </ul>

                <!-- <input v-model="newFile" 
                        placeholder="Add a new file" 
                        class="text-black"
                        /> -->

                <input v-model="commitMessage"
                        placeholder="Commit Message"
                        class="text-black"
                        />

                <!-- <h2>{{ repo_id }}</h2> -->
                <div class="flex flex-row justify-between w-full">
                    <!-- <SecondaryButton @click.prevent="addFile"
                                class="mt-4">
                                Add File</SecondaryButton
                    > -->
                    <input type="file" 
                        @change="handleFileChange" 
                        multiple class="text-black mt-5"
                        />

                    <div class="flex gap-4 h-10 mt-4">
                        <PrimaryButton class="bg-red-600"
                                    @click.prevent="cancelAddFile">
                                    Cancel</PrimaryButton
                                    >
                        <PrimaryButton class="" 
                                    @click.prevent="commitFiles">
                                    Commit</PrimaryButton
                                    >
                    </div>
                </div>
            </div>
        </Modal>
    </div>
    

</template>

<script setup>

import { defineProps, defineEmits, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from './Modal.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';

const props = defineProps ({
    showModal: {
        type: Boolean,
        default: false,
    },

    repo_id: {
        type: Object,
    }
})

const files = ref([])
const newFile = ref('')
const commitMessage = ref('')

const emit = defineEmits(['update:showModal', 'commit']);

const closeModal = () => {
    emit('update:showModal')
}

const addFile = () => {
    if (newFile.value.trim()) {
        files.value.push(newFile.value.trim())
        newFile.value = ''
    }
}

const handleFileChange = (event) => {
    const selectedFiles = Array.from(event.target.files);
    selectedFiles.forEach(file => {
        files.value.push(file);
    });
}

const removeFile = (index) => {
    files.value.splice(index, 1)
}

const cancelAddFile = () => {
    files.value = []
    emit('update:showModal', false)
}

const commitFiles = () => {
        
    const form = useForm({
        id: props.repo_id,
        message: commitMessage.value,
    })
    const response = form.post(route('files.commit'), {
        onFinish: () => {
            emit('commit', files.value)
            emit('update:showModal', false)

            files.value = []
        },
    });
    // console.log(response.data)   
}

</script>