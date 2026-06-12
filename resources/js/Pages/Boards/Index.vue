<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ boards: Object });
</script>

<template>
    <AppLayout>
        <section class="panel grid">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <h2 style="margin: 0;">Feedback boards</h2>
                <Link class="button" :href="route('boards.create')">New board</Link>
            </div>
            <div v-for="board in boards.data" :key="board.id" style="border-top: 1px solid #e5e7eb; padding-top: 14px;">
                <Link :href="route('boards.show', { board: board.id })" style="font-size: 20px; font-weight: 800;">{{ board.name }}</Link>
                <p style="margin: 6px 0; color: #475569;">{{ board.description }}</p>
                <div style="color: #64748b;">{{ board.type }} · {{ board.posts_count }} posts</div>
            </div>
        </section>
    </AppLayout>
</template>
