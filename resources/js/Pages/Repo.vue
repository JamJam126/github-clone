<template>
    <AuthenticatedLayout>

        {{ info.user_name }}
    </AuthenticatedLayout>

</template>

<script setup> 

import { defineProps } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    info: Array,
});


</script>