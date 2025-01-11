<template>
    <AuthenticatedLayout>
        <!-- Side Bar -->
        <div class="main-side border-r h-full flex flex-col px-6 pt-4">
            <div class="mt-8">
                <h2 class="mb-1 text-sm font-semibold">Create your first project</h2>
                <p 
                    class="text-sm mb-4" 
                    style="line-height: 21px;"
                >
                    Ready to start building? Create a repository for a new idea or bring over an existing repository to keep contributing to it.
                </p>
                <div>
                    <Link
                        href="new" 
                        class="h-5 text-white font-medium rounded-md text-sm px-4 py-[6px] bg-[#238536] box-content"
                        as="button"
                        style="border: solid 0.8px rgba(255, 255, 255, 0.1);"
                    >
                        Create repository
                    </Link>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-grow flex items-center justify-center">
            <div>Home Page</div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Link } from '@inertiajs/vue3';

</script>