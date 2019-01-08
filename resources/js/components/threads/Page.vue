<template>
    <div>
        <h1>Threads</h1>

        <div v-if="fetching">
            Loading...
        </div>

        <div v-else>
            <div class="form-group">
                <label for="exampleFormControlSelect1">Sorting</label>
                <select class="form-control" v-model="sortBy">
                    <option value=''>Newest first</option>
                    <option value='abc'>Alphabetically</option>
                </select>
            </div>

            <div class="form-group">
                <label for="exampleFormControlSelect1">Filter by Users</label>
                <select class="form-control" v-model="selectedUser">
                    <option value=''>Select a user...</option>
                    <option v-for="user in availableUsers"
                        :key="user.id"
                        :value="user.id"
                        v-text="user.name"></option>
                </select>
            </div>

            <div class="mb-4">
                <span v-for="user in selectedUsers"
                    :key="user.id"
                >
                    {{ user.name }}
                    
                    <a href="#"
                        class="btn btn-sm btn-danger"
                        @click.prevent="removeSelectedUser(user)">x</a>
                </span>
            </div>

            <div class="card mb-4" v-for="thread in threads" :key="thread.id">
                <div class="card-header">
                    <p>
                        <b>
                            <a :href="`/threads/${thread.id}`">{{ thread.title }}</a>
                        </b>
                        ({{ thread.replies_count }} replies)
                    </p>

                    Posted by <b>{{ thread.author.name }}</b> at <b>{{ thread.created_at }} UTC</b>
                </div>

                <div class="card-body">
                    {{ thread.content.substr(0, 75) }}
                    {{ thread.content.length > 75 ? '...' : '' }}

                    <div class="mt-2">
                        <a :href="`/threads/${thread.id}`"
                            class="btn btn-primary btn-sm">Read Thread</a>
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

    props: ['allUsers'],

    data() {
        return {
            fetching: true,
            data: [],
            threads: [],
            page: 1,
            sortBy: '',
            availableUsers: this.allUsers,
            selectedUser: '',
            selectedUsers: [],
        };
    },

    created() {
        this.fetchThreads();
    },

    watch: {
        sortBy() {
            this.fetchThreads();
        },

        selectedUser() {
            if (this.selectedUser !== '') {
                let user = this.availableUsers.find(item => item.id == this.selectedUser);

                this.selectedUsers.push(user);

                let index = this.availableUsers.indexOf(user);

                this.availableUsers.splice(index, 1);

                this.selectedUser = '';

                this.fetchThreads();
            }
        }
    },

    methods: {
        fetchThreads() {
            this.fetching = true;

            let authors = this.selectedUsers.map(item => item.id).join(',');

            let endpoint = `/threads?page=${this.page}&sort=${this.sortBy}&authors=${authors}`;

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

        removeSelectedUser(user) {
            this.availableUsers.push(user);

            let index = this.selectedUsers.indexOf(user);

            this.selectedUsers.splice(index, 1);

            this.fetchThreads();
        }
    }
}
</script>

<style>

</style>
