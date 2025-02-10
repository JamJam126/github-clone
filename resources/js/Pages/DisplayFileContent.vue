<template>
    <AuthenticatedLayout>
        <!-- <pre v-if="content" 
            class="w-[1200px] h-[90%] flex bg-black border border-gray-500"
            name="" 
            id="testlg" 
            >

            <code class="language-java">
                {{ content }}
            </code>

        </pre> -->
        <div class="w-full">
            <prism :content=content language="css"/>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { defineProps } from 'vue';
    import Prism from '@/Components/Prism.vue';

    const props = defineProps({
        content: Object,

        test: Object
    })


    console.log(props.content)
</script>

