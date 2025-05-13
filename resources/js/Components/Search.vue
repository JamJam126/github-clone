<template>

    <form class="text-white h-8 w-[260px]"
          @submit.prevent="submitSearch"
    >
        <TextInput 
            type="text" 
            class="h-8 w-full text-black" 
            v-model="form.search" 
            autocomplete="off"
            placeholder="Type /" />

    </form>
</template>

<script setup>
    import TextInput from './TextInput.vue';
    import { useForm , router } from '@inertiajs/vue3';

    const form = useForm({
        search: ''
    })

    const submitSearch = () => {
        if (form.search.trim()) router.get('/search', { 
            q: form.search,
            type: "User",
         })
    }
</script>