<template>
    <div v-if="type == 'folder'"
        class="h-8 flex"
        @click="handleExpandClose(index)">
        <svg v-if="type === 'folder'"
            class="h-6 w-7"
            xmlns="http://www.w3.org/2000/svg"
            width="12" height="12" 
            viewBox="0 0 24 24"
            >
            <path fill="currentColor" 
                    d="M12.6 12L8.7 8.1q-.275-.275-.275-.7t.275-.7t.7-.275t.7.275l4.6 4.6q.15.15.213.325t.062.375t-.062.375t-.213.325l-4.6 4.6q-.275.275-.7.275t-.7-.275t-.275-.7t.275-.7z"
                    />
        </svg>

        <div v-if="type === 'folder'"
            class="h-8 flex gap-1.5"

            >
            <svg xmlns="http://www.w3.org/2000/svg" 
                width="24"
                height="24" 
                viewBox="0 0 24 24"
                class="text-[#9198a1]"
                >
                <path fill="currentColor" 
                    d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20z"
                        />
            </svg>
            {{ name }}</div
        >
    </div>

    <ul v-if="array[index] === 1"
        class="px-4">
        <li v-for="(item, index) in folderTree" 
            :key="item.id"
            class="flex flex-col">
            <Folder :name="item.name" 
                    :type="item.type"
                    :index="index"
                    :array="array"
                    @handle-expansion="handleFolderExpansion"
            />
            <!-- <p>
                {{ item.name }}
            </p> -->
        </li> 
    </ul>


    <div v-if="type === 'file'"
        class="h-8 ml-7 flex gap-1.5"
        >
        <svg xmlns="http://www.w3.org/2000/svg" 
            width="24" 
            height="24" 
            viewBox="0 0 24 24"
            class="text-[#9198a1] h-6 w-6"
            >
            <path fill="currentColor" 
                    d="M19 19H8q-.825 0-1.412-.587T6 17V3q0-.825.588-1.412T8 1h6.175q.4 0 .763.15t.637.425l4.85 4.85q.275.275.425.638t.15.762V17q0 .825-.587 1.413T19 19m0-11h-3.5q-.625 0-1.062-.437T14 6.5V3H8v14h11zM4 23q-.825 0-1.412-.587T2 21V8q0-.425.288-.712T3 7t.713.288T4 8v13h10q.425 0 .713.288T15 22t-.288.713T14 23zM8 3v5zv14z"
                    />
        </svg>
        <p>{{ name }}</p>
        </div
    >
</template>

<script setup>

    import { defineProps, defineEmits, ref } from 'vue';

    const props = defineProps ({
        name: String, 

        type: String,

        index: Number,

        array: Array,
    })

    const folderTree = ref([])

    const emit = defineEmits(['handle-expansion'])

    const repo = 'My-First-Repo';
    const folder = props.name;

    const Test = (index) => {
        console.log(index.value)
    }
    
    
    const fetchData = async () => {
        const url = `/fileTree/${repo}/${folder}`; // Example URL
        try {
            const response = await fetch(url);
            
            const data = await response.json();
            console.log('Response Data:', data);
            folderTree.value = data[0];
           
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

        } catch (error) {
            console.error('Error fetching data:', error);
        }
    };
    
    const handleExpandClose = async (index) => {
        emit('handle-expansion', index)
        if (!folderTree.value || folderTree.value.length === 0) {
            await fetchData();
        }
       // Test(index)
       
        // console.log(folderTree.value)
        console.log(index)
        // const testArray = folderTree.value.Array
        // console.log(testArray)
    }

    
</script>