<template>
    <AuthenticatedLayout :hideDefaultNav="true">
        <div class="w-full">
            <RepoNav />
            <main>
                <header class="p-6 w-full">
                    <div class="flex gap-2 items-center text-2xl">
                        <h1>Commit</h1>
                        <p class="bg-gray-900 px-2 rounded">{{ commit.message }}</p>
                    </div>
                </header>
                
                <div class="w-full p-4 flex flex-col gap-1">
                    <div v-for="content in test"
                        class=""
                    >
                        <p class="text-gray-400">{{ content.path }}</p>
                        <DisplayFileContent :content="content.content"/>
                    </div>
                </div>
            </main>
        </div>
        
    </AuthenticatedLayout>

</template>

<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RepoNav from '@/Components/RepoNav.vue';
import { defineProps } from 'vue';
import DisplayFileContent from './DisplayFileContent.vue';

const props = defineProps({
    test: Array,
    commit: Object,
});
</script>