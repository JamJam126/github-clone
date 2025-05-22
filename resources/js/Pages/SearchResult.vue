<template>
    <AuthenticatedLayout>
        <div class="flex h-full w-full">
            <!-- <div v-for="res in result">
                {{ res.name }}
            </div> -->

            <div class="w-[297px] border-r border-r-[#3d444d] ">
                <h1 class="font-semibold p-4">Filter by</h1>
                <div class="px-4 py-1 flex flex-col">
                    <a class="px-2 py-1.5 text-sm rounded-lg hover:bg-[#1a1d1f] cursor-pointer"
                        v-for="filterType in filters"
                        v-bind:style="[type === filterType ? 'font-weight: 600; background-color: #17181a;' : 'font-base']"
                        @click="handleSelected(filterType)"
                    >
                        {{ filterType }}
                    </a>
                </div>
            </div>

            <div class="flex-1 px-6 py-4 h-full w-full flex flex-col">
                <div class="content-center">
                    <h1 class="font-semibold h-7 content-center">
                        {{ result.length }} results
                    </h1>
                </div>
                <div class="h-full pt-4">
                    <div class="w-full h-full flex flex-col gap-4">
                        
                        <div v-if="type === 'Repos'"
                             v-for="repo in result"
                             class="p-4 border border-[#3d444d] rounded-md"
                        >
                            <RepoSearchResult :repo="repo"/>
                        </div>

                        <div v-if="type === 'Users'"
                             v-for="user in result"
                             class="p-4 border border-[#3d444d] rounded-md w-full h-[78px]"
                        >
                            <!-- 
                                Link to profile page once it exist.
                            -->
                            <Link class="text-blue-500 font-semibold hover:underline">
                                {{ user.name }}
                            </Link>
                            
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    
</template>

<script setup>

import RepoSearchResult from '@/Components/RepoSearchResult.vue';
import { Link, router } from '@inertiajs/vue3';
import { defineProps , ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps ({
    query: Object,
    result: Array,
    type: String, 
})

const filters = ref(['Repos', 'Users', 'Test'])

const handleSelected = (type) => {
    router.get('/search', {
        q: props.query,
        type: type,
    })
}

</script>