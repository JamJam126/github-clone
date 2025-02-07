<template>
    <AuthenticatedLayout>
        <!-- <Head :title= {{ info.name }} /> -->
        
        <!-- {{ info.name }} -->
        <!-- {{ info.user_id }} -->
          <!-- {{ info }} -->

        
        <div class="flex flew-row items-center justify-center w-full">
            <!-- <pre v-if="info.files" 
                name="" 
                id="testlg" 
                class="w-[1200px] h-[90%] flex bg-black border border-gray-500">

                <textarea name="" 
                            readonly 
                            class="bg-black w-full">
                            {{ info.files }}</textarea
                            >
            </pre> -->
          
            <div class="flex flex-col gap-2">
                <ul v-if="folders" 
                    class="flex flex-col gap-2">
                    <li v-for="folder in folders"
                        :key="folder.id"
                    >
                        <!--  -->
                        <Link class=""
                              :href="route('repo.subdir', { repo: info.name, folder: folder.name})">
                            {{ folder.name }}</Link    
                        >
                    </li>
                </ul>
                <ul v-if="files"
                    class="flex flex-col gap-2">
                    <li v-for="file in files"
                        :key="file.id"
                    >
                        <Link class=""
                              :href="route('repo.filecontent', { user: info.user_name,repo: info.name, file: file.name})">
                            {{ file.name }}</Link
                            
                        >
                    </li>
                </ul>
            </div>
            
        </div>

        <div class="">  
            <PrimaryButton @click.prevent="showTestModal">
                Open</PrimaryButton
            >
                
            <CommitModal :showModal="openModal" 
                        :repo_id="info.id"
                        :repo_name="info.name"
                        :user_name="info.user_name" 
                        @update:showModal="openModal = $event" 
                        @commit="handleCommit"
            />

            <!-- {{ user.name }} -->
        </div>

    </AuthenticatedLayout>

</template>

<script setup> 

import { Head, useForm, Link} from '@inertiajs/vue3';
import { computed, defineProps, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CommitModal from '@/Pages/CommitModal.vue';

const props = defineProps({
    info: Object,    

    files: Array,

    folders: Array,
    
});

const openModal = ref(false);

const showTestModal = () => {
    openModal.value = !openModal.value;
}

const handleCommit = (files) => {
    for (const file of files) {
        const sizeInKB = (file.size / 1024).toFixed(2)
        const form = useForm ({
            name: file.name,
            size: sizeInKB,
        })

        form.post(route("commited.files")); 

        console.log(props.info.test)

        // console.log(index)
        // console.log(`${file.name}`)
        // console.log(`${sizeInKB}`)
        
    };
}

</script>