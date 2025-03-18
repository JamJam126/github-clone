<template >
    <Head title="Welcome" />
    <div
        className="flex flex-col h-screen"
        v-if="$page.props.auth.user"
    >
        <Navigation />

        <main className="flex-grow text-white flex ">

            <slot></slot>
        </main>

        <!-- <footer
            class="py-16 text-center text-sm text-black"
        >
        Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
        </footer> -->
    </div>
    <div v-else class="login">
        <Link
            :href="route('login')"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
            >Log in
        </Link>
    </div>
    
</template>


<script setup>
import { Head, Link } from '@inertiajs/vue3';
import Navigation from '@/Components/Navigation.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

</script>

<style>
    * {
        font-family: -apple-system,BlinkMacSystemFont,"Segoe UI","Noto Sans",Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji";
    }

    main {
        /* color: white; */
        background-color: #010409;
    }

    .login {
        background-color: #0d1117;
    }

    .main-side {
        border-color: #3d444d;
        width: 336px;
        background-color: #0d1117;
    }
</style>