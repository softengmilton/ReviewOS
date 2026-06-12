<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '../../Layouts/AppLayout.vue';

defineProps({ plans: Array, currentPlan: String });
const form = useForm({ plan: '' });
</script>

<template>
    <AppLayout>
        <section class="grid" style="grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));">
            <div v-for="plan in plans" :key="plan.key" class="panel grid">
                <h2 style="margin: 0;">{{ plan.name }}</h2>
                <div style="font-size: 30px; font-weight: 800;">${{ plan.price }}</div>
                <div v-if="plan.key === currentPlan" style="color: #16a34a; font-weight: 800;">Current plan</div>
                <button class="button" type="button" @click="form.plan = plan.key; form.post(route('billing.checkout'))">Choose</button>
            </div>
        </section>
    </AppLayout>
</template>
