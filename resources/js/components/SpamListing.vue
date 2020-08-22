<template>
    <div>
        <data-list :columns="columns" :sort="false" :rows="rows">
            <div class="p-0 card" slot-scope="{ filteredRows: rows }">
                <data-list-table :rows="rows" :allow-bulk-actions="true">
                    <template slot="cell-id" slot-scope="{ row: collection }">
                        <a :href="collection.show_url">{{ collection.id }}</a>
                    </template>
                    <template slot="actions" slot-scope="{ row: collection, index }">
                        <dropdown-list>
                            <dropdown-item :text="__('Approve')" @click="approve(submission, index)" />
                            <dropdown-item :text="__('Discard')" class="warning" @click="discard(container, index)" />
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
            rows: Array,
        },

        data() {
            return {
                listingKey: "submissions",
                preferencesPrefix: `forms.${this.form}`,
                requestUrl: cp_url(`forms/${this.form}/submissions`),
            };
        },
    };
</script>
