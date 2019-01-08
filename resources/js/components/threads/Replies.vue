<template>
    <div>
        <div v-if="fetching">
            Loading...
        </div>

        <div v-else>
            <div class="card mb-4" v-for="reply in replies" :key="reply.id">
                <div class="card-header">
                    Posted by <b>{{ reply.author.name }}</b> at <b>{{ reply.created_at }} UTC</b>
                </div>

                <div class="card-body" v-text="reply.content"></div>
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

    props: ['threadId'],

    data() {
        return {
            fetching: true,
            data: [],
            replies: [],
            page: 1,
        };
    },

    created() {
        this.fetchReplies();
    },

    watch: {
        sortBy() {
            this.fetchReplies();
        },

        selectedUser() {
            if (this.selectedUser !== '') {
                let user = this.availableUsers.find(item => item.id == this.selectedUser);

                this.selectedUsers.push(user);

                let index = this.availableUsers.indexOf(user);

                this.availableUsers.splice(index, 1);

                this.selectedUser = '';

                this.fetchReplies();
            }
        }
    },

    methods: {
        fetchReplies() {
            this.fetching = true;

            let endpoint = `/threads/${this.threadId}/replies?page=${this.page}`;

            axios.get(endpoint)
                .then(({ data }) => {
                    this.data = data;
                    this.replies = data.data;

                    this.fetching = false;
                });
        },

        changePage(page) {
            this.page = page;

            this.fetchReplies();
        },

        removeSelectedUser(user) {
            this.availableUsers.push(user);

            let index = this.selectedUsers.indexOf(user);

            this.selectedUsers.splice(index, 1);

            this.fetchReplies();
        }
    }
}
</script>

<style>

</style>
