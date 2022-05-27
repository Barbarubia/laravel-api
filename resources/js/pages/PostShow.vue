<template>
    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="text-capitalize">{{ post.title }}</h1>
            </div>
        </div>
        <div class="row d-flex justify-content-between mb-3">
            <div class="col-3 p-3">
                <h6>Info post:</h6>
                <small>Category: {{ post.category.category }}</small><br>
                <!-- @if ($post->tags->all()) -->
                    <small>Tags:
                            <span v-for="tag in post.tags" class="bg-primary rounded text-white px-2 py-1">{{ tag.name }}</span>
                    </small><br>
                <!-- @endif -->
                <small>Created: {{ post.created_at }}</small>
                    <br>
                    <small v-if="post.updated_at != post.created_at">Last update: {{ post.updated_at }}</small>
            </div>
            <div class="col-3 bg-white border border-info border-5 p-3">
                <h6>About the author:</h6>
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Name: {{ post.user.name }}</small><br>
                        <!-- @if ($post->user->userInfo->city)
                            <small>From: {{ $post->user->userInfo->city }}</small><br>
                        @endif
                        @if ($post->user->userInfo->birthday)
                            <small>Age: {{ $post->user->userInfo->age() }}</small>
                        @endif -->
                    </div>
                    <!-- @if ($post->user->userInfo->avatar)
                        <img class="w-25 h-25 rounded-circle" src="{{ $post->user->userInfo->avatar }}" alt="{{ post.user.name }}'s Avatar">
                    @endif -->
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <img v-if="post.image != null" class="mb-3" :src="post.image" :alt="post.title">

                <p>{{ post.content }}</p>
            </div>
        </div>
        <!-- <div class="row mt-3">
            <div class="col">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary fw-bold">Go to index</a>
            </div>
        </div> -->
    </div>
</template>

<script>
export default {
    name: 'PostShow',
    props: ['slug'],
    data() {
        return {
            post: '',
            baseApiUrl: 'http://localhost:8000/api/posts',
        }
    },
    created() {
        this.getData(this.baseApiUrl + '/' + this.slug);
    },
    methods: {
        getData(url) {
            if (url) {
                Axios.get(url)
                .then(response => {
                    if (response.data.success) {
                        this.post =  response.data.results.data;
                    } else {
                        this.$router.push({name: 'error404'});
                    }
                });
            }
        }
    }
}
</script>

<style>

</style>
