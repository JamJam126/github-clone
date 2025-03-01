<template>
    <AuthenticatedLayout>
        <Head title="Create" />

        <div class="flex flex-col mt-[2.5rem] items-center w-full">
            <form
                @submit.prevent="createRepository"
                class="max-w-[768px] w-full px-[32px]"
            >
                <div class="border-b-[#8b949e] border-b pb-2">
                    <h2 class="font-medium text-2xl">
                        Create a new repository
                    </h2>
                    <span class="text-sm text-[#8b949e]"
                        >A repository contains all project files, including the
                        revision history.</span
                    >
                </div>

                <div class="mt-1 flex flex-col border-b border-b-[#8b949e]">
                    <i class="text-sm"
                        >Required fields are marked with an asterisk (*).
                    </i>
                    <!-- <label for="name">Repository Name</label>
                    <input class="text-black" type="text" id="name" v-model="form.name" required /> -->
                    <InputLabel
                        for="name"
                        value="Repository Name *"
                        class="text-white mt-1"
                    />
                    <TextInput
                        class="text-white w-[220px] bg-transparent border border-[#8b949e] py-[0.3rem] my-1"
                        type="text"
                        id="name"
                        required
                        v-model="form.name"
                        placeholder="Repo Name"
                    />
                    <span class="text-sm my-1"
                        >Great repository names are short and memorable.</span
                    >
                    <div class="error" v-if="form.errors.name">
                        {{ form.errors.name }}
                    </div>
                    <InputLabel
                        for="description"
                        value="Descripion"
                        class="text-white"
                    />

                    <TextInput
                        class="text-white bg-transparent border border-[#8b949e] py-[0.3rem] my-1 mb-3"
                        type="text"
                        id="description"
                        v-model="form.description"

                        placeholder="Description"
                    />

                    <!-- <label for="description">Description</label> -->
                    <!-- <textarea class="text-black" id="description" v-model="form.description"></textarea> -->
                    <div class="error" v-if="form.errors.description">
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="my-2">
                    <div class="flex flex-row gap-2 items-center my-3">
                        <input
                            v-model="form.visibility"
                            id="publicRepo"
                            type="radio"
                            name="visibility"
                            checked
                            value="Public"
                        />
                        <svg
                            aria-hidden="true"
                            focusable="false"
                            class="fill-[#9198a1] w-[24px] h-auto"
                            viewBox="0 0 24 24"
                            width="24"
                            height="24"
                            fill="currentColor"
                            style="
                                display: inline-block;
                                user-select: none;
                                vertical-align: text-bottom;
                                overflow: visible;
                            "
                        >
                            <path
                                d="M3 2.75A2.75 2.75 0 0 1 5.75 0h14.5a.75.75 0 0 1 .75.75v20.5a.75.75 0 0 1-.75.75h-6a.75.75 0 0 1 0-1.5h5.25v-4H6A1.5 1.5 0 0 0 4.5 18v.75c0 .716.43 1.334 1.05 1.605a.75.75 0 0 1-.6 1.374A3.251 3.251 0 0 1 3 18.75ZM19.5 1.5H5.75c-.69 0-1.25.56-1.25 1.25v12.651A2.989 2.989 0 0 1 6 15h13.5Z"
                            ></path>
                            <path
                                d="M7 18.25a.25.25 0 0 1 .25-.25h5a.25.25 0 0 1 .25.25v5.01a.25.25 0 0 1-.397.201l-2.206-1.604a.25.25 0 0 0-.294 0L7.397 23.46a.25.25 0 0 1-.397-.2v-5.01Z"
                            ></path>
                        </svg>
                        <div class="flex flex-col">
                            <label for="publicRepo">Public</label>
                            <span class="text-sm text-[#8b949e] leading-4"
                                >Anyone on the internet can see this repository.
                                You choose who can commit.
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-row gap-2 items-center my-3">
                        <input
                            v-model="form.visibility"
                            type="radio"
                            id="privateRepo"
                            name="visibility"
                            value="Private"
                        />

                        <svg
                            aria-hidden="true"
                            focusable="false"
                            class="fill-[#9198a1] w-[24px] h-auto"
                            viewBox="0 0 24 24"
                            width="24"
                            height="24"
                            fill="currentColor"
                            style="
                                display: inline-block;
                                user-select: none;
                                vertical-align: text-bottom;
                                overflow: visible;
                            "
                        >
                            <path
                                d="M6 9V7.25C6 3.845 8.503 1 12 1s6 2.845 6 6.25V9h.5a2.5 2.5 0 0 1 2.5 2.5v8a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 19.5v-8A2.5 2.5 0 0 1 5.5 9Zm-1.5 2.5v8a1 1 0 0 0 1 1h13a1 1 0 0 0 1-1v-8a1 1 0 0 0-1-1h-13a1 1 0 0 0-1 1Zm3-4.25V9h9V7.25c0-2.67-1.922-4.75-4.5-4.75-2.578 0-4.5 2.08-4.5 4.75Z"
                            ></path>
                        </svg>
                        <div class="flex flex-col">
                            <label for="privateRepo">Private</label>
                            <span class="text-sm text-[#8b949e] leading-4"
                                >You choose who can see and commit to this
                                repository.
                            </span>
                        </div>
                    </div>
                    <div v-if="form.errors.visibility" class="error">
                        {{ form.errors.visibility }}
                    </div>
                </div>
                <div class="flex flex-row-reverse my-5">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-[#1f6feb] font-medium p-2 rounded-md text-sm"
                    >
                        Create Repository
                    </button>
                </div>
            </form>

            <div class="text-green-600" v-if="successMessage">
                {{ successMessage }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";

// const form = useForm({
//     name: ''
// })

// function createRepo() {
//     console.log(form.name);
// }

const form = useForm({
    name: "",
    description: "",
    visibility: "Public",
});

const successMessage = ref("");

const createRepository = () => {
    form.post(route("repos.store"), {
        onSuccess: () => {
            successMessage.value = `The repository "${form.name}" was created successfully!`;
            form.reset();
        },
        onError: () => {
            document.getElementById("name").focus();
        },
    });
};
</script>

<style scoped>
.error {
    color: red;
    margin-top: 5px;
}
</style>
