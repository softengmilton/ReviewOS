<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <aside class="sidebar">
        <div style="font-weight: 800; font-size: 20px; margin-bottom: 24px;">FeedbackOS</div>
        <nav class="grid">
            <Link :href="route('dashboard')">Dashboard</Link>
            <Link :href="route('boards.index')">Boards</Link>
            <Link :href="route('billing.index')">Billing</Link>
            <Link :href="route('logout')" method="post" as="button" class="button secondary">Logout</Link>
        </nav>
    </aside>
</template>
