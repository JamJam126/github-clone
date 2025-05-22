<template>
    <nav class="border-b border-b-[#3d444d] px-4 box-content">
        <div class="h-16 flex px-2 items-center gap-10 justify-between">
            <Link href="/home" class="flex flex-row items-center gap-4">
            <ApplicationLogo @click="returnToHOME()"  class="h-[60px] hover:cursor-pointer w-[60px] fill-current text-gray-500"/>
            LaraHub
            </Link>

            <!-- For changes in the future  -->
            <div class="pr-40">
                <Search />
            </div>
        </div>

        <ul class="h-full flex items-center text-sm font-semibold">
            <li class="p-2 flex gap-[2px] border-b h-full items-center border-b-red-500"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m9 8l-4 4l4 4m6 0l4-4l-4-4"
                    />
                </svg>
                Code
            </li>
        </ul>
    </nav>
</template>

<script setup>
import ApplicationLogo from './ApplicationLogo.vue';
import Search from './Search.vue';
// DON'T LIKE TO MANUALLY DELETE URL TO TURN INTO HOME PAGE
function returnToHOME() {
    window.location.href = "/"
}
</script>
