<script setup>
import { Head, Link } from '@statamic/cms/inertia';
import { Badge, Heading, Listing } from '@statamic/cms/ui';

const props = defineProps({
    queues: { type: Array, required: true },
})
</script>

<template>
    <Head title="Spam Queues" />

    <div>
        <Heading :level=1 size="lg" class="mt-4">Queues</Heading>
        <div class="mt-4">
            <Listing
                :allowCustomizingColumns="false"
                :allowSearch="false"
                :columns="[
                    { field: 'title', label: 'Form' },
                    { field: 'count', label: 'Spam' },
                ]"
                :items="queues"
                :sortable="false"
            >
                <template #cell-title="{ row, value }">
                    <Link :href="row.link" v-html="value" />
                </template>
                <template #cell-count="{ row, value }">
                    <Badge color="red" :text="value" pill />
                </template>
            </Listing>

        </div>
    </div>
</template>
