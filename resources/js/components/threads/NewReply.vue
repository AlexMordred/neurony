<template>
    <div>
        <h4>Leave a Reply</h4>

        <form @submit.prevent="submitForm">
            <textarea class="form-control" rows="5" v-model="content"></textarea>

            <button type="submit" class="btn btn-primary">Send</button>
        </form>
    </div>
</template>

<script>
export default {
    props: ['threadId'],

    data() {
        return {
            content: '',
        };
    },

    methods: {
        submitForm() {
            if (this.content.length) {
                let data = new FormData();
                data.append('content', this.content);

                let endpoint = `/threads/${this.threadId}/replies`;

                axios.post(endpoint, data)
                    .then(() => {
                        this.$emit('replySaved');

                        this.content = '';
                    });
            }
        },
    }
}
</script>

<style>

</style>
