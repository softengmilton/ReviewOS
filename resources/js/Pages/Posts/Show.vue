<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';
import FormField from '../../Components/FormField.vue';
import { route } from '../../../../vendor/tightenco/ziggy/dist/index.esm.js';

const props = defineProps({ post: Object, statuses: Array });
const commentForm = useForm({ content: '' });
const statusForm = useForm({ status_id: props.post.status_id });
const voteForm = useForm({ direction: 'up' });

function vote(direction) {
    voteForm.direction = direction;
    voteForm.post(route('posts.vote', { post: props.post.id }), {
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout>
        <article class="panel grid">
            <div style="display: flex; justify-content: space-between; gap: 16px;">
                <div>
                    <div style="color: #64748b;">{{ post.board?.name }}</div>
                    <h2 style="margin: 4px 0 0;">{{ post.title }}</h2>
                </div>
                <Link class="button secondary" :href="route('posts.edit', { post: post.id })">Edit</Link>
            </div>
            <p style="white-space: pre-wrap;">{{ post.content }}</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                <button class="button secondary" type="button" @click="vote('up')">Upvote {{ post.upvotes_count }}</button>
                <button class="button secondary" type="button" @click="vote('down')">Downvote {{ post.downvotes_count }}</button>
                <form style="display: flex; gap: 8px;" @submit.prevent="statusForm.put(route('posts.update', { post: post.id }))">
                    <select v-model="statusForm.status_id" class="select" style="min-width: 180px;">
                        <option v-for="status in statuses" :key="status.id" :value="status.id">{{ status.name }}</option>
                    </select>
                    <button class="button" type="submit">Change status</button>
                </form>
            </div>
        </article>

        <section class="panel grid" style="margin-top: 18px;">
            <h3 style="margin: 0;">Comments</h3>
            <form class="grid" @submit.prevent="commentForm.post(route('posts.comments.store', { post: post.id }), { onSuccess: () => commentForm.reset() })">
                <FormField label="Add comment" :error="commentForm.errors.content">
                    <textarea v-model="commentForm.content" class="textarea"></textarea>
                </FormField>
                <button class="button" type="submit">Comment</button>
            </form>
            <div v-for="comment in post.comments" :key="comment.id" style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                <div style="font-weight: 800;">{{ comment.author?.name || comment.author?.email || 'User' }}</div>
                <p style="margin: 6px 0 0;">{{ comment.content }}</p>
            </div>
        </section>
    </AppLayout>
</template>
