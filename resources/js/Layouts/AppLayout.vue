<script setup>
import { usePage } from '@inertiajs/vue3';
import Sidebar from './Sidebar.vue';
import TopBar from './TopBar.vue';

const page = usePage();
</script>

<template>
    <div class="page-shell">
        <Sidebar />
        <main class="main">
            <TopBar />
            <div v-if="page.props.flash?.success" class="panel" style="border-color: #86efac; margin-bottom: 16px;">
                {{ page.props.flash.success }}
            </div>
            <slot />
        </main>
    </div>
</template>
