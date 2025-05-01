<template>
    <AuthenticatedLayout :hideDefaultNav="true">
        <div class="w-full">
            <RepoNav/>
            <main class="w-full h-full p-6 flex justify-center">
                <div class="w-[1280px] h-full">
                    <header class="text-2xl">
                        Commits
                    </header>
                    <div class="w-full h-[1px] bg-slate-600 my-2.5"></div>
                    <div class="py-[10px]">
                        <div class="flex gap-1 items-center">
                            <p class="text-sm">History for</p>
                            <ul class="flex gap-1">
                                <li> 
                                    <Link class="flex gap-2 text-blue-400"
                                        :href="route('repo.commithandle', {
                                            user: repo_owner,
                                            repo: repo.name,
                                            path: repoPath
                                        })"
                                    >
                                        {{ repo.name }}</Link
                                    >
                                </li>

                                <li class="flex gap-1"
                                    v-for="directory in arrayDirectoryPath" 
                                    :key="directory.index"
                                >
                                    <p>/</p>

                                    <Link class="flex gap-2 text-blue-400" 
                                        v-if="directory.index !== arrayDirectoryPath.length - 1"
                                        :href="route('repo.commithandle', {
                                            user: repo_owner,
                                            repo: repo.name,
                                            path: directory.path,})"
                                    >
                                        {{ directory.folder }}</Link
                                    >

                                    <p v-else>{{ directory.folder }}</p>
                                </li>
                            </ul>
                        </div>            
                        <div v-if="commits"class="flex flex-col mt-[23px]">
                            <div v-for="[label, group] in Object.entries(groupByDate)" 
                                :key="label"
                                class="relative flex pb-1">
                                <div class="absolute left-[16px] top-2 w-[2px] h-full bg-slate-600"></div>
                                <div class="z-10">
                                    <svg class="mx-[7px] bg-black h-8" 
                                        xmlns="http://www.w3.org/2000/svg" 
                                        width="20" 
                                        height="20" 
                                        viewBox="0 0 24 24"
                                    >
                                        <path fill="currentColor"
                                            d="M12 17q-1.825 0-3.187-1.137T7.1 13H3q-.425 0-.712-.288T2 12t.288-.712T3 11h4.1q.35-1.725 1.713-2.863T12 7t3.188 1.138T16.9 11H21q.425 0 .713.288T22 12t-.288.713T21 13h-4.1q-.35 1.725-1.712 2.863T12 17m0-2q1.25 0 2.125-.875T15 12t-.875-2.125T12 9t-2.125.875T9 12t.875 2.125T12 15" />
                                    </svg>
                                </div>
                                <div class="ml-2 w-full">
                                    <p class="text-sm py-1 text-gray-400 mb-[10px]">
                                        Commits on {{ label }}
                                    </p>
                                    <div class="border border-gray-600 rounded-md">
                                        <div v-for="(commit, index) in group"
                                            :key="commit.id"
                                            :class="[
                                                'h-[65px] p-2 pl-4 font-medium flex flex-col justify-between',
                                                index !== 0 ? 'border-t border-t-gray-600' : ''
                                            ]" 
                                        >
                                            <Link class=""
                                                  :href="route('repo.commit.view', {
                                                        user: repo_owner,
                                                        repo: repo.name,
                                                        commit: commit.id,
                                                        path: currPath})"
                                            >
                                                {{ commit.message }}
                                            </Link>
                                            <p class="text-xs font-normal text-gray-400">{{ commit.name }} commited on {{ label }}</p>
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <div class="relative flex pb-1">
                                <div class="z-10">
                                    <svg class="mx-[7px] bg-black h-8" 
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                        <path fill="currentColor"
                                            d="M12 17q-1.825 0-3.187-1.137T7.1 13H3q-.425 0-.712-.288T2 12t.288-.712T3 11h4.1q.35-1.725 1.713-2.863T12 7t3.188 1.138T16.9 11H21q.425 0 .713.288T22 12t-.288.713T21 13h-4.1q-.35 1.725-1.712 2.863T12 17m0-2q1.25 0 2.125-.875T15 12t-.875-2.125T12 9t-2.125.875T9 12t.875 2.125T12 15" />
                                    </svg>
                                </div>
                                <div class="ml-4 w-full">
                                    <p class="text-sm py-1 text-gray-400">Ends of commit history for this file</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        
    </AuthenticatedLayout>

</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import RepoNav from '@/Components/RepoNav.vue';
import { computed, defineProps, onMounted, ref } from 'vue';

const props = defineProps({
    commits: Object,
    currPath: String,
    repo: Object,
    repo_owner: Object,
})

const arrayDirectoryPath = ref([])
const repoPath = ref("")

const getShortLabel = (dateStr) => {
    const date = new Date(dateStr)
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: '2-digit',
    })
}

const groupByDate = computed(() => {
    const groups = {}
    for (const item of props.commits) 
    {
        const label = getShortLabel(item.created_at)

        if (!groups[label]) groups[label] = []
        groups[label].push(item)
    }
    return groups
})

onMounted(() => {
    const subDirectories = props.currPath.split('/')
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
