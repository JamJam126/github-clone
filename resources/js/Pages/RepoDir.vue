<template>
    <AuthenticatedLayout>
        <div>
            <ul v-if="folders"
                class="">
                <li v-for="folder in folders"
                    :key="folder.id"
                    class="">

                    <Link class=""
                          :href="route('repo.subdir', {repo: repo.name, folder: folder.name})">
                            {{ folder.name }}</Link    
                    >

                </li>
            </ul>
            <ul v-if="files"
                class="">
                <li v-for="file in files"
                    :key="file.id">
                    <Link class=""
                          :href="route('subdir.filecontent', {repo: repo.name, folder: currFolder, file: file.name})">
                            {{ file.name }}</Link
                            
                    >
                </li>

            </ul>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

    import { defineProps } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    
    const props = defineProps({

        repo: Object,

        files: Array,

        folders: Array,

        currFolder: Object,
    })

</script>