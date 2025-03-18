<template>

    <div class=" h-7 inline-flex items-center rounded-md 
                border border-transparent border-zinc-700 bg-gray-800 text-xs 
                font-semibold tracking-widest text-white transition duration-150 ease-in-out "
    >
        <button class="px-4 h-full font rounded-s-md hover:bg-gray-700 
                    focus:bg-gray-700 focus:outline-none focus:ring-1 
                    focus:ring-indigo-500 focus:ring-offset-1 
                    active:bg-gray-900"
        >
            <slot /> 
        </button>

        <div class="border-l border-l-zinc-700 px-2 py-2  rounded-e-md
                    hover:bg-gray-700 focus:bg-gray-700 focus:outline-none 
                    focus:ring-1 focus:ring-indigo-500 focus:ring-offset-1 
                    active:bg-gray-900"
        >
            <svg class=''
                v-bind:style= "'transform: rotate(90deg);'"
                xmlns="http://www.w3.org/2000/svg"
                width="12" height="12" 
                viewBox="0 0 24 24"
                >
                <path fill="currentColor" 
                        d="M12.6 12L8.7 8.1q-.275-.275-.275-.7t.275-.7t.7-.275t.7.275l4.6 4.6q.15.15.213.325t.062.375t-.062.375t-.213.325l-4.6 4.6q-.275.275-.7.275t-.7-.275t-.275-.7t.275-.7z"
                        />
            </svg>       
        </div>

    </div>
</template>

<script setup>

</script>