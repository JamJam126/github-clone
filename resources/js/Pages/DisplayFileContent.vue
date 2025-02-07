<template>
    <AuthenticatedLayout>
        {{ content }}
    </AuthenticatedLayout>
</template>

<script setup>

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { defineProps } from 'vue';

    const props = defineProps({
        content: Object,

        test: Object
    })

    
    console.log(props.content)

</script>