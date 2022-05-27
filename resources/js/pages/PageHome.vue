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
                        <p class="card-text">{{ getExcerpt(post.content) }}</p>
                        <router-link :to="{name: 'postShow', params: {slug: post.slug}}" class="btn btn-primary">View post</router-link>
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

            excerptMaxLength: 500,
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
        },
        // metodo che visualizza solo una parte del contenuto del post se il testo è troppo lungo
        getExcerpt(content) {
            if (content.length > this.excerptMaxLength) {
                return content.substring(0, this.excerptMaxLength) + '...';
            } else {
                return content;
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
