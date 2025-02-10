<template>
    <AuthenticatedLayout>

        <div class="w-full bg-gray-950"> <!-- Github Color-> bg-[#0D1117] -->
            <div class="flex flex-col w-[1280px] mx-auto">

                <!-- Repo Name -->
                <div class="h-16 w-full border-b flex items-center 
                         border-b-zinc-700 gap-2">
                    
                    <h1 class="font-semibold text-xl">
                        {{ info.name }}</h1
                    >

                    <div class="font-medium text-xs text-gray-500 border 
                                border-zinc-700 rounded-xl px-[6px] pb-[2px]">
                        {{ info.visibility }}</div
                    >

                </div>

                <div class="h-full w-full flex">
                    <div class="flex-1 flex flex-col">
                        <div class="h-[60px] content-center">
                             
                            <PrimaryButton @click.prevent="showTestModal">
                                Commit</PrimaryButton
                            >
                                
                            <CommitModal :showModal="openModal" 
                                        :repo_id="info.id"
                                        :repo_name="info.name"
                                        :user_name="info.user_name" 
                                        @update:showModal="openModal = $event" 
                                        @commit="handleCommit"
                            />
                        </div>

                        <!-- Project's Content (border-[#3D444D]) --> 
                        <div v-if="folders && files"
                            class="w-full h-auto border border-zinc-700 rounded-lg">
                            <!--Total and Lastest Commits  -->
                            <div class="h-[52px] bg-[#151b23] rounded-t-lg">

                            </div>

                            <!-- Files and Folders -->
                            <div class="flex flex-col ">
                                <!-- Folders -->
                                <ul v-if="folders.length > 0" 
                                    class="">

                                    <li v-for="folder in folders" 
                                        :key="folder.id"
                                        class="h-[40px] content-center border-t border-t-zinc-700 pl-4">

                                        <Link class="flex gap-2"
                                            :href="route('repo.subdir', { 
                                                repo: info.name, 
                                                folder: folder.name })"
                                            > 
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                width="24" 
                                                height="24" 
                                                viewBox="0 0 24 24"
                                                class="text-[#9198a1]"
                                                >
                                                <path fill="currentColor" 
                                                        d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20z"
                                                        />
                                            </svg>
                                            {{ folder.name }} </Link
                                        >   

                                    </li>
                                </ul>

                                <!-- Files -->
                                <ul v-if="files.length > 0" 
                                    class="">

                                    <li v-for="file in files" 
                                        :key="file.id"
                                        class="h-[40px] content-center border-t border-t-zinc-700 pl-4">

                                        <Link class="flex gap-2"
                                            :href="route('repo.filecontent', { 
                                                user: info.user_name, 
                                                repo: info.name, 
                                                file: file.name })"
                                            > 
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                width="24" 
                                                height="24" 
                                                viewBox="0 0 24 24"
                                                class="text-[#9198a1]"
                                                >
                                                <path fill="currentColor" 
                                                        d="M19 19H8q-.825 0-1.412-.587T6 17V3q0-.825.588-1.412T8 1h6.175q.4 0 .763.15t.637.425l4.85 4.85q.275.275.425.638t.15.762V17q0 .825-.587 1.413T19 19m0-11h-3.5q-.625 0-1.062-.437T14 6.5V3H8v14h11zM4 23q-.825 0-1.412-.587T2 21V8q0-.425.288-.712T3 7t.713.288T4 8v13h10q.425 0 .713.288T15 22t-.288.713T14 23zM8 3v5zv14z"
                                                        />
                                            </svg>
                                            {{ file.name }}</Link
                                        >

                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="h-full w-[300px]">

                    </div>
                </div>
            </div>


        </div>
    </AuthenticatedLayout>

</template>

<script setup>

    import { useForm, Link } from '@inertiajs/vue3';
    import { defineProps, ref } from 'vue';
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
        const form = useForm({
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
