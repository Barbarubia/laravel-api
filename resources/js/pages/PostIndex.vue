<template>
    <div class="container">
        <div class="row mb-3">
            <div class="col">
                <h1>Posts</h1>
                <h5>Total posts: {{ totalPosts }}</h5>
            </div>
        </div>

        <div class="container">
            <div class="row mb-3">
                <div class="col">
                    <form @submit.prevent="getData(baseApiUrl + '?search=' + searchString + '&category=' + filterCategory + '&author=' + filterAuthor + '&tag=' + filterTag)" class="row d-flex bg-white border py-2 mb-5">
                        <div class="col-3 mb-2">
                            <label for="search-string" class="form-label mb-0">Text to search:</label>
                            <input type="text" class="form-control" id="search-string" name="search" v-model="searchString">
                        </div>

                        <div class="col-2 mb-2">
                            <label for="category" class="form-label mb-0">Category:</label><br>
                            <select class="form-select" aria-label="Default select example" name="category" id="category" v-model="filterCategory">
                                <option value="" selected>Select a category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.id }} - {{ category.category }}</option>
                            </select>
                        </div>

                        <div class="col-2mb-2">
                            <label for="author" class="form-label mb-0">Author:</label><br>
                            <select class="form-select" aria-label="Default select example" name="author" id="author" v-model="filterAuthor">
                                <option value="" selected>Select an author</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                            </select>
                        </div>

                        <!-- FIXME: filtro tag non funziona -->
                        <div class="col-2 mb-2">
                            <label for="tags" class="form-label mb-0">Tag:</label><br>
                            <select class="form-select" aria-label="Default select example" name="tags" id="tags" v-model="filterTag">
                                <option value="" selected>Select a tag</option>
                                <option v-for="tag in tags" :key="tag.id" :value="tag.name">{{ tag.name }}</option>
                            </select>
                        </div>

                        <div class="col-3 d-flex justify-content-between align-items-center">
                            <button class="btn btn-primary">Apply filters</button>
                        </div>
                    </form>
                </div>
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
        <!-- Numero pagina visualizzata con selettore pagina -->
        <div class="row mb-3">
            <div class="col text-center">
                <span>Page
                    <select name="page" id="page" v-model="selectedPage" @change="getData(baseApiUrl + '?search=' + searchString + '&category=' + filterCategory + '&author=' + filterAuthor + '&tag=' + filterTag + '&page=' + selectedPage)">
                        <option v-for="page in nLastPage" :key="page" :value="page">{{ page }}</option>
                    </select>
                    of {{ nLastPage }}
                </span>
            </div>
        </div>
        <!-- Navigazione tra le pagine -->
        <div class="row">
            <div class="col text-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item" :class="{disabled: nCurrentPage == 1}" @click="getData(baseApiUrl + firstPageUrl)">
                            <a class="page-link">First</a>
                        </li>

                        <li class="page-item" :class="{disabled: !prevPageUrl}" @click="getData(baseApiUrl + prevPageUrl)">
                            <a class="page-link">Previous</a>
                        </li>

                        <li class="page-item" :class="{disabled: !nextPageUrl}" @click="getData(baseApiUrl + nextPageUrl)">
                            <a class="page-link">Next</a>
                        </li>

                        <li class="page-item" :class="{disabled: nCurrentPage == nLastPage}" @click="getData(baseApiUrl + lastPageUrl)">
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
            categories: [],
            filterCategory: '',
            users: [],
            filterAuthor: '',
            tags: [],
            filterTag: '',
            searchString: '',
            posts: [],
            baseApiUrl: 'http://localhost:8000/api/posts',
            nCurrentPage: null,
            nLastPage: null,
            fistPageUrl: null,
            lastPageUrl: null,
            prevPageUrl: null,
            nextPageUrl: null,
            totalPosts: null,

            selectedPage: null,

            excerptMaxLength: 500,
        }
    },
    created() {
        this.getData(this.baseApiUrl);

        Axios.get(this.baseApiUrl)
            .then(response => {
                this.categories = response.data.categories;
                this.users = response.data.users;
                this.tags = response.data.tags;
            });
    },
    methods: {
        getData(url) {
            if (url) {
                Axios.get(url)
                .then(response => {
                    console.log(response);
                    this.posts =  response.data.results.data;
                    this.nCurrentPage = response.data.results.current_page;
                    this.selectedPage = this.nCurrentPage;
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
        },
    }
}
</script>

<style>
    .page-item {
        cursor: pointer;
    }
</style>
