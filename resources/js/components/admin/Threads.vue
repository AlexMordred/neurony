<template>
    <div>
        <h1>Manage Threads</h1>

        <div v-if="fetching">
            Loading...
        </div>

        <div v-else>
            <div class="card mb-4" v-for="(thread, index) in threads" :key="thread.id">
                <div class="card-header">
                    <p>
                        <b>
                            <a :href="`/threads/${thread.id}`" target="_blank">{{ thread.title }}</a>
                        </b>
                        ({{ thread.replies_count }} replies)
                    </p>

                    Posted by <b>{{ thread.author.name }}</b> at <b>{{ thread.created_at }} UTC</b>
                </div>

                <div class="card-body">
                    {{ thread.content.substr(0, 75) }}
                    {{ thread.content.length > 75 ? '...' : '' }}

                    <div class="mt-2">
                        <a href="#"
                            class="btn btn-danger btn-sm"
                            @click.prevent="showDeleteConfirmation(index)">Delete Thread</a>
                    </div>
                </div>
            </div>

            <v-paginator
                v-show="data.total / data.per_page > 1"
                :data="data"
                :page="page"
                @pageChanged="changePage"></v-paginator>
        </div>
    </div>
</template>

<script>
import Paginator from '../Paginator';

export default {
    components: {
        'v-paginator': Paginator,
    },

    data() {
        return {
            fetching: true,
            data: [],
            threads: [],
            page: 1,
            sortBy: '',
        };
    },

    created() {
        this.fetchThreads();
    },

    methods: {
        fetchThreads() {
            this.fetching = true;

            let endpoint = `/admin?page=${this.page}`;

            axios.get(endpoint)
                .then(({ data }) => {
                    this.data = data;
                    this.threads = data.data;

                    this.fetching = false;
                });
        },

        changePage(page) {
            this.page = page;

            this.fetchThreads();
        },

        showDeleteConfirmation(index) {
            let thread = this.threads[index];

            VoerroModal.show({
                title: `Delete thread "${thread.title}"?`,
                body: 'The operation is irreversible!',
                buttons: [
                    {
                        text: 'Delete',
                        handler: () => {
                            this.deleteThread(index);
                        }
                    },
                    {
                        text: 'Cancel'
                    }
                ]
            });
        },

        deleteThread(index) {
            let thread = this.threads[index];

            this.threads.splice(index, 1);

            axios.delete(`/admin/${thread.id}`)
                .then(() => {
                    this.fetchThreads();
                });
        }
    }
}
</script>

<style>

</style>
