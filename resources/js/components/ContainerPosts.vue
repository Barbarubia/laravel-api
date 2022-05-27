<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <router-link :to="{name: 'home'}">Home</router-link>
                <router-link :to="{name: 'postIndex'}">Blog</router-link>
                <router-link :to="{name: 'about'}">About</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ContainerPosts',
    data() {
        return {
            posts: [],
            baseApiUrl: 'http://localhost:8000/api/posts',
            nCurrentPage: null,
            nLastPage: null,
            fistPageUrl: null,
            lastPageUrl: null,
            prevPageUrl: null,
            nextPageUrl: null,
            totalPosts: null,
        }
    },
    created() {
        this.getData(this.baseApiUrl);
    },
    methods: {
        getData(url) {
            if (url) {
                Axios.get(url)
                .then(response => {
                    this.posts =  response.data.results.data;
                    this.nCurrentPage = response.data.results.current_page;
                    this.nLastPage = response.data.results.last_page;
                    this.firstPageUrl = response.data.results.first_page_url;
                    this.lastPageUrl = response.data.results.last_page_url;
                    this.prevPageUrl = response.data.results.prev_page_url;
                    this.nextPageUrl = response.data.results.next_page_url;
                    this.totalPosts = response.data.results.total;
                });
            }
        }
    }
}
</script>

<style>
    .page-item {
        cursor: pointer;
    }
</style>

