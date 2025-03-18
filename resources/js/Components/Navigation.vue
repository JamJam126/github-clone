

<template>
    <nav class="h-16 flex px-2 items-center gap-10 justify-between">
        <Link href="/home" class="flex flex-row items-center gap-4">
            <ApplicationLogo class="h-[60px] w-[60px] fill-current text-gray-500"/>
            LaraHub
        </Link>
            

        <!-- For changes in the future  -->
        <div class="pr-40">
            <Search />
        </div>
        
        
    </nav>

</template>

<script setup>
    import { Link } from '@inertiajs/vue3';
    import ApplicationLogo from './ApplicationLogo.vue';
    import Search from './Search.vue';
</script>

<style>
    nav {
        background-color: #010409;
        color: white;
        border-color: #3d444d;
    }
</style>