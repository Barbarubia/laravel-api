<template>
    <div class="container">
        <h1>{{ post.title }}</h1>
        <p>{{ post.content }}</p>
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
