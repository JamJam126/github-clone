<template>
    <div class="h-full w-full">
        <Link class="flex text-blue-500 cursor-pointer hover:underline" 
              :href="route('repo.show', {
                    user: repo.user,
                    repo: repo.name,
                })"
        >
            <h1 class="font-medium"
            >
                {{ repo.user }}/
            </h1>
            <h1 class="font-bold"
            >
                {{ repo.name }}
            </h1>
        </Link>

        <h1 class="text-sm mt-1" 
            v-if="repo.description"
        >
            {{ repo.description }}
        </h1>

        <div class="mt-2 h-[22px] w-full "
        >
            <h1 class="text-sm">

            </h1>
        </div>

        <div class="w-full text-[#9198a1] mt-3 flex gap-2 text-xs "
        >
            <div class="flex gap-1 items-center">
                <svg v-if="!starStatus" 
                     xmlns="http://www.w3.org/2000/svg" 
                     width="18" height="18" viewBox="0 0 24 24"
                     fill="yellow"
                >
                    <path fill="#9198a1"
                        d="m8.85 16.825l3.15-1.9l3.15 1.925l-.825-3.6l2.775-2.4l-3.65-.325l-1.45-3.4l-1.45 3.375l-3.65.325l2.775 2.425zm3.15.45l-4.15 2.5q-.275.175-.575.15t-.525-.2t-.35-.437t-.05-.588l1.1-4.725L3.775 10.8q-.25-.225-.312-.513t.037-.562t.3-.45t.55-.225l4.85-.425l1.875-4.45q.125-.3.388-.45t.537-.15t.537.15t.388.45l1.875 4.45l4.85.425q.35.05.55.225t.3.45t.038.563t-.313.512l-3.675 3.175l1.1 4.725q.075.325-.05.588t-.35.437t-.525.2t-.575-.15zm0-5.025" />
                </svg>

                <h1 class="content-center"
                >
                    {{ repo.total_stars }}
                </h1>
            </div>

            <h1 class="items-center">
                ·
            </h1>
            <h1>Updated</h1>
        </div>
    </div>


</template>

<script setup>

import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    repo: Object
})
</script>