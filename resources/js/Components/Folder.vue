<template>
    <div v-if="type == 'folder'"
        class="h-8 flex cursor-pointer">
        <svg v-if="type === 'folder'"
            class='h-6 w-7'
            v-bind:style= "[array[index] === 1 ? 'transform: rotate(90deg);' : '']"
            xmlns="http://www.w3.org/2000/svg"
            width="12" height="12" 
            viewBox="0 0 24 24"
            @click="handleExpandClose(index)"
            >
            <path fill="currentColor" 
                    d="M12.6 12L8.7 8.1q-.275-.275-.275-.7t.275-.7t.7-.275t.7.275l4.6 4.6q.15.15.213.325t.062.375t-.062.375t-.213.325l-4.6 4.6q-.275.275-.7.275t-.7-.275t-.275-.7t.275-.7z"
                    />
        </svg>

        
            <div v-if="type === 'folder'"
                class="h-8"
                @click="test">
                <Link :href="route('repo.folderhandle', {
                        user: repo_owner,
                        repo: repo_name,
                        path: currPath,})"
                    class="flex gap-1.5 overflow-hidden h-full"
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
                    {{ name }}
                </Link>
            
        </div>        
    </div>
    <ul v-if="array[index] === 1"
        class="px-4">
        <div v-if="loading" 
            class="-mt-2 ml-4 mb-1 flex gap-1 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" 
                width="24" 
                height="24" 
                viewBox="0 0 24 24">
                <g fill="none" 
                    stroke="currentColor" 
                    stroke-linecap="round" 
                    stroke-linejoin="round" 
                    stroke-width="2">
                    <path stroke-dasharray="16" 
                        stroke-dashoffset="16" 
                        d="M12 3c4.97 0 9 4.03 9 9">
                        <animate fill="freeze" 
                                attributeName="stroke-dashoffset" 
                                dur="0.3s" 
                                values="16;0"
                        />
                        <animateTransform attributeName="transform" 
                                        dur="1.5s" 
                                        repeatCount="indefinite" 
                                        type="rotate" 
                                        values="0 12 12;360 12 12"
                        />
                    </path>
                    <path stroke-dasharray="64" 
                            stroke-dashoffset="64" 
                            stroke-opacity="0.3" 
                            d="M12 3c4.97 0 9 4.03 9 9c0 4.97 -4.03 9 -9 9c-4.97 0 -9 -4.03 -9 -9c0 -4.97 4.03 -9 9 -9Z"
                            >
                        <animate fill="freeze" 
                                attributeName="stroke-dashoffset" 
                                dur="1.2s" 
                                values="64;0"
                        />
                    </path>
                </g>
            </svg>
            <p>Loading</p>
        </div>
        <li v-for="(item, index) in folderTree" 
            :key="item.id"
            class="flex flex-col">
            <Folder :repo_owner="repo_owner"
                    :name="item.name" 
                    :type="item.type"
                    :repo_name="repo_name"
                    :index="index"
                    :file_id="item.id"
                    :array="currArray"
                    :folderPath="currPath"
                    @handle-expansion="handleFolderExpansion"
            />
            <!-- <p>
                {{ item.name }}
            </p> -->
        </li>
    </ul>

    <div v-if="type === 'file'"
        class="h-8 ml-7 flex gap-1.5 over"
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
        <p class="overflow-hidden">{{ name }}</p>
    </div>
</template>

<script setup>

    import { defineProps, defineEmits, ref, watch } from 'vue';
    import { Link } from '@inertiajs/vue3';

const props = defineProps({
    repo_owner: String,
    name: {
        type: String,
        required: true
    },
    file_id: {
        type: String,
        required: true
    },
    type: String,
    index: Number,
    repo_name: {
        type: String,
        required: true
    },
    array: Array,
    folderPath: String,
})

    const folderTree = ref([])
    const currArray = ref([])
    const loading = ref([false])
    const currPath = props.folderPath === '' 
                    ? ref(props.name) 
                    : ref(props.folderPath + '/' + props.name)
    const emit = defineEmits(['handle-expansion'])

    const repo = props.repo_name
    const folder = props.name

    const fetchData = async () => {
        const url = `/fileTree/${repo}/${folder}/${props.file_id}`
        try {
            const response = await fetch(url)
            
            const data = await response.json()
            console.log('Response Data:', data)
            folderTree.value = data[0]
            loading.value = false
            currArray.value = Array(data[0].length).fill(0)
            if (!response.ok) {
                throw new Error('Network response was not ok')
            }

        } catch (error) {
            console.error('Error fetching data:', error)
        }
    };
    
    const handleFolderExpansion = (index) => {
        currArray.value[index] = currArray.value[index] === 0 ? 1 : 0
    }
    
    const handleExpandClose = async (index) => {
        emit('handle-expansion', index)
        if (!folderTree.value || folderTree.value.length === 0) {
            loading.value = true
            await fetchData();
        }
        if (currArray.value[index] === 1) {
            closeAllNestedFolders(index)
        }
    }

    const closeAllNestedFolders = (index) => {

        currArray.value[index] = 0
        if (folderTree.value[index].children.length > 0) {
            folderTree.value[index].children.forEach((_, childIndex) => {
                closeAllNestedFolders(childIndex)
            })
        }
    }

    const debugging = () => {
        console.log(currPath.value)
    }

</script>
