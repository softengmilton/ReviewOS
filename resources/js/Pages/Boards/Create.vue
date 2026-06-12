<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import FormField from '../../Components/FormField.vue';

const form = useForm({ name: '', description: '', type: 'public' });
</script>

<template>
    <AppLayout>
        <form class="panel grid" style="max-width: 720px;" @submit.prevent="form.post(route('boards.store'))">
            <h2 style="margin: 0;">New board</h2>
            <FormField label="Name" :error="form.errors.name"><input v-model="form.name" class="input"></FormField>
            <FormField label="Description" :error="form.errors.description"><textarea v-model="form.description" class="textarea"></textarea></FormField>
            <FormField label="Type" :error="form.errors.type">
                <select v-model="form.type" class="select">
                    <option value="public">Public</option>
                    <option value="private">Private</option>
                    <option value="unlisted">Unlisted</option>
                </select>
            </FormField>
            <button class="button" type="submit">Create board</button>
        </form>
    </AppLayout>
</template>
