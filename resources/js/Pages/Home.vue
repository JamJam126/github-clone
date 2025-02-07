<template>
    <AuthenticatedLayout>
        <!-- Side Bar -->
        <div class="main-side border-r h-full flex flex-col px-6 pt-4">

            <div class="mt-8" v-if="repos.length === 0">
                <h2 class="mb-1 text-sm font-semibold">
                    Create your first project</h2
                >

                <p 
                    class="text-sm mb-4" 
                    style="line-height: 21px;">
                    Ready to start building? Create a repository for a new idea or bring over an existing repository to keep contributing to it.</p
                >
                    
                <div>

                    <Link
                        href="new" 
                        class="h-5 text-white font-medium rounded-md text-sm px-4 py-[6px] bg-[#238536] box-content"
                        as="button"
                        style="border: solid 0.8px rgba(255, 255, 255, 0.1);">
                        Create repository</Link
                    >

                </div>
            </div>

            <div v-else class="mt-8">
                <ul>
                    <li v-for="repo in repos" 
                        :key="repo.id" 
                        class="text-white">

                        <Link class=""
                            :href="route('repo.show', { user: repo.user_name, repo: repo.name})"
                            >
                            {{ repo.user_name }} / {{ repo.name }}</Link
                        >

                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div>Home Page</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import axios from 'axios';
// import { Inertia } from '@inertiajs/vue3';

const props = defineProps({
    repos: Array,
});

// console.log(repos);

</script>