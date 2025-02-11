<template>
    <AuthenticatedLayout>
        <div class="bg-gray-950 w-full flex">
            <div class="w-80 h-auto border-r border-zinc-700">
                <!-- <SubFolders :folder_tree="repo_tree" /> -->
                <ul v-if="repo_tree"
                    class="px-4 mt-4">
                    <li v-for="(item, index) in repo_tree"
                        :key="item.id"
                        class="flex flex-col">
                        <Folder :name="item.name"
                                :type="item.type"
                                :index="index"
                                :array="array"
                                :repo_name="repo.name"
                                :file_id="item.id"
                                @handle-expansion="handleFolderExpansion"
                        />
                    </li>
                </ul>
            </div>

            <div class="flex flex-col w-full flex-1 px-4">
                <div class="h-12">

                </div>

                <div class="mt-4 w-full">

                    <div class="w-full">

                        <div class="h-12 w-full border pl-4 flex items-center
                                    rounded-lg font-semibold text-sm border-zinc-700">
                            <h1>Elapoch</h1> <!--Username Place Holder-->
                        </div>

                        <div class="border border-zinc-700 mt-4 rounded-lg">
                            <div class="w-full h-10 bg-[#151b23] rounded-t-lg
                                        text-[12px] font-semibold pl-4 flex text-gray-500
                                        items-center">
                                <p>Name</p>
                            </div>

                            <ul v-if="folders" class="">
                                <li v-for="folder in folders" :key="folder.id"
                                    class="h-10 pl-4 content-center border-t border-t-zinc-700">

                                    <Link class="flex gap-2" :href="route('repo.folderhandle', {
                                        user: repo_owner,
                                        repo: repo.name,
                                        path: `${currFolder}/${folder.name}`,
                                    })">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        class="text-[#9198a1]">
                                        <path fill="currentColor"
                                            d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20z" />
                                    </svg>
                                    {{ folder.name }}</Link>

                                </li>
                            </ul>
                            <ul v-if="files" class="">
                                <li v-for="file in files" :key="file.id"
                                    class="h-10 content-center border-t border-t-zinc-700 pl-4">
                                    <Link class="flex gap-2" :href="route('repo.filehandler', {
                                        user: repo_owner,

                                        repo: repo.name,
                                        path: `${currFolder}/${file.name}`,

                                    })">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        class="text-[#9198a1]">
                                        <path fill="currentColor"
                                            d="M19 19H8q-.825 0-1.412-.587T6 17V3q0-.825.588-1.412T8 1h6.175q.4 0 .763.15t.637.425l4.85 4.85q.275.275.425.638t.15.762V17q0 .825-.587 1.413T19 19m0-11h-3.5q-.625 0-1.062-.437T14 6.5V3H8v14h11zM4 23q-.825 0-1.412-.587T2 21V8q0-.425.288-.712T3 7t.713.288T4 8v13h10q.425 0 .713.288T15 22t-.288.713T14 23zM8 3v5zv14z" />
                                    </svg>
                                    {{ file.name }}</Link>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>

    import { defineProps, ref } from 'vue';
    import { Link } from '@inertiajs/vue3';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import Folder from '@/Components/Folder.vue';

const props = defineProps({

    repo: Object,

    files: Array,

    folders: Array,

    currFolder: Object,

    repo_tree: Array,
    repo_owner: String

    // repo_folders: Array,

    // repo_files: Array,
})


    const Test = (index) => {

        console.log(index)

    }

    const array = ref(Array(props.repo_tree.length).fill(0))

    const handleFolderExpansion = (index) => {
        array.value[index] = array.value[index] === 0 ? 1 : 0
    };

</script>
