<template>
    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <li class="page-item"
                    :class="{ 'disabled': this.data.current_page == 1 }"
                >
                    <a class="page-link"
                        href="#"
                        @click.prevent="updatePage(page - 1)">Previous</a>
                </li>
                <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li> -->
                <li class="page-item"
                    :class="{ 'disabled': this.data.current_page == this.data.last_page }"
                >
                    <a class="page-link"
                        href="#"
                        @click.prevent="updatePage(page + 1)">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    props: ['data', 'page'],

    methods: {
        updatePage(page) {
            this.$emit('pageChanged', page);
        }
    }
}
</script>

<style>

</style>
