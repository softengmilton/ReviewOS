<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ board: Object, posts: Object });
</script>

<template>
    <AppLayout>
        <section class="panel grid">
            <div style="display: flex; justify-content: space-between; gap: 12px; align-items: center;">
                <div>
                    <h2 style="margin: 0;">{{ board.name }}</h2>
                    <p style="margin-bottom: 0; color: #475569;">{{ board.description }}</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <Link class="button secondary" :href="route('boards.edit', { board: board.id })">Edit</Link>
                    <Link class="button" :href="route('boards.posts.create', { board: board.id })">New post</Link>
                </div>
            </div>
            <article v-for="post in posts.data" :key="post.id" style="border-top: 1px solid #e5e7eb; padding-top: 14px;">
                <Link :href="route('posts.show', { post: post.id })" style="font-size: 18px; font-weight: 800;">{{ post.title }}</Link>
                <p style="color: #475569;">{{ post.content }}</p>
                <div style="display: flex; gap: 12px; color: #64748b;">
                    <span>{{ post.status?.name || 'No status' }}</span>
                    <span>{{ post.upvotes_count }} upvotes</span>
                    <span>{{ post.comments_count }} comments</span>
                </div>
            </article>
        </section>
    </AppLayout>
</template>
