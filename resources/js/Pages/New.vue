<template>

    <AuthenticatedLayout>
        <Head title="Create" />
        
        <div class="flex flex-col justify-center items-center ">

            <form @submit.prevent="createRepository">
                <div>
                    <!-- <label for="name">Repository Name</label>
                    <input class="text-black" type="text" id="name" v-model="form.name" required /> -->
                    <InputLabel for="name" 
                                value="Repository Name" 
                                class="text-white" 
                                />
                    <TextInput  class="text-black" 
                                type="text" 
                                id="name" 
                                v-model="form.name" 
                                placeholder="Repo Name"
                    />

                    <div class="error" 
                        v-if="form.errors.name"
                        >
                        {{ form.errors.name }}
                    </div>
                </div>

                <div>
                    <InputLabel for="description" 
                                value="Descripion" 
                                class="text-white" 
                    />

                    <TextInput  class="text-black" 
                                type="text" 
                                id="description" 
                                v-model="form.description" 
                                placeholder="Description"
                    />
                    
                    <!-- <label for="description">Description</label> -->
                    <!-- <textarea class="text-black" id="description" v-model="form.description"></textarea> -->
                    <div class="error"
                        v-if="form.errors.description" 
                        >{{ form.errors.description }}</div
                    >

                </div>

                <div>
                    <label for="visibility">Visibility</label>

                    <select class="text-black" 
                            id="visibility" 
                            v-model="form.visibility" required
                            >
                        <option value="public">
                            Public
                        </option>
                        <option value="private">
                            Private
                        </option></select
                    >

                    <div v-if="form.errors.visibility" 
                        class="error"
                        >{{ form.errors.visibility }}</div
                    >

                </div>
                <button type="submit" 
                        :disabled="form.processing"
                        >Create Repository</button
                >
            </form>

            <div class="text-green-600"
                v-if="successMessage" 
                >{{ successMessage }}
            </div>

        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from "vue";
import { useForm } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

// const form = useForm({
//     name: ''
// })

// function createRepo() {
//     console.log(form.name);
// }


const form = useForm({
    name: '',
    description: '',
    visibility: 'public',
});

const successMessage = ref('');

const createRepository = () => {
    form.post(route('repos.store'), {
        onSuccess: () => {
            successMessage.value = `The repository "${form.name}" was created successfully!`;
            form.reset();
            
        },
        onError: () => {
            document.getElementById('name').focus();
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
