<template>

    <form class="text-white h-8 w-[260px]">
        <TextInput 
            type="text" 
            class="h-8 w-full text-black" 
            v-model="form.search" 
            autocomplete 
            placeholder="Type /" />
    </form>
</template>

<script setup>
    import TextInput from './TextInput.vue';
    import { useForm } from '@inertiajs/vue3';

    const form = useForm({
        search: ''
    })
</script>