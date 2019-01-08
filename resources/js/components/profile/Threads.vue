<template>
    <div>
        <div class="card" v-for="thread in threads" :key="thread.id">
            <div class="card-body flex flex-align-center">
                <div class="flex-1">
                    <b>
                        <a href="#" v-text="thread.title"></a>
                    </b>
                </div>

                <div>
                    <a :href="editUrl(thread)" class="btn btn-sm btn-warning">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['threads'],

    methods: {
        editUrl(thread) {
            return `/threads/edit/${thread.id}`;
        },
    }
}
</script>

<style>

</style>
