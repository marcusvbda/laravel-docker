<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import ListView from '../../../../packages/lazarus/src/js/components/ListPage/ListView.vue';

defineProps({
    payload : {
        type : Object,
        required : true
    }
});

</script>

<template>
    <Head :title="payload.appearance.title" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{payload.appearance.title}}</h2>
        </template> -->

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <ListView :payload="payload"/>
            </div>
        </div> 
    </AuthenticatedLayout>
</template>
