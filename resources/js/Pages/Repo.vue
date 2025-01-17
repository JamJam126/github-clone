<template>
    <AuthenticatedLayout>
        <!-- <Head :title= {{ info.name }} /> -->
        
        <!-- {{ info.name }} -->
        <!-- {{ info.user_id }} -->
        <div class="">  
            <PrimaryButton @click.prevent="showTestModal">
                Open</PrimaryButton
            >
            
            <CommitModal :showModal="openModal" 
                        :repo_id="info.id" 
                        @update:showModal="openModal = $event" 
                        @commit="handleCommit"
            />
        </div>

    </AuthenticatedLayout>

</template>

<script setup> 

import { Head, useForm} from '@inertiajs/vue3';
import { defineProps, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CommitModal from '@/Components/CommitModal.vue';

const props = defineProps({
    info: Array,
});

const openModal = ref(false);

const showTestModal = () => {
    openModal.value = !openModal.value;
}

const handleCommit = async (files) => {
    for (const file of files) {
        const sizeInKB = (file.size / 1024).toFixed(2)
        const form = useForm ({
            name: file.name,
            size: sizeInKB,
        })

        form.post(route("commited.files")); 

        console.log(props.response)

        // console.log(index)
        // console.log(`${file.name}`)
        // console.log(`${sizeInKB}`)
        
    };
}

</script>