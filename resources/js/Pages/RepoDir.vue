<template>
    <AuthenticatedLayout :hideDefaultNav="true">
        <div class="w-full bg-gray-950 ">

            <RepoNav />
            <div class="flex flex-col w-full h-full">
                <div class="bg-gray-950 w-full flex h-full  ">
                    <div class="w-80 h-auto border-r border-zinc-700">
                        <!-- <SubFolders :folder_tree="repo_tree" /> -->
                        <ul v-if="repo_tree" class="px-4 mt-4">
                            <li v-for="(item, index) in repo_tree" :key="item.id" class="flex flex-col">
                                <Folder :repo_owner="repo_owner"
                                        :name="item.name"
                                        :type="item.type"
                                        :repo_name="repo.name"
                                        :file_id="item.id"
                                        :index="index"
                                        :array="array"
                                        :folderPath="currPath"
                                        @handle-expansion="handleFolderExpansion" />
                            </li>
                        </ul>
                    </div>

                    <div class="flex flex-col w-full flex-1 px-4">
                        <ul class="font-semibold  h-12 pt-2 flex gap-1 items-center ">
                            <li>
                                <Link class="flex gap-2 text-blue-400"
                                :href="route('repo.show', {
                                        user: repo_owner,
                                        repo: repo.name,
                                    })"
                                >
                                    {{ repo.name }}</Link
                                >
                            </li>
                            <li class="flex gap-1"
                                v-for="directory in arrayDirectoryPath"
                                :key="directory.index">
                                <p>/</p>

                                <Link class="flex gap-2 text-blue-400"
                                    v-if="directory.index !== arrayDirectoryPath.length - 1"
                                    :href="route('repo.folderhandle', {
                                        user: repo_owner,
                                        repo: repo.name,
                                        path: directory.path,})"
                                >
                                    {{ directory.folder }}</Link
                                >

                                <p v-else>{{ directory.folder }}</p>

                            </li>

                        </ul>

                        <div class="mt-2 w-full">
                            <div class="w-full">
                                <div class="h-12 w-full border pl-4 pr-3 flex items-center justify-between
                                            rounded-lg font-semibold text-sm border-zinc-700"
                                >
                                    <div class="h-full w-full flex items-center gap-2">
                                        <h1>{{ commit.name }}</h1> <!--Username Place Holder-->
                                        <h1 className="text-sm font-normal text-gray-400">{{ commit.message }}</h1>
                                    </div>
                                    <div class="h-full w-full flex items-center gap-2 justify-end">
                                        <h1 className="text-xs font-normal text-gray-400">{{ commit.created_at }}</h1>
                                        <Link class="flex gap-1 items-center hover:bg-gray-900 py-1 px-2 rounded-md"
                                            :href="route('repo.commithandle', {
                                                    user: repo_owner,
                                                    repo: repo.name,
                                                    path: currFolder,
                                            })"
                                        >
                                            <svg class="text-gray-500"
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                            >
                                                <path fill="currentColor" d="M6.865 6.882A7.25 7.25 0 1 1 4.75 12a.75.75 0 0 0-1.5 0a8.75 8.75 0 1 0 2.552-6.176a1 1 0 0 0-.07.08L4.475 4.646a.5.5 0 0 0-.852.309L3.27 8.844a.5.5 0 0 0 .543.543l3.89-.354a.5.5 0 0 0 .307-.851L6.782 6.954a1 1 0 0 0 .083-.072" />
                                                <path fill="currentColor" d="M12.75 7a.75.75 0 0 0-1.5 0v5a.75.75 0 0 0 .352.636l3 1.875a.75.75 0 1 0 .796-1.272l-2.648-1.655z" />
                                            </svg>
                                            <h1 class="text-xs">History</h1>
                                        </Link>
                                    </div>
                                </div>

                                <div class="border border-zinc-700 mt-4 rounded-lg"
                                     v-if="!content"
                                >
                                    <div class="w-full h-10 bg-[#151b23] rounded-t-lg
                                                text-[12px] font-semibold px-4 text-gray-500
                                                items-center grid grid-cols-[40%,auto,124px]"
                                    >
                                        <p class="">Name</p>
                                        <p class="">Last commit message</p>
                                        <p class="text-end">Last commit date</p>
                                    </div>

                                    <ul v-if="folders" class="">
                                        <li v-for="folder in folders" :key="folder.id"
                                            class="h-10 pl-4 content-center border-t border-t-zinc-700
                                                   px-4 grid grid-cols-[40%,auto,124px] items-center"
                                        >
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
                                                {{ folder.name }}</Link
                                            >
                                            <h1 class="text-sm font-normal text-gray-400">{{ folder.latest_commit.message }}</h1>
                                            <p class="text-end text-sm text-gray-400">{{ folder.latest_commit.created_at }}</p>
                                        </li>
                                    </ul>
                                    <ul v-if="files" class="">
                                        <li v-for="file in files" :key="file.id"
                                            class="h-10 content-center border-t border-t-zinc-700 px-4 grid
                                                   grid-cols-[40%,auto,124px] items-center"
                                        >
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
                                                {{ file.name }}
                                            </Link>
                                            <h1 class="text-sm font-normal text-gray-400">{{ file.latest_commit.message }}</h1>
                                            <p class="text-end text-sm text-gray-400">{{ file.latest_commit.created_at }}</p>
                                        </li>

                                    </ul>
                                </div>
                                <div v-else
                                    class="mt-4 rounded-lg">
                                    <DisplayFileContent :content="content" :filename="filename"/>
                                </div>
                            </div>

                        </div>


                    </div>

                </div>

            </div>
        </div>


    </AuthenticatedLayout>
</template>

<script setup>

import { defineProps, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Folder from '@/Components/Folder.vue';
import RepoNav from '@/Components/RepoNav.vue';
import DisplayFileContent from './DisplayFileContent.vue';

const props = defineProps({
    filename: String,
    repo: Object,
    files: Array,
    folders: Array,
    currFolder: Object,
    repo_tree: Array,
    repo_owner: String,
    currFolder: String,
    content: Object,
    commit: Object,
})

console.log(props.commit)
const Test = (index) => {

    console.log(index)

}

const currPath = ref("")
const array = ref(Array(props.repo_tree.length).fill(0))
const arrayDirectoryPath = ref([])

const getFolderArray = () => {}

const handleFolderExpansion = (index) => {
    array.value[index] = array.value[index] === 0 ? 1 : 0
};

onMounted(() => {
    const subDirectories = props.currFolder.split('/')
    subDirectories.map((subDir, index) => {
        const directory = {
            index: index,
            folder: subDir,
            path: subDirectories.slice(0, index + 1).join('/')
        }
        arrayDirectoryPath.value.push(directory)
    })

    console.log(arrayDirectoryPath.value);
})

</script>
