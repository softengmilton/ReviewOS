<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import FormField from '../../Components/FormField.vue';

const props = defineProps({ board: Object });
const form = useForm({
    name: props.board.name,
    description: props.board.description,
    type: props.board.type,
    is_active: props.board.is_active,
});
</script>

<template>
    <AppLayout>
        <form class="panel grid" style="max-width: 720px;" @submit.prevent="form.put(route('boards.update', { board: board.id }))">
            <h2 style="margin: 0;">Edit board</h2>
            <FormField label="Name" :error="form.errors.name"><input v-model="form.name" class="input"></FormField>
            <FormField label="Description"><textarea v-model="form.description" class="textarea"></textarea></FormField>
            <FormField label="Type"><select v-model="form.type" class="select"><option>public</option><option>private</option><option>unlisted</option></select></FormField>
            <label style="display: flex; gap: 8px;"><input v-model="form.is_active" type="checkbox"> Active</label>
            <button class="button" type="submit">Save board</button>
        </form>
    </AppLayout>
</template>
