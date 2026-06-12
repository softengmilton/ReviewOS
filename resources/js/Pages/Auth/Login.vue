<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import FormField from '../../Components/FormField.vue';

const form = useForm({ email: '', password: '', remember: false });
</script>

<template>
    <main style="min-height: 100vh; display: grid; place-items: center; padding: 24px;">
        <form class="panel grid" style="width: min(440px, 100%);" @submit.prevent="form.post(route('login'))">
            <h1 style="margin: 0; font-size: 28px;">Sign in</h1>
            <FormField label="Email" :error="form.errors.email">
                <input v-model="form.email" class="input" type="email" autocomplete="email">
            </FormField>
            <FormField label="Password" :error="form.errors.password">
                <input v-model="form.password" class="input" type="password" autocomplete="current-password">
            </FormField>
            <label style="display: flex; gap: 8px; align-items: center;">
                <input v-model="form.remember" type="checkbox"> Remember me
            </label>
            <button class="button" type="submit" :disabled="form.processing">Sign in</button>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <Link :href="route('register')">Create account</Link>
                <a :href="route('oauth.redirect', { provider: 'google' })">Google</a>
                <a :href="route('oauth.redirect', { provider: 'github' })">GitHub</a>
            </div>
        </form>
    </main>
</template>
