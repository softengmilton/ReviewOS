<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../Layouts/AppLayout.vue';

defineProps({ metrics: Object, recentPosts: Array });
</script>

<template>
    <AppLayout>
        <section class="grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); margin-bottom: 18px;">
            <div v-for="(value, key) in metrics" :key="key" class="panel">
                <div style="color: #64748b; text-transform: capitalize;">{{ key }}</div>
                <div style="font-size: 30px; font-weight: 800;">{{ value }}</div>
            </div>
        </section>
        <section class="panel grid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0;">Recent feedback</h2>
                <Link class="button" :href="route('boards.index')">Open boards</Link>
            </div>
            <div v-for="post in recentPosts" :key="post.id" style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                <Link :href="route('posts.show', { post: post.id })" style="font-weight: 800;">{{ post.title }}</Link>
                <div style="color: #64748b;">{{ post.board?.name }} · {{ post.status?.name || 'No status' }}</div>
            </div>
        </section>
    </AppLayout>
</template>
