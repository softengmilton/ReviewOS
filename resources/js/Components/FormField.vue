<script setup>
defineProps({
    label: String,
    error: String,
});
</script>

<template>
    <label class="grid" style="gap: 6px;">
        <span style="font-weight: 700;">{{ label }}</span>
        <slot />
        <span v-if="error" style="color: #dc2626; font-size: 14px;">{{ error }}</span>
    </label>
</template>
