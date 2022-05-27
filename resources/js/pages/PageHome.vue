<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h1>Last 5 Posts</h1>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 mb-3" v-for="post in posts" :key="post.id">
                <div class="card">
                    <img v-if="post.image" :src="post.image" class="card-img-top" :alt="post.title">
                    <div class="card-body">
                        <h3 class="card-title">{{ post.title }}</h3>
                        <p class="card-text">{{ post.content }}</p>
                        <a :href="'/posts/' + post.slug" class="btn btn-primary">View post</a>
                    </div>
                </div>
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
            baseApiUrl: 'http://localhost:8000/api/posts?home',
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
