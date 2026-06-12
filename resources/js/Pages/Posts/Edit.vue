<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import FormField from '../../Components/FormField.vue';

const props = defineProps({ post: Object, statuses: Array });
const form = useForm({ title: props.post.title, content: props.post.content, status_id: props.post.status_id });
</script>

<template>
    <AppLayout>
        <form class="panel grid" style="max-width: 760px;" @submit.prevent="form.put(route('posts.update', { post: post.id }))">
            <h2 style="margin: 0;">Edit post</h2>
            <FormField label="Title"><input v-model="form.title" class="input"></FormField>
            <FormField label="Details"><textarea v-model="form.content" class="textarea"></textarea></FormField>
            <FormField label="Status">
                <select v-model="form.status_id" class="select">
                    <option v-for="status in statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                </select>
            </FormField>
            <button class="button" type="submit">Save post</button>
        </form>
    </AppLayout>
</template>
