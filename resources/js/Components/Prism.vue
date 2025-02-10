<template>
    <div class="w-full">
        <pre v-if="content" class="code-container w-full h-[90%] flex bg-black border border-gray-500">
            <code ref="codeBlock" class="language-java">{{ content }}</code>
        </pre>
    </div>
</template>

<script setup>
    import { onMounted, ref, watch } from 'vue';
    import Prism from 'prismjs';
    import "prismjs/themes/prism.css";
    import 'prismjs/components/prism-java.min.js'; 

    const props = defineProps({
        content: String
    });

    const codeBlock = ref(null);

    const highlightCode = () => {
        if (codeBlock.value) {
            Prism.highlightElement(codeBlock.value);
        }
    };

    onMounted(highlightCode);

    watch(() => props.content, highlightCode); 
</script>

<style scoped>
.code-container {
    background-color: #2d2d2d;
    padding: 1rem;
    border-radius: 8px;
}

.code-container code {
    color: #f8f8f2;
    text-shadow: none;
}

</style>
