<template>
    <ul v-if="folder_tree"
        class="px-4 mt-4">
        <li v-for="item in folder_tree"
            clas="flex gap-2">
            <div v-on:click="" v-if="item.type == 'folder'"
                class="h-8 flex hover:cursor-pointer">
                <svg v-if="item.type==='folder'"
                    class="h-6 w-7"
                xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"><path fill="currentColor" d="M12.6 12L8.7 8.1q-.275-.275-.275-.7t.275-.7t.7-.275t.7.275l4.6 4.6q.15.15.213.325t.062.375t-.062.375t-.213.325l-4.6 4.6q-.275.275-.7.275t-.7-.275t-.275-.7t.275-.7z"/></svg>
                <div v-if="item.type === 'folder'"
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
                    {{ item.name }}</div
                >
            </div>
            <div v-if="item.type === 'file'"
                class="h-8 ml-7 flex gap-1.5"
                >
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    class="text-[#9198a1]"
                    >
                    <path fill="currentColor"
                            d="M19 19H8q-.825 0-1.412-.587T6 17V3q0-.825.588-1.412T8 1h6.175q.4 0 .763.15t.637.425l4.85 4.85q.275.275.425.638t.15.762V17q0 .825-.587 1.413T19 19m0-11h-3.5q-.625 0-1.062-.437T14 6.5V3H8v14h11zM4 23q-.825 0-1.412-.587T2 21V8q0-.425.288-.712T3 7t.713.288T4 8v13h10q.425 0 .713.288T15 22t-.288.713T14 23zM8 3v5zv14z"
                            />
                </svg>
                {{ item.name }}</div
            >

        </li>
    </ul>

</template>


<script setup>

    import { defineProps } from 'vue';

    const props = defineProps({
        folder_tree: Object,

    })
    console.log(props.folder_tree)

</script>
