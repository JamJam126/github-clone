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
                    <li v-for="(file, index) in commitData.files" 
                        :key="index"
                        class="p-2 bg-gray-800 border border-gray-500 rounded-lg flex justify-between">
                        {{ file.name }}
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
                        id="fileInput"
                        @change="handleFileChange" 
                        multiple 
                        webkitdirectory
                        class="text-black mt-5"
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
import Modal from '../Components/Modal.vue';
import PrimaryButton from '../Components/PrimaryButton.vue';
import SecondaryButton from '../Components/SecondaryButton.vue';

const props = defineProps ({
    showModal: {
        type: Boolean,
        default: false,
    },

    repo_id: {
        type: Object,
    },

    repo_name: {
        type: String,
    },

    user_name: {
        type: String,
    },

})

const commitMessage = ref('')

const emit = defineEmits(['update:showModal', 'commit']);

const closeModal = () => {
    emit('update:showModal')
}

// const addFile = () => {
//     if (newFile.value.trim()) {
//         files.value.push(newFile.value.trim())
//         newFile.value = ''
//     }
// }

const commitData = useForm({
    id: props.repo_id,
    message: '',
    files: [],
    relativePath: [] 
});

const handleFileChange = (event) => {
    const selectedFiles = Array.from(event.target.files);
    selectedFiles.forEach(file => {
       commitData.files.push(file)
       commitData.relativePath.push(file.webkitRelativePath)
    });
}

const removeFile = (index) => {
    files.value.splice(index, 1)
}

const cancelAddFile = () => {
    files.value = []
    emit('update:showModal', false)
}

// https://www.youtube.com/watch?v=dD2rea_fCFk

const readFileContent = (file) => {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            resolve(e.target.result); 
        };
        reader.onerror = (error) => {
            reject(error);
        };
        reader.readAsText(file); 
    });
};

//     // fetch('files.commit', { user: props.user_name, repo: props.repo_name }, {
        
//     //     method: 'POST',
//     //     body: commitData,
//     // })

const commitFiles = async () => {
    try {
        // const filesWithContent = await Promise.all(files.value.map(async (file) => {
        //     const content = await readFileContent(file); // Read content asynchronously
        //     return {
        //         name: file.name,
        //         size: (file.size / 1024).toFixed(2),
        //         path: file.webkitRelativePath,
        //         content: content
        //     };
        // }));

        // const commitData = useForm({
        //     id: props.repo_id,
        //     message: commitMessage.value,
        //     files: filesWithContent, 
        // });
        commitData.message = commitMessage.value

        commitData.post(route('files.commit', { user: props.user_name, repo: props.repo_name }), {
            onFinish: (response) => {
                console.log(response.data);
                emit('update:showModal', false);
                // files.value = []; 
            },
        });

    } catch (error) {
        console.error("Error reading file contents:", error);
    }
};


</script>