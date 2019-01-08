<template>
    <div>
        <div class="card" v-for="(thread, index) in threads" :key="thread.id">
            <div class="card-body flex flex-align-center">
                <div class="flex-1">
                    <b>
                        <a href="#" v-text="thread.title"></a>
                    </b>
                </div>

                <div>
                    <a :href="editUrl(thread)" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#"
                        class="btn btn-sm btn-danger"
                        @click.prevent="showDeleteConfirmation(index)">Delete</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['dataThreads'],

    data() {
        return {
            threads: this.dataThreads,
        };
    },

    methods: {
        editUrl(thread) {
            return `/threads/edit/${thread.id}`;
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

            axios.delete(`/threads/${thread.id}`);
        }
    }
}
</script>

<style>

</style>
