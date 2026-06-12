<script setup>
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <header style="display: flex; justify-content: space-between; gap: 16px; margin-bottom: 24px;">
        <div>
            <div style="font-size: 14px; color: #64748b;">{{ page.props.organization?.name }}</div>
            <h1 style="margin: 4px 0 0; font-size: 28px;">{{ page.props.title || 'Workspace' }}</h1>
        </div>
        <div style="font-size: 14px;">{{ page.props.auth?.user?.name }}</div>
    </header>
</template>
