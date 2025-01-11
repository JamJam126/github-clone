

<template>
    <nav class="h-16 flex pl-2 items-center border-b gap-10">
        <Link href="/home" class="flex flex-row items-center gap-4">
            <ApplicationLogo class="h-[60px] w-[60px] fill-current text-gray-500"/>
            Github
        </Link>
            

        <Search />
        
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