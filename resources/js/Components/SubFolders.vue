<template>
    <ul v-if="folder_tree"
        class="px-4 mt-4">
        <li v-for="(item, index) in folder_tree" 
            :key="item.id"
            class="flex gap-2">
            
            
        </li> 
    </ul>
    <button @click="fetchData">Fetch Data</button>

</template>


<script setup> 

    import { defineProps, ref, onMounted } from 'vue';
    import { Inertia } from '@inertiajs/inertia';

    const props = defineProps({
        folder_tree: Object,
    })

const data = ref([]);
const loading = ref(true);
const error = ref(null);
const repo = 'My-First-Repo';
const folder = 'Calendar';

const fetchData = async (folder) => {
    const url = `/fileTree/${repo}/${folder}`; // Example URL
    try {
        const response = await fetch(url);
        
        const text = await response.text();
        console.log('Response Text:', text); 
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
};



</script>