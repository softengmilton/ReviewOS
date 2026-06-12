<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import FormField from '../../Components/FormField.vue';

const props = defineProps({ board: Object });
const form = useForm({ title: '', content: '' });
</script>

<template>
    <AppLayout>
        <form class="panel grid" style="max-width: 760px;" @submit.prevent="form.post(route('boards.posts.store', { board: board.id }))">
            <h2 style="margin: 0;">Submit feedback to {{ props.board.name }}</h2>
            <FormField label="Title" :error="form.errors.title"><input v-model="form.title" class="input"></FormField>
            <FormField label="Details" :error="form.errors.content"><textarea v-model="form.content" class="textarea"></textarea></FormField>
            <button class="button" type="submit">Submit post</button>
        </form>
    </AppLayout>
</template>
