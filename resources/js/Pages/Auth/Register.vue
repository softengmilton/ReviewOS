<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import FormField from '../../Components/FormField.vue';

const form = useForm({
    name: '',
    email: '',
    organization_name: '',
    password: '',
    password_confirmation: '',
});
</script>

<template>
    <main style="min-height: 100vh; display: grid; place-items: center; padding: 24px;">
        <form class="panel grid" style="width: min(520px, 100%);" @submit.prevent="form.post(route('register'))">
            <h1 style="margin: 0; font-size: 28px;">Create workspace</h1>
            <FormField label="Name" :error="form.errors.name"><input v-model="form.name" class="input"></FormField>
            <FormField label="Email" :error="form.errors.email"><input v-model="form.email" class="input" type="email"></FormField>
            <FormField label="Organization" :error="form.errors.organization_name"><input v-model="form.organization_name" class="input"></FormField>
            <FormField label="Password" :error="form.errors.password"><input v-model="form.password" class="input" type="password"></FormField>
            <FormField label="Confirm password"><input v-model="form.password_confirmation" class="input" type="password"></FormField>
            <button class="button" type="submit" :disabled="form.processing">Create account</button>
            <Link :href="route('login')">Already have an account?</Link>
        </form>
    </main>
</template>
