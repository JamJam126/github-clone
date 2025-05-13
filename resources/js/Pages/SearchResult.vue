<template>
    <div v-for="res in result">
        {{ res.name }}
    </div>
    
</template>

<script setup>

import { defineProps } from 'vue';

const props = defineProps ({
    query: Object,
    result: Array
})

</script>