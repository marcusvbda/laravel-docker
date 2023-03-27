<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
    },
    quote: {
        type: String,
    },
});
</script>

<template>
    <Head title="Example" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Example</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h1>{{ title }}</h1>
                    <small>{{ quote }}</small>
                </div>
            </div>
        </div> 
    </AuthenticatedLayout>
</template>
