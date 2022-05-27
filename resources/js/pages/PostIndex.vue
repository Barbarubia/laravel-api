<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h1>Posts</h1>
                <h5>Total posts: {{ totalPosts }}</h5>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 mb-3" v-for="post in posts" :key="post.id">
                <div class="card">
                    <img v-if="post.image" :src="post.image" class="card-img-top" :alt="post.title">
                    <div class="card-body">
                        <h3 class="card-title text-capitalize">{{ post.title }}</h3>
                        <small>Author: {{ post.user.name }}</small><br>
                        <small class="mt-0">Category: {{ post.category.category }}</small>
                        <small v-if="post.tags.length > 0">
                            <br>
                            Tags:
                            <span v-for="tag in post.tags" :key="tag.id" class="bg-primary rounded text-white px-2 py-1 mx-1">{{ tag.name }}</span>
                        </small>

                        <p class="card-text mt-3">{{ getExcerpt(post.content) }}</p>
                        <router-link :to="{name: 'postShow', params: {slug: post.slug}}" class="btn btn-primary">View post</router-link>
                    </div>
                </div>
            </div>
        </div>
        <!-- Numero pagina visualizzata -->
        <div class="row">
            <div class="col text-center">
                <span>Page {{ nCurrentPage }} of {{ nLastPage }}</span>
            </div>
        </div>
        <!-- Navigazione tra le pagine -->
        <div class="row">
            <div class="col text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{disabled: nCurrentPage == 1}" @click="getData(firstPageUrl)">
                            <a class="page-link">First</a>
                        </li>

                        <li class="page-item" :class="{disabled: !prevPageUrl}" @click="getData(prevPageUrl)">
                            <a class="page-link">Previous</a>
                        </li>

                        <li class="page-item" :class="{disabled: !nextPageUrl}" @click="getData(nextPageUrl)">
                            <a class="page-link">Next</a>
                        </li>

                        <li class="page-item" :class="{disabled: nCurrentPage == nLastPage}" @click="getData(lastPageUrl)">
                            <a class="page-link">Last</a>
                        </li>
                    </ul>
                </nav>
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
                    this.nCurrentPage = response.data.results.current_page;
                    this.nLastPage = response.data.results.last_page;
                    this.firstPageUrl = response.data.results.first_page_url;
                    this.lastPageUrl = response.data.results.last_page_url;
                    this.prevPageUrl = response.data.results.prev_page_url;
                    this.nextPageUrl = response.data.results.next_page_url;
                    this.totalPosts = response.data.results.total;
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
