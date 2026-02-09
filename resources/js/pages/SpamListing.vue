<template>
    <Head title="Spam" />

    <Heading :level=1 size="lg" class="mt-4">Spam</Heading>

    <Listing
        :actionUrl="runActionUrl"
        :allowBulkActions="true"
        :columns
        :url="requestUrl"
    >
        <template #prepended-row-actions="{ row: submission }">
            <DropdownItem :text="__('View')" :href="submissionUrl(submission.id)" icon="eye" />
        </template>
    </Listing>
</template>

<script setup>
import { Head } from '@statamic/cms/inertia';
import { DropdownItem, Heading, Listing } from '@statamic/cms/ui';

const props = defineProps({
    columns: { type: Array },
    form: { type: String, required: true },
})

const requestUrl = cp_url(`akismet/api/queues/${props.form}/spam`);
const runActionUrl = cp_url(`akismet/queues/${props.form}/spam/actions`);

function submissionUrl(submission) {
    return cp_url(`akismet/queues/${props.form}/spam/${submission.id}`);
}
</script>
