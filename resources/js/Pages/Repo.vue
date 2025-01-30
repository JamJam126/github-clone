<template>
    <AuthenticatedLayout>
        <!-- <Head :title= {{ info.name }} /> -->
        
        <!-- {{ info.name }} -->
        <!-- {{ info.user_id }} -->
          <!-- {{ info }} -->

        
        <div class="flex flew-col items-center">
            <pre v-if="info.files" 
                name="" 
                id="testlg" 
                class="w-[1200px] h-[90%] flex bg-black border border-gray-500">

                <textarea name="" 
                            readonly 
                            class="bg-black w-full">
                            {{ info.files }}</textarea
                            >
            </pre>
        
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
            </div>
        </div>

    </AuthenticatedLayout>

</template>

<script setup> 

import { Head, useForm} from '@inertiajs/vue3';
import { computed, defineProps, ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CommitModal from '@/Pages/CommitModal.vue';

const props = defineProps({
    info: Array,
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