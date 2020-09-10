<template>
    <div>
        <data-list :columns="columns" :sort="false" :rows="rows">
            <div class="p-0 card" slot-scope="{ filteredRows: rows }">
                <data-list-table :rows="rows" :allow-bulk-actions="true">
                    <template slot="cell-id" slot-scope="{ row: submission }">
                        <a :href="submission.show_url">{{ submission.id }}</a>
                    </template>
                    <template slot="actions" slot-scope="{ row: submission, index }">
                        <dropdown-list>
                            <dropdown-item
                                :text="__('Approve')"
                                @click="approving = submission.id"
                            >
                                <confirmation-modal
                                    v-if="approving"
                                    title="Approve"
                                    :bodyText="__('Are you sure you want to approve this submission?')"
                                    :buttonText="__('Approve')"
                                    :danger="true"
                                    @confirm="approve(submission.id, index)"
                                    @cancel="approving = false"
                                >
                                </confirmation-modal>
                            </dropdown-item>
                            <dropdown-item
                                :text="__('Discard')"
                                class="warning"
                                @click="discarding = submission.id"
                            >
                                <confirmation-modal
                                    v-if="discarding"
                                    title="Discard"
                                    :bodyText="__('Are you sure you want to discard this submission?')"
                                    :buttonText="__('Discard')"
                                    :danger="true"
                                    @confirm="discard(submission.id, index)"
                                    @cancel="discarding = false"
                                >
                                </confirmation-modal>
                            </dropdown-item>
                        </dropdown-list>
                    </template>
                </data-list-table>
            </div>
        </data-list>
    </div>
</template>

<script>
    export default {
        props: {
            form: String,
            columns: Array,
            initialRows: Array,
        },

        data() {
            return {
                approving: false,
                discarding: false,
                listingKey: "submissions",
                preferencesPrefix: `forms.${this.form}`,
                rows: this.initialRows,
                requestUrl: cp_url(`forms/${this.form}/submissions`),
            };
        },
        methods: {
            approve(id, index) {
                this.$axios.post(cp_url(`akismet/spam/${this.form}/${id}`))
                    .then(() => {
                        this.rows.splice(index, 1);
                        this.approving = false;
                        this.$toast.success('Spam approved');
                    })
                    .catch(e => {
                        this.approving = false;
                        this.$toast.error(e.response
                            ? e.response.data.message
                            : __('Something went wrong'));
                    });
            },
            discard(id, index) {
                this.$axios.delete(cp_url(`akismet/spam/${this.form}/${id}`))
                    .then(() => {
                        this.rows.splice(index, 1);
                        this.discarding = false;
                        this.$toast.success('Spam discarded');
                    })
                    .catch(e => {
                        console.log(e);
                        this.discarding = false;
                        this.$toast.error(e.response
                            ? e.response.data.message
                            : __('Something went wrong'));
                    });
            },
        }
    };
</script>
